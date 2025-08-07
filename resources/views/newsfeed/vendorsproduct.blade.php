
{{-- This page is rendered by contact() method inside Front/CmsController.php --}}
@extends('front.layout.layout')


@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Vendor Products</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="contact.html">{{$vendor->name}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Contact-Page -->
    <div class="page-contact u-s-p-t-80">
        <div class="container">
            <div class="sec-maker-header text-center">
                <h3 class="sec-maker-h3">Shop name: {{ $vendor->businessDetails?->shop_name ?? 'missing' }}</h3>
                <ul class="nav tab-nav-style-1-a justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#men-latest-products">Shop address: {{ $vendor->businessDetails?->shop_address ?? 'missing' }}</a>
                    </li>
                </ul>
            </div>
            <div class="row">
        @forelse($products as $product)
            @php
                $imagePath = $product->product_image
                    ? 'front/images/product_images/small/' . $product->product_image
                    : 'front/images/product_images/small/no-image.png';
                $productUrl = url('product/' . $product->id);
                $discountPrice = \App\Models\Product::getDiscountPrice($product->id);
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <a href="{{ $productUrl }}">
                        <img src="{{ asset($imagePath) }}" class="card-img-top" alt="{{ $product->product_name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->product_name }}</h5>
                        <p class="card-text">{{ $product->product_code }}</p>
                        <div class="mb-2">
                            @if($discountPrice > 0)
                                <span class="text-success font-weight-bold">Rs. {{ number_format($discountPrice, 2) }}</span>
                                <span class="text-muted text-decoration-line-through">Rs. {{ number_format($product->product_price, 2) }}</span>
                            @else
                                <span class="text-dark font-weight-bold">Rs. {{ number_format($product->product_price, 2) }}</span>
                            @endif
                        </div>
                        <a href="{{ $productUrl }}" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No products found for this vendor.</p>
            </div>
        @endforelse
    </div>


        </div>
        <div class="u-s-p-t-80">
            <div id="map"></div>
        </div>
    </div>
    <!-- Contact-Page /- -->
@endsection