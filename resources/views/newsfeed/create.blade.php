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
  <h2>Post a Product Review</h2>
  <form style="margin-top: 30px;" id="reviewForm">
    
    <input type="text" placeholder="Product Name" required />

    <select required>
      <option value="">Select Shop</option>
      <option value="daraz">Daraz</option>
      <option value="evaly">Evaly</option>
      <option value="rokomari">Rokomari</option>
      <option value="othoba">Othoba</option>
    </select>

    <select id="productTags" name="tags[]" multiple>
      @foreach($products as $product)
        <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
      @endforeach
    </select>

    <textarea placeholder="Write your review here..." required></textarea>

    <label for="mediaUpload">Upload Product Image or Video:</label>
    <input type="file" id="mediaUpload" accept="image/*,video/*" />

    <button type="submit">Post Review</button>
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