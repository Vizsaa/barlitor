@extends('layouts.app')
@section('title', 'Edit Review - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-pen"></i> Edit Review</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('reviews.update', $review->review_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-select" required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" required>{{ $review->comment }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('items.show', $review->item_id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
