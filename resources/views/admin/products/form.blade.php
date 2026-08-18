@extends('layouts.admin')

@section('content')
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Base Price</label>
                        <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price ?? 0) }}">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Short Description</label>
                        <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description ?? '') }}">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
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

                <div class="col-12">
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Published / Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Product Variations</h5>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-variation">Add Variation</button>
            </div>

            <div id="variations-wrapper">
                @php $variations = old('variations', $product->variations ?? []); @endphp
                @if($variations->isNotEmpty() ?? false)
                    @foreach($variations as $i => $variation)
                        <div class="row variation-item g-3 mb-3 align-items-end">
                            <div class="col-md-3">
                                <label>Variation Name</label>
                                <input type="text" name="variations[{{ $i }}][name]" class="form-control" value="{{ $variation['name'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label>Size</label>
                                <input type="text" name="variations[{{ $i }}][size]" class="form-control" value="{{ $variation['size'] ?? '' }}" placeholder="M / L">
                            </div>
                            <div class="col-md-2">
                                <label>Color</label>
                                <input type="text" name="variations[{{ $i }}][color]" class="form-control" value="{{ $variation['color'] ?? '' }}" placeholder="Black">
                            </div>
                            <div class="col-md-2">
                                <label>Price</label>
                                <input type="number" step="0.01" name="variations[{{ $i }}][price]" class="form-control" value="{{ $variation['price'] ?? 0 }}">
                            </div>
                            <div class="col-md-2">
                                <label>Default</label>
                                <div class="mt-2">
                                    <input type="checkbox" class="default-variation-toggle" name="variations[{{ $i }}][is_default]" value="1" {{ !empty($variation['is_default']) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row variation-item g-3 mb-3 align-items-end">
                        <div class="col-md-3">
                            <label>Variation Name</label>
                            <input type="text" name="variations[0][name]" class="form-control" value="Default" placeholder="Full Set">
                        </div>
                        <div class="col-md-2">
                            <label>Size</label>
                            <input type="text" name="variations[0][size]" class="form-control" value="M" placeholder="M / L">
                        </div>
                        <div class="col-md-2">
                            <label>Color</label>
                            <input type="text" name="variations[0][color]" class="form-control" value="Black" placeholder="Black">
                        </div>
                        <div class="col-md-2">
                            <label>Price</label>
                            <input type="number" step="0.01" name="variations[0][price]" class="form-control" value="0">
                        </div>
                        <div class="col-md-2">
                            <label>Default</label>
                            <div class="mt-2">
                                <input type="checkbox" class="default-variation-toggle" name="variations[0][is_default]" value="1" checked>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<script>
    let variationIndex = {{ count(old('variations', $product->variations ?? [])) ?: 1 }};

    const enforceSingleDefault = () => {
        const toggles = document.querySelectorAll('.default-variation-toggle');
        toggles.forEach((toggle) => {
            toggle.addEventListener('change', function () {
                if (!this.checked) {
                    return;
                }

                toggles.forEach((other) => {
                    if (other !== this) {
                        other.checked = false;
                    }
                });
            });
        });
    };

    enforceSingleDefault();

    document.getElementById('add-variation')?.addEventListener('click', function () {
        const wrapper = document.getElementById('variations-wrapper');
        const row = document.createElement('div');
        row.className = 'row variation-item g-3 mb-3 align-items-end';
        row.innerHTML = `
            <div class="col-md-3">
                <label>Variation Name</label>
                <input type="text" name="variations[${variationIndex}][name]" class="form-control" placeholder="Full Set">
            </div>
            <div class="col-md-2">
                <label>Size</label>
                <input type="text" name="variations[${variationIndex}][size]" class="form-control" placeholder="M / L">
            </div>
            <div class="col-md-2">
                <label>Color</label>
                <input type="text" name="variations[${variationIndex}][color]" class="form-control" placeholder="Black">
            </div>
            <div class="col-md-2">
                <label>Price</label>
                <input type="number" step="0.01" name="variations[${variationIndex}][price]" class="form-control" value="0">
            </div>
            <div class="col-md-2">
                <label>Default</label>
                <div class="mt-2">
                    <input type="checkbox" class="default-variation-toggle" name="variations[${variationIndex}][is_default]" value="1">
                </div>
            </div>
        `;
        wrapper.appendChild(row);
        enforceSingleDefault();
        variationIndex++;
    });
</script>
@endsection
