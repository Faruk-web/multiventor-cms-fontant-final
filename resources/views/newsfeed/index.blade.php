

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

    .post {
      background: #d7d7d770;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 3px rgba(0,0,0,0.1);
      margin-top: 40px;
      overflow: hidden;
      position: relative;
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
    padding: 0 15px 15px;
    font-size: 20px;
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
    font-size: 30px;
    }

  </style>

<div class="container">
  @foreach($newsfeed as $item)
    <div class="post" data-viewed="false">
      
      {{-- Header --}}
      <<div class="post-header d-flex justify-between align-center">
      <div class="d-flex align-center">
        <img src="https://randomuser.me/api/portraits/men/10.jpg" alt="avatar">
        <div class="username">{{ $item->name }}</div>
      </div>

      {{-- Right Side Buttons --}}
      @if(auth()->id() === $item->user_id)
        <div class="header-actions">
          <a href="{{ route('newsfeed.edit', $item->id) }}" title="Edit">
            <i class="fas fa-edit"></i>
          </a>
          <form action="{{ route('newsfeed.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </div>
      @endif
    </div>


      {{-- Media --}}
      <div style="position:relative">
        @php
          $ext = pathinfo($item->media_path, PATHINFO_EXTENSION);
        @endphp

        @if(in_array($ext, ['jpg','jpeg','png','gif']))
          <img class="post-img" src="{{ asset($item->media_path) }}" alt="post image">
        @elseif(in_array($ext, ['mp4','mov','avi']))
          <video class="post-img" controls>
            <source src="{{ asset($item->media_path) }}" type="video/{{ $ext }}">
          </video>
        @endif

        {{-- 🔁 Tag Loop Inside overlay-buttons --}}
        <div class="overlay-buttons">
          @foreach(json_decode($item->tags) as $tagId)
            @if(isset($allProducts[$tagId]))
              <button title="Tagged Product"><a class="item-img-wrapper-link" href="{{ url('product/' . $tagId) }}">{{ $allProducts[$tagId] }}</a></button>
            @endif
          @endforeach
        </div>
      </div>
      {{-- Post Content --}}
      <div class="post-actions">
        <p>{{ $item->review }}</p>

          
      </div>
      <div class="post-actions">
          <button class="like-btn" data-id="{{ $item->id }}">
              <i class="{{ $item->isLikedBy(auth()->id()) ? 'fas fa-thumbs-up text-primary' : 'far fa-thumbs-up' }}"></i>
              <span class="like-count">{{ $item->likes->count() }}</span>
          </button>
          <button class="love-btn" data-id="{{ $item->id }}">
            <i class="{{ $item->isLovedBy(auth()->id()) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
            <span class="love-count">{{ $item->loves->count() }}</span>
        </button>
<!-- comment secttion -->

{{-- Show Comments --}}
<div class="comments-section">
  @if($item->comments && count($item->comments))
    @foreach($item->comments as $comment)
      <div class="comment">
        <strong>{{ optional($comment->user)->name ?? 'Unknown User' }}:</strong> {{ $comment->comment }}
        
        {{-- Admin Reply Button --}}
        @if(auth()->check() && auth()->user()->is_admin)
          <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="newsfeed_id" value="{{ $item->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="comment" placeholder="Reply to this comment..." required></textarea>
            <button type="submit">Reply</button>
          </form>
        @endif

        {{-- Replies --}}
        @if($comment->replies && count($comment->replies))
          @foreach($comment->replies as $reply)
            <div class="reply" style="margin-left: 20px;">
              <strong>{{ optional($reply->user)->name ?? 'Unknown User' }} (Reply):</strong> {{ $reply->comment }}
            </div>
          @endforeach
        @endif
      </div>
    @endforeach
  @else
    <p>No comments yet.</p>
  @endif
</div>

{{-- New Comment Form --}}
@if(auth()->check())
  <form action="{{ route('comments.store') }}" method="POST">
    @csrf
    <input type="hidden" name="newsfeed_id" value="{{ $item->id }}">
    <textarea name="comment" placeholder="Write a comment..." required></textarea>
    <button type="submit">Post Comment</button>
  </form>
@endif


<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }
</script>




        <!-- <div class="action-btn comment-btn"><i class="ion ion-md-chatbubbles"></i> <span class="comment-count">0</span></div> -->
        <!-- <div class="action-btn view-btn"><i class="ion ion-md-eye"></i> <span class="view-count">0</span></div> -->
        <div class="action-btn share-btn"><i class="ion ion-md-share"></i></div>
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





</script>



@endsection
