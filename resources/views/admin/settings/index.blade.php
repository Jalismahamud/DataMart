@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Site Settings</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" value="{{ $settings['address'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Facebook</label>
                    <input type="url" name="facebook" class="form-control" value="{{ $settings['facebook'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Instagram</label>
                    <input type="url" name="instagram" class="form-control" value="{{ $settings['instagram'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Delivery Inside Dhaka</label>
                    <input type="number" name="delivery_inside_dhaka" class="form-control" value="{{ $settings['delivery_inside_dhaka'] ?? 70 }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Delivery Outside Dhaka</label>
                    <input type="number" name="delivery_outside_dhaka" class="form-control" value="{{ $settings['delivery_outside_dhaka'] ?? 120 }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Hero Title</label>
                    <input type="text" name="hero_title" class="form-control" value="{{ $settings['hero_title'] ?? '' }}">
                </div>
                <div class="col-md-12 form-group">
                    <label>Hero Subtitle</label>
                    <textarea name="hero_subtitle" class="form-control" rows="3">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label>Hero Image Path</label>
                    <input type="text" name="hero_image" class="form-control" value="{{ $settings['hero_image'] ?? '' }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
        </form>
    </div>
</div>
@endsection
