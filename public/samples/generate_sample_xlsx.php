<?php

/**
 * Generate a sample items_import_sample.xlsx for the BarliTor Shop import demo.
 * Run once: php generate_sample_xlsx.php
 */

require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Products Import');

// ── Headers ─────────────────────────────────────────────────────
$headers = ['title', 'description', 'cost_price', 'sell_price', 'category', 'type', 'stock_quantity', 'supplier_id'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// Style headers
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E65100'], // Orange
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

// ── Sample Data Rows ─────────────────────────────────────────────
$items = [
    ['NGK Iridium Spark Plug', 'Premium iridium spark plug for 4-stroke motorcycle engines. Long-lasting and reliable ignition performance.', 120.00, 250.00, 'Engine', 'product', 75, null],
    ['Motul 5100 10W-40 Engine Oil (1L)', 'Semi-synthetic 4-stroke motorcycle engine oil. Excellent thermal stability and engine protection.', 320.00, 550.00, 'Engine', 'product', 120, null],
    ['EBC Double-H Sintered Brake Pads', 'High-performance sintered brake pads for sport and street riding. Excellent wet and dry braking.', 450.00, 850.00, 'Brakes', 'product', 40, null],
    ['DID 520VX3 Gold X-Ring Chain', 'Professional-grade X-ring chain with gold side plates. Superior durability for street and track use.', 1800.00, 3200.00, 'Drivetrain', 'product', 25, null],
    ['Koso RX-2N GP Style Speedometer', 'Digital speedometer with tachometer, odometer, and fuel gauge. Compact, modern dashboard upgrade.', 2200.00, 3800.00, 'Electrical', 'product', 15, null],
    ['Motion Pro Cable Luber V3', 'Professional cable lubrication tool. Makes throttle and clutch cable maintenance quick and mess-free.', 350.00, 650.00, 'Tools', 'tool', 30, null],
    ['Park Tool Torque Wrench TW-5.2', 'Precision click-type torque wrench with 3-15 Nm range. Essential for proper bolt tightening on delicate components.', 1500.00, 2800.00, 'Tools', 'tool', 10, null],
    ['K&N High-Flow Air Filter', 'Washable and reusable high-flow air filter. Increases airflow by up to 50% over stock paper filters.', 800.00, 1500.00, 'Engine', 'product', 35, null],
    ['Yoshimura Alpha T Slip-On Exhaust', 'Stainless steel slip-on exhaust with carbon fiber end cap. Improved performance and aggressive sound.', 8500.00, 15999.00, 'Exhaust', 'product', 5, null],
    ['Renthal Fatbar Handlebar', 'Oversized 28.6mm handlebar with variable wall thickness. 7075-T6 aluminum for maximum strength.', 2000.00, 3500.00, 'Chassis', 'product', 20, null],
];

$row = 2;
foreach ($items as $item) {
    $sheet->setCellValue('A' . $row, $item[0]);
    $sheet->setCellValue('B' . $row, $item[1]);
    $sheet->setCellValue('C' . $row, $item[2]);
    $sheet->setCellValue('D' . $row, $item[3]);
    $sheet->setCellValue('E' . $row, $item[4]);
    $sheet->setCellValue('F' . $row, $item[5]);
    $sheet->setCellValue('G' . $row, $item[6]);
    $sheet->setCellValue('H' . $row, $item[7] ?? '');

    // Alternate row colors
    if ($row % 2 === 0) {
        $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF3E0'],
            ],
        ]);
    }
    $row++;
}

// Data borders
$sheet->getStyle('A1:H' . ($row - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC'],
        ],
    ],
]);

// ── Save ──────────────────────────────────────────────────────────
$outputPath = __DIR__ . '/items_import_sample.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);

echo "✅ Created: {$outputPath}\n";
echo "   → " . count($items) . " sample items (8 products + 2 tools)\n";
