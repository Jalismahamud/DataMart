@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Reviews</h3>
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">Add Review</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr>
                        <td>
                            <img src="{{  asset('images/reviews/' . $review->image) }}" alt="Product Review" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        </td>
                        <td>
                            <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
