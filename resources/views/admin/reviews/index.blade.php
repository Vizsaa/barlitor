@extends('layouts.app')
@section('title', 'Customer Reviews - BarliTor Shop Admin')
@section('title_header', 'Customer Reviews')

@section('content')
<div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#111111]">
        <div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Customer Reviews</h3>
            <p class="text-xs text-gray-400 mt-1">Total reviews: {{ $reviews->count() }}</p>
        </div>
    </div>

    @if($reviews->count() > 0)
        <div class="overflow-x-auto">
            <table id="reviewsTable" class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">Item</th>
                        <th class="px-6 py-4 font-semibold">Customer</th>
                        <th class="px-6 py-4 font-semibold">Rating</th>
                        <th class="px-6 py-4 font-semibold">Comment</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @foreach($reviews as $index => $review)
                        <tr class="hover:bg-[#111111] transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400 w-16">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-white">
                                    <a href="{{ route('items.show', $review->item_id) }}" class="hover:text-orange-400">
                                        {{ $review->item->title ?? 'Unknown Item' }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    ID: {{ $review->item_id }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-white">
                                    {{ $review->user->name ?? 'Deleted User' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $review->user->email ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center text-yellow-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star mr-0.5"></i>
                                        @else
                                            <i class="fa-regular fa-star mr-0.5 text-gray-600"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-gray-400">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="text-sm text-gray-300">
                                    {{ \Illuminate\Support\Str::limit($review->comment, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST"
                                      onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 border border-red-800/50 text-red-400 hover:text-red-300 rounded text-xs font-semibold transition-colors shadow-sm">
                                        <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-8 text-center text-gray-400">
            <i class="fa-regular fa-comments text-4xl mb-3 text-gray-600"></i>
            <h4 class="text-white font-bold mb-1">No Reviews Found</h4>
            <p>There are currently no customer reviews in the system.</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        #reviewsTable_wrapper {
            color: #e5e7eb;
        }
        #reviewsTable_wrapper .dataTables_length select,
        #reviewsTable_wrapper .dataTables_filter input {
            background-color: #111111;
            border-color: #374151;
            color: #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
        }
        #reviewsTable_wrapper .dataTables_paginate .paginate_button {
            color: #9ca3af !important;
        }
        #reviewsTable_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #f97316 !important;
            color: #ffffff !important;
            border-color: #ea580c !important;
        }
        #reviewsTable_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }
        #reviewsTable tbody tr:hover {
            background-color: #111111;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && $('#reviewsTable').length) {
                $('#reviewsTable').DataTable({
                    pageLength: 15,
                    order: [[0, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 2, 3, 4, 6] }
                    ],
                    language: {
                        search: "Search reviews:",
                        lengthMenu: "Show _MENU_ reviews per page",
                    }
                });
            }
        });
    </script>
@endpush

