@extends('layouts.admin')

@section('content')
<!-- Include jQuery & Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ isset($product->id) ? 'Edit Product' : 'Create Product' }}</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($product->id) ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($product->id))
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                    </div>
                </div>
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Base Price</label>
                        <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Regular Price</label>
                        <input type="number" step="0.01" name="regular_price" class="form-control" value="{{ old('regular_price', $product->regular_price ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Discount Price</label>
                        <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price ?? '') }}">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="summernote" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Product Images (multiple)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted d-block mt-2">Upload multiple product images. First image becomes the main cover.</small>
                    </div>

                    @if(isset($product->id) && $product->images->count())
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @foreach($product->images as $image)
                                <div class="position-relative border rounded overflow-hidden" style="width: 100px; height: 100px;">
                                    <img src="{{ asset('images/products/' . $image->image_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="row g-4">
                <!-- Sizes Section -->
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Product Sizes</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-size">Add Size</button>
                    </div>
                    <div id="sizes-wrapper">
                        @php $sizes = old('sizes', isset($product->sizes) ? $product->sizes->toArray() : []); @endphp
                        @if($sizes && count($sizes))
                            @foreach($sizes as $i => $size)
                                <div class="input-group mb-2 size-item">
                                    <input type="text" name="sizes[{{ $i }}][name]" class="form-control" value="{{ $size['name'] ?? '' }}" placeholder="e.g. XL, 42">
                                    <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
                                </div>
                            @endforeach
                        @else
                            <div class="input-group mb-2 size-item">
                                <input type="text" name="sizes[0][name]" class="form-control" placeholder="e.g. XL, 42">
                                <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colors Section -->
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Product Colors</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-color">Add Color</button>
                    </div>
                    <div id="colors-wrapper">
                        @php $colors = old('colors', isset($product->colors) ? $product->colors->toArray() : []); @endphp
                        @if($colors && count($colors))
                            @foreach($colors as $i => $color)
                                <div class="input-group mb-2 color-item">
                                    <input type="text" name="colors[{{ $i }}][name]" class="form-control" value="{{ $color['name'] ?? '' }}" placeholder="e.g. Red, Blue">
                                    <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
                                </div>
                            @endforeach
                        @else
                            <div class="input-group mb-2 color-item">
                                <input type="text" name="colors[0][name]" class="form-control" placeholder="e.g. Red, Blue">
                                <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    let sizeIndex = {{ count(old('sizes', isset($product->sizes) ? $product->sizes->toArray() : [])) ?: 1 }};
    let colorIndex = {{ count(old('colors', isset($product->colors) ? $product->colors->toArray() : [])) ?: 1 }};

    const attachRemoveEvents = () => {
        document.querySelectorAll('.remove-item').forEach((button) => {
            // Remove existing listeners by replacing the element to avoid duplicates
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            newButton.addEventListener('click', function () {
                const row = this.closest('.input-group');
                if (row) {
                    row.remove();
                }
            });
        });
    };

    attachRemoveEvents();

    document.getElementById('add-size')?.addEventListener('click', function () {
        const wrapper = document.getElementById('sizes-wrapper');
        const row = document.createElement('div');
        row.className = 'input-group mb-2 size-item';
        row.innerHTML = `
            <input type="text" name="sizes[${sizeIndex}][name]" class="form-control" placeholder="e.g. XL, 42">
            <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
        `;
        wrapper.appendChild(row);
        attachRemoveEvents();
        sizeIndex++;
    });

    document.getElementById('add-color')?.addEventListener('click', function () {
        const wrapper = document.getElementById('colors-wrapper');
        const row = document.createElement('div');
        row.className = 'input-group mb-2 color-item';
        row.innerHTML = `
            <input type="text" name="colors[${colorIndex}][name]" class="form-control" placeholder="e.g. Red, Blue">
            <button type="button" class="btn btn-outline-danger remove-item">Remove</button>
        `;
        wrapper.appendChild(row);
        attachRemoveEvents();
        colorIndex++;
    });
</script>
@endsection
