@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ isset($review->id) ? 'Edit Review' : 'Create Review' }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($review->id) ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($review->id))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Review Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted d-block mt-2">Upload an image for this review (JPG, PNG, WebP, max 2MB)</small>
                    
                    @if(isset($review->id) && $review->image)
                        <div class="mt-3">
                            <p class="text-muted mb-2">Current Image:</p>
                            <img src="{{ asset('images/reviews/' . $review->image) }}" alt="Review" style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd; padding: 5px;">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Review</button>
        </form>
    </div>
</div>
@endsection
