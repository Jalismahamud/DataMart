@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ isset($review->id) ? 'Edit Review' : 'Create Review' }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($review->id) ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}">
            @csrf
            @if(isset($review->id))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $review->name ?? '') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Rating</label>
                    <input type="number" name="rating" min="1" max="5" class="form-control" value="{{ old('rating', $review->rating ?? 5) }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Text</label>
                    <textarea name="text" class="form-control" rows="4" required>{{ old('text', $review->text ?? '') }}</textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label>Image Path</label>
                    <input type="text" name="image" class="form-control" value="{{ old('image', $review->image ?? '') }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $review->is_active ?? true) ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Review</button>
        </form>
    </div>
</div>
@endsection
