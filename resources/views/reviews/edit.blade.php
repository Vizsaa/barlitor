@extends('layouts.app')
@section('title', 'Edit Review - BarliTor Shop')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-lg w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl shadow-xl">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen text-orange-500"></i>
                    <span>Edit Your Review</span>
                </h1>
                @if($review->item ?? false)
                    <p class="text-xs text-gray-400 mt-1">
                        For item:
                        <a href="{{ route('items.show', $review->item_id) }}" class="text-orange-400 hover:text-orange-300 font-semibold">
                            {{ $review->item->title }}
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('reviews.update', $review->review_id) }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Rating</label>
                <input type="hidden" name="rating" id="ratingInput" value="{{ $review->rating }}">
                <div id="starContainer" class="flex items-center gap-1 text-2xl cursor-pointer">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-star star-icon {{ $review->rating >= $i ? 'fa-solid text-yellow-400' : 'fa-regular text-gray-600' }}"
                           data-value="{{ $i }}"></i>
                    @endfor
                    <span class="ml-3 text-sm text-gray-400" id="ratingLabel">
                        {{ $review->rating }}/5
                    </span>
                </div>
                @error('rating')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="comment" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Comment</label>
                <textarea name="comment" id="comment" rows="5" required
                          class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2 resize-none">{{ $review->comment }}</textarea>
                @error('comment')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-gray-800">
                <a href="{{ route('items.show', $review->item_id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 text-sm font-semibold transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-colors">
                    <i class="fa-solid fa-check mr-2"></i>
                    Update Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('#starContainer .star-icon');
        const ratingInput = document.getElementById('ratingInput');
        const ratingLabel = document.getElementById('ratingLabel');

        function setRating(value) {
            ratingInput.value = value;
            stars.forEach(star => {
                const v = parseInt(star.getAttribute('data-value'), 10);
                if (v <= value) {
                    star.classList.remove('fa-regular', 'text-gray-600');
                    star.classList.add('fa-solid', 'text-yellow-400');
                } else {
                    star.classList.remove('fa-solid', 'text-yellow-400');
                    star.classList.add('fa-regular', 'text-gray-600');
                }
            });
            if (ratingLabel) {
                ratingLabel.textContent = value + '/5';
            }
        }

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = parseInt(this.getAttribute('data-value'), 10);
                if (!isNaN(value)) {
                    setRating(value);
                }
            });
        });

        const initial = parseInt(ratingInput.value || '0', 10);
        if (!isNaN(initial) && initial >= 1 && initial <= 5) {
            setRating(initial);
        }
    });
</script>
@endpush
