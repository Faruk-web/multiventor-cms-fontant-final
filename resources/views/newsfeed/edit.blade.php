{{-- This page is accessed from My Account tab in the dropdown menu in the header (in front/layout/header.blade.php). Check userAccount() method in Front/UserController.php --}} 
@extends('front.layout.layout')

@section('content')
    <!-- Page Introduction Wrapper -->
    <div class="page-style-a">
        <div class="container">
            <div class="page-intro">
                <h2>News Feed Post</h2>
                <ul class="bread-crumb">
                    <li class="has-separator">
                        <i class="ion ion-md-home"></i>
                        <a href="index.html">Home</a>
                    </li>
                    <li class="is-marked">
                        <a href="account.html">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Page Introduction Wrapper /- -->
      {{-- Displaying Success Message --}}
            @if (Session::has('success_message')) <!-- Check userRegister() method in Front/UserController.php -->
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success:</strong> {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- Displaying Error Messages --}}
            @if (Session::has('error_message')) <!-- Check userRegister() method in Front/UserController.php -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{-- Displaying Error Messages --}}
            @if ($errors->any()) <!-- Check userRegister() method in Front/UserController.php -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> @php echo implode('', $errors->all('<div>:message</div>')); @endphp
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
<style>
.product-review-form {
  background: #ebf1ef;
  padding: 20px 25px;
  margin: 30px auto;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
  max-width: 800px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.product-review-form form > * {
  margin-bottom: 15px;
  width: 100%;
  box-sizing: border-box;
}

.product-review-form input[type="text"],
.product-review-form select,
.product-review-form textarea,
.product-review-form input[type="file"] {
  font-size: 16px;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.product-review-form textarea {
  resize: vertical;
  min-height: 120px;
}

.product-review-form button {
  width: 100%;
  background: #1877f2;
  color: white;
  padding: 12px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.product-review-form button:hover {
  background: #145dbf;
}
#productTags {
  width: 100%;
  min-height: 120px;
  padding: 10px;
  border: 1.5px solid #1877f2;
  border-radius: 8px;
  background-color: #fff;
  font-size: 16px;
  color: #333;
  box-sizing: border-box;
  outline: none;
  cursor: pointer;
}

#productTags option {
  padding: 8px;
  font-size: 15px;
}

#productTags:focus {
  border-color: #0f58d0;
  box-shadow: 0 0 8px rgba(15, 88, 208, 0.5);
}


/* Choices.js styles */
.choices__inner {
  min-height: 20px;
  border-radius: 8px;
  border: 1px solid #ccc;
  padding: 5px 10px;
  font-size: 16px;
}

.choices__list--multiple .choices__item {
  background-color: #1877f2;
  color: white;
  border-radius: 6px;
  padding: 3px 8px;
  margin: 3px 4px 3px 0;
  font-size: 14px;
}

.choices__list--multiple {
  padding: 5px 0;
}
.choices__list--dropdown {
  max-height: 200px;
  overflow-y: auto;
}

</style>

<div class="product-review-form">
  <h2>Update Your Review</h2>

  <form action="{{ route('newsfeed.update', $review->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 30px;" id="reviewForm">
    @csrf
    @method('PUT')
     <input type="hidden" name="user_id" value="{{ $review->user_id }}" readonly />
    {{-- User Name --}}
    <input type="text" name="name" value="{{ $review->name }}" readonly />

    {{-- Product Name --}}
      <select name="product_name" required>
            <option value="{{ $review->product_name }}" {{ $review->product_name == $review->product_name ? 'selected' : '' }}>
                {{ $review->product_name }} post
            </option>
            <option value="Sport">Sport post</option>
            <option value="Fustion">Fustion post</option>
            <option value="Fan">Fan post</option>

    </select>
    {{-- Shop Selection --}}
    <select name="vendor_id" required>
        <option value="">Select Shop</option>
        @foreach($shopname as $shop)
            <option value="{{ $shop->vendor_id }}" {{ $review->vendor_id == $shop->vendor_id ? 'selected' : '' }}>
                {{ $shop->shop_name }}
            </option>
        @endforeach
    </select>

    {{-- Product Tags --}}
    <select id="productTags" name="tags[]" multiple>
        @foreach($products as $product)
            <option value="{{ $product->id }}" 
              {{ in_array($product->id, json_decode($review->tags, true) ?? []) ? 'selected' : '' }}>
              {{ $product->product_name }}
            </option>
        @endforeach
    </select>

    {{-- Review Text --}}
    <textarea name="review" placeholder="Write your review here..." required>{{ $review->review }}</textarea>

    {{-- Show Existing Media --}}
    @if($review->media_path)
      @php $ext = pathinfo($review->media_path, PATHINFO_EXTENSION); @endphp
      <div style="margin-top:10px;">
        @if(in_array($ext, ['jpg','jpeg','png','gif']))
          <img src="{{ asset($review->media_path) }}" width="150">
        @elseif(in_array($ext, ['mp4','mov','avi']))
          <video width="200" controls>
            <source src="{{ asset($review->media_path) }}" type="video/{{ $ext }}">
          </video>
        @endif
      </div>
    @endif

    {{-- File Upload --}}
    <label for="mediaUpload">Change Image or Video:</label>
    <input type="file" name="media" id="mediaUpload" accept="image/*,video/*" />

    {{-- Submit --}}
    <button type="submit">Update Review</button>
  </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
  

  const tagInput = new Choices('#productTags', {
  removeItemButton: true,
  placeholderValue: 'Select related products',
  duplicateItemsAllowed: false,
  searchEnabled: true,
  searchFloor: 1,
  itemSelectText: '',
  noResultsText: 'No matching products',
});

</script>

@endsection