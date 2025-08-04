

{{-- This page is rendered by contact() method inside Front/CmsController.php --}}
@extends('front.layout.layout')


@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>Wardrobe Products</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="contact.html">Wardrobe</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
    <!-- Contact-Page -->
    <div class="page-contact u-s-p-t-80">
        <div class="container">
            <div class="row">
    @foreach($orders as $order)
      

        @foreach($order->orders_products as $orderProduct)
            @php
                $product = $orderProduct->product;
                $imagePath = 'front/images/product_images/small/' . ($product->product_image ?? 'no-image.png');
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
                        <p class="card-text">Order Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }} |</p>
                        <p class="card-text">Customer: {{ $order->user->name ?? 'Unknown' }}</p>
                        <div class="mb-2">
                            @if($discountPrice > 0)
                                <span class="text-success font-weight-bold">Rs. {{ $discountPrice }}</span>
                                <span class="text-muted text-decoration-line-through">Rs. {{ $product->product_price }}</span>
                            @else
                                <span class="text-dark font-weight-bold">Rs. {{ $product->product_price }}</span>
                            @endif
                        </div>
                        <a href="{{ $productUrl }}" class="btn btn-primary btn-sm">View Product</a>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>


        </div>
        <div class="u-s-p-t-80">
            <div id="map"></div>
        </div>
    </div>
    <!-- Contact-Page /- -->
@endsection