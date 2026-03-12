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
        $chartYear = (int) $request->input('year', date('Y'));
        if ($chartYear < 2000 || $chartYear > (int) date('Y') + 1) {
            $chartYear = (int) date('Y');
        }

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

        // Always fetch both sets of rows for charts (tables still respect $viewType below).
        $materialsRowsAll = DB::table('products_sold')
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

        $rentalsRowsAll = DB::table('rental')
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

        if ($viewType === 'all' || $viewType === 'materials') {
            $materialsTotal = (float) DB::table('products_sold')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->selectRaw('COALESCE(SUM(products_sold.quantity * products_sold.rate_charged), 0) as total')
                ->value('total');

            $materialsRows = $materialsRowsAll;
        }

        if ($viewType === 'all' || $viewType === 'rentals') {
            $rentalsTotal = (float) DB::table('rental')
                ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
                ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
                ->selectRaw('COALESCE(SUM(rental.rate_charged), 0) as total')
                ->value('total');

            $rentalsRows = $rentalsRowsAll;
        }

        $totalIncome = $materialsTotal + $rentalsTotal;

        $expenseTotal = (float) Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $netIncome = $totalIncome - $expenseTotal;

        // Chart 1 — Yearly monthly totals (always both series)
        $monthlyMaterials = DB::table('products_sold')
            ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
            ->whereYear('orderinfo.date_placed', $chartYear)
            ->selectRaw('MONTH(orderinfo.date_placed) as month, SUM(products_sold.quantity * products_sold.rate_charged) as total')
            ->groupByRaw('MONTH(orderinfo.date_placed)')
            ->pluck('total', 'month');

        $monthlyRentals = DB::table('rental')
            ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'rental.transaction_id')
            ->whereYear('orderinfo.date_placed', $chartYear)
            ->selectRaw('MONTH(orderinfo.date_placed) as month, SUM(rental.rate_charged) as total')
            ->groupByRaw('MONTH(orderinfo.date_placed)')
            ->pluck('total', 'month');

        $yearlyMaterialsData = [];
        $yearlyRentalsData = [];
        for ($m = 1; $m <= 12; $m++) {
            $yearlyMaterialsData[] = round((float) ($monthlyMaterials[$m] ?? 0), 2);
            $yearlyRentalsData[] = round((float) ($monthlyRentals[$m] ?? 0), 2);
        }

        // Chart 2 — Date-range daily totals (derived from already fetched rows)
        $dailyMaterials = $materialsRowsAll->groupBy('date_placed')
            ->map(fn ($rows) => round($rows->sum('line_total'), 2))
            ->toArray();

        $dailyRentals = $rentalsRowsAll->groupBy('date_placed')
            ->map(fn ($rows) => round($rows->sum('line_total'), 2))
            ->toArray();

        $allDates = collect(array_keys($dailyMaterials))
            ->merge(array_keys($dailyRentals))
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $dateRangeLabels = $allDates;
        $dateRangeMaterials = array_map(fn ($d) => $dailyMaterials[$d] ?? 0.0, $allDates);
        $dateRangeRentals = array_map(fn ($d) => $dailyRentals[$d] ?? 0.0, $allDates);

        // Chart 3 — Product sales revenue breakdown (materials only)
        $productPieData = DB::table('products_sold')
            ->join('orderinfo', 'orderinfo.orderinfo_id', '=', 'products_sold.transaction_id')
            ->join('item', 'item.item_id', '=', 'products_sold.product_id')
            ->whereBetween('orderinfo.date_placed', [$dateFrom, $dateTo])
            ->selectRaw('item.title, SUM(products_sold.quantity * products_sold.rate_charged) as total')
            ->groupBy('item.item_id', 'item.title')
            ->orderByDesc('total')
            ->get();

        $pieLabels = $productPieData->pluck('title')->toArray();
        $pieValues = $productPieData->map(fn ($r) => round((float) $r->total, 2))->toArray();

        return view('admin.reports.index', compact(
            'dateFrom', 'dateTo', 'viewType',
            'materialsTotal', 'rentalsTotal', 'totalIncome',
            'expenseTotal', 'netIncome',
            'materialsRows', 'rentalsRows',
            'chartYear',
            'yearlyMaterialsData', 'yearlyRentalsData',
            'dateRangeLabels', 'dateRangeMaterials', 'dateRangeRentals',
            'pieLabels', 'pieValues'
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
