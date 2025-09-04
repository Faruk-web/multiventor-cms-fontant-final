{{-- This page is accessed from My Account tab in the dropdown menu in the header (in front/layout/header.blade.php). Check userAccount() method in Front/UserController.php --}} 
@extends('front.layout.layout')
@section('content')
<style>
  /* Desktop view (default) */
.fan-page {
    margin-left: 782px;
}
 .profile-container:hover .upload-icon {
        background: #145db2; /* hover color */
    }
/* Mobile view (max-width 991px) */
@media (max-width: 991px) {
    .fan-page {
        margin-left: 0;
    }
}

</style>
<section id="fan-posts" class="my-3">
    <div class="container fan-page">
        <div class="row" style="text-align: center;">
          <div class="col-lg-2">
          <!-- Profile Image -->
            <div class="profile-container" style="position: relative; display: inline-block;">
              {{-- Profile Photo --}}
              <img src="{{ $user->profile_photo 
                          ? asset('uploads/profile/' . $user->profile_photo) 
                          : 'https://i.ibb.co/ZYW3VTp/brown-brim.png' }}" 
                  alt="Profile Picture" 
                  class="profile-pic" 
                  style="width:150px; height:150px; border-radius:50%; object-fit:cover;">

              {{-- Update Icon (overlay) --}}
              <form action="{{ route('profile.update.photo', $user->id) }}" method="POST" enctype="multipart/form-data" id="photoForm">
                  @csrf
                  <label for="profile_photo" class="upload-icon"
                        style="position: absolute; bottom: 10px; right: 10px; 
                                background: #1877f2; color: #fff; border-radius: 50%; 
                                padding: 8px; cursor: pointer; display:flex; align-items:center; justify-content:center;">
                      <i class="fas fa-camera"></i> {{-- FontAwesome icon --}}
                  </label>
                  <input type="file" name="profile_photo" id="profile_photo" style="display: none;" onchange="document.getElementById('photoForm').submit();">
              </form>
          </div>

          </div>
          <div class="col-lg-2">
          <!-- Info Section -->
            <div class="info">
              <p>Shopping Rating</p>
              <p class="rating">6.0</p>
              <p>From Bangladesh</p>
            </div>
          </div>
          <div class="col-lg-1">
            <nav>
                <ul class="secondary-nav g-nav">
                    <li>
                        <a>
                            {{-- If the user is authenticated/logged in, show 'My Account', if not, show 'Login/Register' --}} 
                            @if (\Illuminate\Support\Facades\Auth::check()) {{-- Determining If The Current User Is Authenticated: https://laravel.com/docs/9.x/authentication#determining-if-the-current-user-is-authenticated --}}
                                My Account
                            @else
                                Login/Register
                            @endif

                            <i class="fas fa-chevron-down u-s-m-l-9"></i>
                        </a>
                        <ul class="g-dropdown" style="width:200px">
                            <li>
                                <a href="{{ url('cart') }}">
                                <i class="fas fa-cog u-s-m-r-9"></i>
                                My Cart</a>
                            </li>
                            <li>
                                <a href="{{ url('checkout') }}">
                                <i class="far fa-check-circle u-s-m-r-9"></i>
                                Checkout</a>
                            </li>
                            {{-- If the user is authenticated/logged in, show 'My Account' and 'Logout', if not, show 'Customer Login' and 'Vendor Login' --}} 
                            @if (\Illuminate\Support\Facades\Auth::check()) {{-- Determining If The Current User Is Authenticated: https://laravel.com/docs/9.x/authentication#determining-if-the-current-user-is-authenticated --}}
                                <li>
                                    <a href="{{ url('user/account') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        My Account
                                    </a>
                                </li>
                                    <li>
                                    <a href="{{ url('customer/Wardrobe') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Wardrobe
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ url('user/orders') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        My Orders
                                    </a>
                                </li>
                                    <li>
                                    <a href="{{ url('post/newsfeed') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Post NewsFeed
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('user/logout') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Logout
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ url('user/login-register') }}"> 
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Customer Login
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('vendor/login-register') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Vendor Login
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </nav>
          </div>
        </div>
      </div>
  </section>
  @php
    if(isset($user->id)) {
        $looks = \App\Models\Newsfeed::with('likes','comments.user')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();
    } else {
        $looks = \App\Models\Newsfeed::with('likes','comments.user')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
    }
@endphp
 <!-- Tabs -->
  <div class="tabs">
    <a href="javascript:void(0)" class="active" onclick="openTab(event, 'looks')">Looks</a>
    <a href="javascript:void(0)" onclick="openTab(event, 'wardoop')">Wardrobe</a>
    <a href="javascript:void(0)" onclick="openTab(event, 'fan')">Fan</a>
    <!-- <a href="javascript:void(0)" onclick="openTab(event, 'following')">Following ({{ $user->followers->count() }})</a> -->
    <a href="javascript:void(0)" onclick="toggleTab(event, 'following')">Following {{ $user->followers->count() }}</a>
  </div>

  <!-- Tab Contents -->
  <div id="looks" class="tab-content-profile active">
    <div class="container">
      @foreach($looks as $item)
        <div class="post" data-viewed="false">
          
          {{-- Header --}}
          <div class="post-header d-flex justify-between align-center">
            <div class="d-flex align-center">
              <img src="{{ $user->profile_photo 
                          ? asset('uploads/profile/' . $user->profile_photo) 
                          : 'https://i.ibb.co/ZYW3VTp/brown-brim.png' }}"  alt="avatar">
              <div class="username">{{ $item->name }}</div>
            </div>

            @if(auth()->id() === $item->user_id)
              <div class="header-actions" style="position: relative; display: inline-block;">
                  {{-- Three dots icon --}}
                  <span class="dots-icon" style="cursor: pointer; font-size: 18px;" onclick="toggleActions({{ $item->id }})">
                      &#8230; {{-- HTML entity for ... --}}
                  </span>
                  {{-- Hidden edit/delete icons --}}
                  <div id="actions-{{ $item->id }}" style="display: none; position: absolute; top: 20px; right: 0; background: #fff; border: 1px solid #ccc; padding: 5px; border-radius: 5px; z-index: 100;">
                      <a href="{{ route('newsfeed.edit', $item->id) }}" title="Edit" >
                          <i class="fas fa-edit" style="margin-left: 6px;"></i>
                      </a>
                      <form action="{{ route('newsfeed.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">
                              <i class="fas fa-trash-alt"></i>
                          </button>
                      </form>
                  </div>
              </div>
            @endif
          </div>

          {{-- Media --}}
          <div style="position:relative">
            @php $ext = pathinfo($item->media_path, PATHINFO_EXTENSION); @endphp
            @if(in_array($ext, ['jpg','jpeg','png','gif']))
              <img class="post-img" src="{{ asset($item->media_path) }}" alt="post image">
            @elseif(in_array($ext, ['mp4','mov','avi']))
              <video class="post-img" controls>
                <source src="{{ asset($item->media_path) }}" type="video/{{ $ext }}">
              </video>
            @endif

            <div class="overlay-buttons">
              @foreach(json_decode($item->tags) as $tagId)
                @if(isset($allProducts[$tagId]))
                  <button title="Tagged Product">
                    <a class="item-img-wrapper-link" href="{{ url('product/' . $tagId) }}">{{ $allProducts[$tagId] }}</a>
                  </button>
                @endif
              @endforeach
            </div>
          </div>

          {{-- Post Content --}}
          <div class="post-actions">
            <span>{{ $item->review }}</span>
          </div>

          {{-- Like/Love/Share --}}
          <div class="post-actions">
            <div class="like-btn" data-id="{{ $item->id }}">
              <i class="{{ $item->isLikedBy(auth()->id()) ? 'fas fa-thumbs-up text-primary' : 'far fa-thumbs-up' }}"></i>
              <span class="like-count">{{ $item->likes->count() }}</span>
            </div>
            <div class="love-btn" data-id="{{ $item->id }}">
              <i class="{{ $item->isLovedBy(auth()->id()) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
              <span class="love-count">{{ $item->loves->count() }}</span>
            </div>
            <div class="postid" data-url="{{ route('newsfeed.edit', $item->id) }}" data-title="Post Title">
              <div class="action-btn share-btn" onclick="shareToFacebook(this)">
                <i class="ion ion-md-share"></i> শেয়ার
              </div>
            </div>
          </div>

          {{-- 🔁 Comment Section For This Post --}}
          <div class="newsfeed-box" style="margin-top: 15px; border: 1px solid #ccc; padding: 15px;">
            {{-- 🟡 Toggle Button --}}
            <div class="action-btn comment-btn" onclick="toggleCommentBox({{ $item->id }})" style="cursor: pointer; color: #007bff;">
              <i class="ion ion-md-chatbubbles"></i>
              <span class="comment-count">{{ $item->comments->count() }}</span> Comments
            </div>

            {{-- 🟢 Comment Box --}}
            <div class="comment-section" id="comment-box-{{ $item->id }}" style="display: none; margin-top: 15px;">
              {{-- Comment Form --}}
              @auth
              <form action="{{ route('comment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="newsfeed_id" value="{{ $item->id }}">
                <textarea name="comment" placeholder="Write a comment..." style="width: 100%; padding: 8px;"></textarea>
                <button type="submit">Comment</button>
              </form>
              @endauth
              @guest
                <form action="{{ route('comment.store') }}" method="POST" style="margin-bottom: 10px;">
                    @csrf
                    <input type="hidden" name="newsfeed_id" value="{{ $item->id }}">
                    <textarea name="comment" placeholder="Write a comment..." style="width: 100%; padding: 8px;"></textarea>
                    <button type="submit">Comment</button>
                </form>
                @endguest
              {{-- Comments --}}
              @foreach($item->comments->where('parent_id', null) as $comment)
              <div class="comment-box" style="margin-left: 10px; padding: 5px 0;">
                <strong>{{ $comment->user->name ?? 'General Customer' }}</strong>: {{ $comment->comment }}

                {{-- Delete --}}
                @auth
                @if(auth()->id() == $comment->user_id)
                  <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button style="border: none;" type="submit" onclick="return confirm('Are you sure to delete this comment?')">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                @endif
                @endauth

                {{-- Replies --}}
                @foreach($comment->replies as $reply)
                <div class="reply-box" style="margin-left: 20px;">
                  <strong>{{ $reply->user->name }}</strong>: {{ $reply->comment }}
                </div>
                @endforeach

                {{-- Reply Form --}}
                @auth
                <form action="{{ route('comment.reply') }}" method="POST" style="margin-top: 5px;">
                  @csrf
                  <input type="hidden" name="newsfeed_id" value="{{ $item->id }}">
                  <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                  <input type="text" name="comment" placeholder="Reply..." style="width: 90%; padding: 4px;">
                  <button type="submit">Reply</button>
                </form>
                @endauth
              </div>
              @endforeach
            </div>
          </div>

        </div>
      @endforeach
   </div>
</div>

  <div id="wardoop" class="tab-content-profile">
    <div class="container">
     <div class="sec-maker-header text-center">
        <h3 class="sec-maker-h3">Buying Products</h3>
         </div>
            <div class="row">
            @foreach($orders as $order)
                @foreach($order->orders_products as $orderProduct)
                    @php
                        $product = $orderProduct->product;
                        $imagePath = 'front/images/product_images/small/' . ($product->product_image ?? 'no-image.png');
                        $productUrl = url('product/' . $product->id);
                        $discountPrice = \App\Models\Product::getDiscountPrice($product->id);
                    @endphp
                    <!-- <div class="col-4 mb-4"> -->
                    <div class="col-md-4 mb-4">
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
</div>
  <div id="fan" class="tab-content-profile">
    <div class="container">
       @forelse($user->followers as $follower)
            <div class="post mb-2 p-2 border rounded">
              <a href="{{ route('customer.profileshow', $follower->id) }}">
                <div class="d-flex align-items-center">
                    <img src="https://randomuser.me/api/portraits/men/10.jpg" 
                         alt="{{ $follower->name }}" 
                         width="40" height="40" class="rounded-circle me-2">
                    <span style="margin-left:12px">{{ $follower->name }}</span>
                </div>
               </a>
            </div>
        @empty
            <div class="post p-2">
                এখনও কোনো ফলোয়ার নেই 🙂
            </div>
        @endforelse
    </div>
  </div>

  <div id="following" class="tab-content-profile">
    <div class="container text-center">

    @auth
        @if(auth()->user()->following->contains($user->id))
            <form action="{{ route('unfollow', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Unfollow</button>
            </form>
        @else
            <form action="{{ route('follow', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Follow</button>
            </form>
        @endif
    @else
        <p>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                Login to Follow
            </a>
        </p>
    @endauth

</div>

 </div>
  <!-- Script -->
  <script>
    function openTab(evt, tabId) {
      // সব tab-content-profile hide করো
      document.querySelectorAll('.tab-content-profile').forEach(tab => {
        tab.classList.remove('active');
      });

      // সব ট্যাব বাটন থেকে active সরাও
      document.querySelectorAll('.tabs a').forEach(link => {
        link.classList.remove('active');
      });

      // যেটা ক্লিক করা হলো সেটা দেখাও
      document.getElementById(tabId).classList.add('active');
      evt.currentTarget.classList.add('active');
    }
  </script>


<script>
function toggleTab(evt, tabId) {
    var tab = document.getElementById(tabId);
    var link = evt.currentTarget;

    // যদি already active থাকে, close করো
    if(tab.classList.contains('active')) {
        tab.classList.remove('active');
        link.classList.remove('active');
    } else {
        // সব tab-content-profile hide করো
        document.querySelectorAll('.tab-content-profile').forEach(t => t.classList.remove('active'));
        // সব ট্যাব বাটন থেকে active সরাও
        document.querySelectorAll('.tabs a').forEach(l => l.classList.remove('active'));

        // current tab open করো
        tab.classList.add('active');
        link.classList.add('active');
    }
}
</script>

<!-- ====================== newsfeed page er script code============================ -->
 <meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // যখন ইউজার like button এ ক্লিক করবে তখন AJAX call হবে
    $('.like-btn').click(function() {
        var button = $(this);
        var newsfeedId = button.data('id');  // button থেকে newsfeed id নিন

        $.ajax({
            url: '/newsfeed/' + newsfeedId + '/like',
            method: 'POST',
            success: function(response) {
                if (response.liked) {
                    button.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                } else {
                    button.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                }
                button.find('.like-count').text(response.like_count);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Like করতে সমস্যা হচ্ছে। Console error দেখুন।');
            }
        });
    });
});

/// love
$('.love-btn').click(function () {
    var button = $(this);
    var newsfeedId = button.data('id');
    var icon = button.find('i');

    $.ajax({
        url: '/newsfeed/' + newsfeedId + '/love',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            // Icon toggle logic
            if (response.loved) {
                  icon.removeClass('far fa-heart').addClass('fas fa-heart text-danger');
              } else {
                  icon.removeClass('fas fa-heart text-danger').addClass('far fa-heart');
              }

            // Count update
            button.find('.love-count').text(response.love_count);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
});
// =================coment repply=============
</script>
@section('scripts')
<script>
    function toggleCommentBox(postId) {
      const box = document.getElementById('comment-box-' + postId);
      if (box.style.display === 'none') {
        box.style.display = 'block';
      } else {
        box.style.display = 'none';
      }
    }
</script>
<!-- ========== shear link================ -->
<script>
function shareToFacebook(button) {
  const postDiv = button.closest('.postid');
  const url = postDiv.getAttribute('data-url');
  const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
  window.open(shareUrl, '_blank', 'width=600,height=400');
}
</script>

@endsection

@endsection
