@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ isset($product->id) ? 'Edit Product' : 'Create Product' }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($product->id) ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if(isset($product->id))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Base Price</label>
                    <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price ?? 0) }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description ?? '') }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label>Featured Image Path</label>
                    <input type="text" name="featured_image" class="form-control" value="{{ old('featured_image', $product->featured_image ?? '') }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
            </div>

            <hr>
            <h5>Variations</h5>
            <div id="variations-wrapper">
                @php $variations = old('variations', $product->variations ?? []); @endphp
                @if($variations->isNotEmpty() ?? false)
                    @foreach($variations as $i => $variation)
                        <div class="row variation-item mb-3">
                            <div class="col-md-4 form-group">
                                <label>Name</label>
                                <input type="text" name="variations[{{ $i }}][name]" class="form-control" value="{{ $variation['name'] ?? '' }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" name="variations[{{ $i }}][price]" class="form-control" value="{{ $variation['price'] ?? 0 }}" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Default</label>
                                <div class="mt-2">
                                    <input type="checkbox" name="variations[{{ $i }}][is_default]" value="1" {{ !empty($variation['is_default']) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row variation-item mb-3">
                        <div class="col-md-4 form-group">
                            <label>Name</label>
                            <input type="text" name="variations[0][name]" class="form-control" value="" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="variations[0][price]" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Default</label>
                            <div class="mt-2">
                                <input type="checkbox" name="variations[0][is_default]" value="1" checked>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-variation">Add Variation</button>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</div>

<script>
    let variationIndex = {{ count(old('variations', $product->variations ?? [])) ?: 1 }};

    document.getElementById('add-variation')?.addEventListener('click', function () {
        const wrapper = document.getElementById('variations-wrapper');
        const row = document.createElement('div');
        row.className = 'row variation-item mb-3';
        row.innerHTML = `
            <div class="col-md-4 form-group">
                <label>Name</label>
                <input type="text" name="variations[${variationIndex}][name]" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="variations[${variationIndex}][price]" class="form-control" value="0" required>
            </div>
            <div class="col-md-3 form-group">
                <label>Default</label>
                <div class="mt-2">
                    <input type="checkbox" name="variations[${variationIndex}][is_default]" value="1">
                </div>
            </div>
        `;
        wrapper.appendChild(row);
        variationIndex++;
    });
</script>
@endsection
