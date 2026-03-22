@extends('layouts.app')
@section('title', 'Reports & Analytics - BarliTor Shop Admin')
@section('title_header', 'Reports & Analytics')

@section('content')
<div class="mb-8">
    <div class="bg-[#1a1a1a] p-5 rounded-xl border border-gray-800 shadow-sm">
        <form method="get" action="{{ route('admin.reports') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">From (Order Date)</label>
                <input type="date" name="from" value="{{ $dateFrom }}" class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">To (Order Date)</label>
                <input type="date" name="to" value="{{ $dateTo }}" class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">View Type</label>
                <div class="flex rounded-md shadow-sm" role="group">
                    <button type="submit" name="type" value="all" class="flex-1 px-4 py-2.5 text-sm font-medium border {{ $viewType === 'all' ? 'bg-orange-600 border-orange-600 text-white z-10' : 'bg-[#111111] border-gray-700 text-gray-300 hover:bg-gray-800' }} rounded-l-lg focus:z-10 focus:ring-2 focus:ring-orange-500 transition-colors">
                        All
                    </button>
                    <button type="submit" name="type" value="materials" class="flex-1 px-4 py-2.5 text-sm font-medium border-t border-b border-r {{ $viewType === 'materials' ? 'bg-orange-600 border-orange-600 text-white z-10' : 'bg-[#111111] border-gray-700 text-gray-300 hover:bg-gray-800' }} focus:z-10 focus:ring-2 focus:ring-orange-500 transition-colors">
                        Materials
                    </button>
                    <button type="submit" name="type" value="rentals" class="flex-1 px-4 py-2.5 text-sm font-medium border-t border-b border-r {{ $viewType === 'rentals' ? 'bg-orange-600 border-orange-600 text-white z-10' : 'bg-[#111111] border-gray-700 text-gray-300 hover:bg-gray-800' }} rounded-r-lg focus:z-10 focus:ring-2 focus:ring-orange-500 transition-colors">
                        Rentals
                    </button>
                </div>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2.5 px-4 rounded-md transition-colors border border-gray-600 shadow-sm text-sm">
                    Apply
                </button>
                <a href="{{ route('admin.reports', ['from' => $dateFrom, 'to' => $dateTo, 'type' => $viewType, 'export' => 1]) }}"
                   class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-md transition-colors text-center shadow-sm text-sm" title="Export CSV">
                    <i class="fa-solid fa-file-csv"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Yearly Chart Year Selector -->
<div class="bg-[#1a1a1a] p-4 rounded-xl border border-gray-800 shadow-sm mb-8">
    <form method="get" action="{{ route('admin.reports') }}" class="flex flex-wrap items-end gap-4">
        {{-- Preserve existing date range and view type --}}
        <input type="hidden" name="from" value="{{ $dateFrom }}">
        <input type="hidden" name="to" value="{{ $dateTo }}">
        <input type="hidden" name="type" value="{{ $viewType }}">

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">
                Yearly Chart — Select Year
            </label>
            <select name="year"
                class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 p-2.5 pr-8">
                @for($y = (int) date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $chartYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <button type="submit"
            class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2.5 px-4 rounded-md transition-colors border border-gray-600 text-sm">
            Update Chart
        </button>
    </form>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111111] p-5 rounded-xl border border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-box text-5xl text-white"></i>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Materials Income</p>
        <h3 class="text-2xl font-black text-white">₱{{ number_format($materialsTotal, 2) }}</h3>
    </div>
    
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111111] p-5 rounded-xl border border-gray-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="fa-solid fa-wrench text-5xl text-white"></i>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Rentals Income</p>
        <h3 class="text-2xl font-black text-white">₱{{ number_format($rentalsTotal, 2) }}</h3>
    </div>
    
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111111] p-5 rounded-xl border border-red-900/30 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity text-red-500">
            <i class="fa-solid fa-arrow-trend-down text-5xl"></i>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Expenses</p>
        <h3 class="text-2xl font-black text-red-400">₱{{ number_format($expenseTotal, 2) }}</h3>
    </div>
    
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111111] p-5 rounded-xl border {{ $netIncome >= 0 ? 'border-green-900/30' : 'border-red-900/30' }} shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity {{ $netIncome >= 0 ? 'text-green-500' : 'text-red-500' }}">
            <i class="fa-solid fa-money-bill-wave text-5xl"></i>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Net Income</p>
        <h3 class="text-2xl font-black {{ $netIncome >= 0 ? 'text-green-400' : 'text-red-400' }}">
            {{ $netIncome < 0 ? '-' : '' }}₱{{ number_format(abs($netIncome), 2) }}
        </h3>
    </div>
</div>

<!-- Charts Section -->
<div class="mb-8 space-y-6">

    <!-- Row 1: Yearly Bar Chart + Pie Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart 1: Yearly Sales Bar Chart -->
        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-orange-500"></i>
                Monthly Sales — {{ $chartYear }}
            </h3>
            <div class="relative h-64">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Product Sales Pie Chart -->
        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-orange-500"></i>
                Product Sales Breakdown
                <span class="text-xs text-gray-500 font-normal normal-case ml-1">
                    ({{ $dateFrom }} to {{ $dateTo }})
                </span>
            </h3>
            @if(count($pieLabels) > 0)
                <div class="relative h-64">
                    <canvas id="pieChart"></canvas>
                </div>
            @else
                <div class="h-64 flex items-center justify-center text-gray-500 text-sm">
                    <div class="text-center">
                        <i class="fa-solid fa-inbox text-3xl mb-2 block text-gray-600"></i>
                        No product sales in this date range.
                    </div>
                </div>
            @endif
        </div>

    </div>

    <!-- Row 2: Date-Range Sales Bar Chart (full width) -->
    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-column text-orange-500"></i>
            Daily Sales — {{ $dateFrom }} to {{ $dateTo }}
        </h3>
        @if(count($dateRangeLabels) > 0)
            <div class="relative h-64">
                <canvas id="rangeChart"></canvas>
            </div>
        @else
            <div class="h-64 flex items-center justify-center text-gray-500 text-sm">
                <div class="text-center">
                    <i class="fa-solid fa-inbox text-3xl mb-2 block text-gray-600"></i>
                    No sales data in this date range.
                </div>
            </div>
        @endif
    </div>

</div>

<div class="space-y-8">
    @if($viewType === 'all' || $viewType === 'materials')
        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 bg-[#111111]">
                <h3 class="text-lg font-bold text-white uppercase tracking-wide flex items-center">
                    <i class="fa-solid fa-box text-orange-500 mr-2"></i> Materials Sold
                </h3>
            </div>
            
            @if($materialsRows->isEmpty())
                <div class="p-8 text-center text-gray-400">
                    <i class="fa-solid fa-inbox text-3xl mb-3 text-gray-600"></i>
                    <p>No materials sold in the selected date range.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Transaction #</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Order Date</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Item</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-center">Quantity</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-right">Rate</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60">
                            @foreach($materialsRows as $row)
                                <tr class="hover:bg-[#111111] transition-colors">
                                    <td class="px-6 py-4 font-medium text-orange-400">#{{ $row->orderinfo_id }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ \Carbon\Carbon::parse($row->date_placed)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-white">{{ $row->title }}</td>
                                    <td class="px-6 py-4 text-center text-gray-300">{{ (int)$row->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-gray-300">₱{{ number_format($row->rate_charged, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-white">₱{{ number_format($row->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    @if($viewType === 'all' || $viewType === 'rentals')
        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-800 bg-[#111111]">
                <h3 class="text-lg font-bold text-white uppercase tracking-wide flex items-center">
                    <i class="fa-solid fa-wrench text-orange-500 mr-2"></i> Tool Rentals
                </h3>
            </div>
            
            @if($rentalsRows->isEmpty())
                <div class="p-8 text-center text-gray-400">
                    <i class="fa-solid fa-inbox text-3xl mb-3 text-gray-600"></i>
                    <p>No tool rentals in the selected date range.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Transaction #</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Order Date</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Tool</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-center">Rental Period</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-center">Qty</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-right">Total Charged</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60">
                            @foreach($rentalsRows as $row)
                                <tr class="hover:bg-[#111111] transition-colors">
                                    <td class="px-6 py-4 font-medium text-orange-400">#{{ $row->orderinfo_id }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ \Carbon\Carbon::parse($row->date_placed)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-white">{{ $row->title }}</td>
                                    <td class="px-6 py-4 text-center text-gray-300">
                                        <div class="flex flex-col text-xs">
                                            <span>{{ \Carbon\Carbon::parse($row->start_date)->format('M d') }} to {{ \Carbon\Carbon::parse($row->due_date)->format('M d') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-300">{{ (int)$row->quantity }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-white">₱{{ number_format($row->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script type="application/json" id="chart_yearly_materials">@json($yearlyMaterialsData)</script>
<script type="application/json" id="chart_yearly_rentals">@json($yearlyRentalsData)</script>
<script type="application/json" id="chart_range_labels">@json($dateRangeLabels)</script>
<script type="application/json" id="chart_range_materials">@json($dateRangeMaterials)</script>
<script type="application/json" id="chart_range_rentals">@json($dateRangeRentals)</script>
<script type="application/json" id="chart_pie_labels">@json($pieLabels)</script>
<script type="application/json" id="chart_pie_values">@json($pieValues)</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.Chart) return;

        function readJson(id, fallback) {
            var el = document.getElementById(id);
            if (!el) return fallback;
            try {
                return JSON.parse(el.textContent || 'null') ?? fallback;
            } catch (e) {
                return fallback;
            }
        }

        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = '#1f2937';
        Chart.defaults.font.family = 'inherit';

        const gridColor = 'rgba(75, 85, 99, 0.3)';
        const yearlyMaterialsData = readJson('chart_yearly_materials', []);
        const yearlyRentalsData = readJson('chart_yearly_rentals', []);
        const rangeLabels = readJson('chart_range_labels', []);
        const rangeMaterials = readJson('chart_range_materials', []);
        const rangeRentals = readJson('chart_range_rentals', []);
        const pieLabels = readJson('chart_pie_labels', []);
        const pieValues = readJson('chart_pie_values', []);

        const yearlyCanvas = document.getElementById('yearlyChart');
        if (yearlyCanvas) {
            new Chart(yearlyCanvas, {
                type: 'bar',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: [
                        {
                            label: 'Materials',
                            data: yearlyMaterialsData,
                            backgroundColor: 'rgba(249, 115, 22, 0.75)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'Rentals',
                            data: yearlyRentalsData,
                            backgroundColor: 'rgba(59, 130, 246, 0.65)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#d1d5db', boxWidth: 12 } },
                        tooltip: {
                            backgroundColor: '#111111',
                            borderColor: '#374151',
                            borderWidth: 1,
                            titleColor: '#f3f4f6',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            callbacks: {
                                label: function (ctx) {
                                    return ' ' + ctx.dataset.label + ': ₱' + Number(ctx.parsed.y || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: '#9ca3af' } },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: '#9ca3af',
                                callback: function (v) { return '₱' + Number(v).toLocaleString('en-PH'); }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const rangeCanvas = document.getElementById('rangeChart');
        if (rangeCanvas) {
            new Chart(rangeCanvas, {
                type: 'bar',
                data: {
                    labels: rangeLabels,
                    datasets: [
                        {
                            label: 'Materials',
                            data: rangeMaterials,
                            backgroundColor: 'rgba(249, 115, 22, 0.75)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 1,
                            borderRadius: 3,
                            stack: 'sales',
                        },
                        {
                            label: 'Rentals',
                            data: rangeRentals,
                            backgroundColor: 'rgba(59, 130, 246, 0.65)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 3,
                            stack: 'sales',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: '#d1d5db', boxWidth: 12 } },
                        tooltip: {
                            backgroundColor: '#111111',
                            borderColor: '#374151',
                            borderWidth: 1,
                            titleColor: '#f3f4f6',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            callbacks: {
                                label: function (ctx) {
                                    return ' ' + ctx.dataset.label + ': ₱' + Number(ctx.parsed.y || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        x: { stacked: true, grid: { color: gridColor }, ticks: { color: '#9ca3af', maxRotation: 45 } },
                        y: {
                            stacked: true,
                            grid: { color: gridColor },
                            ticks: {
                                color: '#9ca3af',
                                callback: function (v) { return '₱' + Number(v).toLocaleString('en-PH'); }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        const pieCanvas = document.getElementById('pieChart');
        if (pieCanvas) {
            const labels = pieLabels;
            const values = pieValues;

            const palette = [
                'rgba(249,115,22,0.85)',
                'rgba(59,130,246,0.85)',
                'rgba(16,185,129,0.85)',
                'rgba(245,158,11,0.85)',
                'rgba(139,92,246,0.85)',
                'rgba(236,72,153,0.85)',
                'rgba(20,184,166,0.85)',
                'rgba(239,68,68,0.85)',
                'rgba(99,102,241,0.85)',
                'rgba(251,191,36,0.85)',
            ];
            const colors = labels.map(function (_, i) { return palette[i % palette.length]; });

            new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: '#111111',
                        borderWidth: 2,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#d1d5db',
                                boxWidth: 12,
                                padding: 10,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#111111',
                            borderColor: '#374151',
                            borderWidth: 1,
                            titleColor: '#f3f4f6',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            callbacks: {
                                label: function (ctx) {
                                    const total = (ctx.dataset.data || []).reduce(function (a, b) { return a + Number(b || 0); }, 0);
                                    const val = Number(ctx.parsed || 0);
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : '0.0';
                                    return ' ₱' + val.toLocaleString('en-PH', { minimumFractionDigits: 2 }) + '  (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
