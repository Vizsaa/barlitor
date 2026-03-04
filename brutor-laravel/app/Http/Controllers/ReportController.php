<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ProductSold;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('from', date('Y-m-01'));
        $dateTo = $request->input('to', date('Y-m-d'));
        $viewType = $request->input('type', 'all');

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) $dateFrom = date('Y-m-01');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) $dateTo = date('Y-m-d');

        // Export CSV
        if ($request->input('export') === '1') {
            return $this->exportCsv($dateFrom, $dateTo, $viewType);
        }

        $materialsTotal = 0.0;
        $rentalsTotal = 0.0;
        $materialsRows = collect();
        $rentalsRows = collect();

        if ($viewType === 'all' || $viewType === 'materials') {
            $materialsTotal = (float) DB::table('products_sold')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->selectRaw('COALESCE(SUM(products_sold.quantity * products_sold.rate_charged), 0) as total')
                ->value('total');

            $materialsRows = DB::table('products_sold')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
                ->join('item', 'item.item_id', '=', 'products_sold.product_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->select(
                    'orderinfo.orderinfo_id',
                    'orderinfo.date_placed',
                    'item.title',
                    'products_sold.quantity',
                    'products_sold.rate_charged',
                    DB::raw('(products_sold.quantity * products_sold.rate_charged) as line_total')
                )
                ->orderByDesc('orderinfo.date_placed')
                ->orderByDesc('orderinfo.orderinfo_id')
                ->get();
        }

        if ($viewType === 'all' || $viewType === 'rentals') {
            $rentalsTotal = (float) DB::table('rental')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->selectRaw('COALESCE(SUM(rental.rate_charged), 0) as total')
                ->value('total');

            $rentalsRows = DB::table('rental')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
                ->join('item', 'item.item_id', '=', 'rental.item_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->select(
                    'orderinfo.orderinfo_id',
                    'orderinfo.date_placed',
                    'item.title',
                    'rental.start_date',
                    'rental.due_date',
                    'rental.quantity',
                    'rental.rate_charged as line_total'
                )
                ->orderByDesc('orderinfo.date_placed')
                ->orderByDesc('orderinfo.orderinfo_id')
                ->get();
        }

        $totalIncome = $materialsTotal + $rentalsTotal;

        $expenseTotal = (float) Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $netIncome = $totalIncome - $expenseTotal;

        return view('admin.reports.index', compact(
            'dateFrom', 'dateTo', 'viewType',
            'materialsTotal', 'rentalsTotal', 'totalIncome',
            'expenseTotal', 'netIncome',
            'materialsRows', 'rentalsRows'
        ));
    }

    private function exportCsv($dateFrom, $dateTo, $viewType)
    {
        $filename = "brutor_report_{$dateFrom}_to_{$dateTo}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($dateFrom, $dateTo, $viewType) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Type', 'Transaction ID', 'Order Date', 'Item', 'Quantity', 'Rate Charged', 'Line Total']);

            if ($viewType === 'all' || $viewType === 'materials') {
                $rows = DB::table('products_sold')
                    ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
                    ->join('item', 'item.item_id', '=', 'products_sold.product_id')
                    ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                    ->select(
                        'orderinfo.orderinfo_id', 'orderinfo.date_placed', 'item.title',
                        'products_sold.quantity', 'products_sold.rate_charged',
                        DB::raw('(products_sold.quantity * products_sold.rate_charged) as line_total')
                    )
                    ->orderByDesc('orderinfo.date_placed')
                    ->get();

                foreach ($rows as $row) {
                    fputcsv($output, [
                        'Materials', $row->orderinfo_id, $row->date_placed, $row->title,
                        (int) $row->quantity, number_format($row->rate_charged, 2),
                        number_format($row->line_total, 2),
                    ]);
                }
            }

            if ($viewType === 'all' || $viewType === 'rentals') {
                $rows = DB::table('rental')
                    ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
                    ->join('item', 'item.item_id', '=', 'rental.item_id')
                    ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                    ->select(
                        'orderinfo.orderinfo_id', 'orderinfo.date_placed', 'item.title',
                        'rental.quantity', 'rental.rate_charged as line_total'
                    )
                    ->orderByDesc('orderinfo.date_placed')
                    ->get();

                foreach ($rows as $row) {
                    fputcsv($output, [
                        'Rentals', $row->orderinfo_id, $row->date_placed, $row->title,
                        (int) $row->quantity, '', number_format($row->line_total, 2),
                    ]);
                }
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
