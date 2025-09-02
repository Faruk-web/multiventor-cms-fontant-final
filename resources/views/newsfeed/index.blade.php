

@extends('front.layout.layout')
@section('content')
  <style>
  .post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
  }
  .post-header img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
  }
  .username {
    font-weight: bold;
  }
  .header-actions a,
  .header-actions button {
    margin-left: 10px;
    font-size: 16px;
    color: #333;
    cursor: pointer;
  }
  .header-actions i:hover {
    color: #007bff;
  }

    .post-img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      position: relative;
    }
    .overlay-buttons {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 10px;
    }
    .overlay-buttons button {
      background: rgba(255,255,255,0.8);
      border: none;
      padding: 5px 8px;
      border-radius: 5px;
      cursor: pointer;
    }
    .post-content {
      padding: 15px;
    }
    .post-actions {
    display: flex;
    justify-content: space-between;
    padding: 14px 15px 1px;
    font-size:15px;
    color: #555;
    }

    .post-actions .action-btn {
    cursor: pointer;
    user-select: none;
    display: flex;
    align-items: center;
    gap: 10px;
    }

    .post-actions i {
    font-size:18px;
    }
    .item-img-wrapper-link:before{
      background-color: none;
    }
/* ========== comment repply css======================= */
.newsfeed-box {
    background: #f9f9f9;
    border-radius: 10px;
}
.comment-section {
    background: #fff;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
.comment-box, .reply-box {
    background: #f1f1f1;
    border-radius: 5px;
    padding: 6px 10px;
    margin-top: 5px;
}
/* ===================shear link=============== */
.share-btn {
  cursor: pointer;
  color: #4267B2;
}
  </style>

<div class="container">
  @foreach($newsfeed as $item)
    <div class="post" data-viewed="false">
      {{-- Header --}}
      <div class="post-header d-flex justify-between align-center">
        <div class="d-flex align-center">
          <img src="{{ $item->user->profile_photo  
                          ? asset('uploads/profile/' . $item->user->profile_photo ) 
                          : 'https://i.ibb.co/ZYW3VTp/brown-brim.png' }}"  alt="avatar">
          <a href="{{ route('customer.profileshow', $item->user_id) }}">
          <div class="username">{{ $item->name }}</div>
             </a>
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
      <div class="newsfeed-box" style="margin-top: 15px; padding: 15px;">
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
@endsection
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
