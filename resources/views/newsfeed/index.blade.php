

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
      <div class="post-content">
        <p>{{ $item->review }}</p>
      </div>

      {{-- Post Actions --}}
      <div class="post-actions">
        <div class="action-btn like-btn"><i class="ion ion-md-heart-empty"></i> <span class="like-count">0</span></div>
        <div class="action-btn comment-btn"><i class="ion ion-md-chatbubbles"></i> <span class="comment-count">0</span></div>
        <div class="action-btn view-btn"><i class="ion ion-md-eye"></i> <span class="view-count">0</span></div>
        <div class="action-btn share-btn"><i class="ion ion-md-share"></i></div>
      </div>
    </div>
  @endforeach
</div>


<script>
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const countSpan = this.querySelector('.like-count');
      let count = parseInt(countSpan.innerText);
      if (this.classList.contains('liked')) {
        this.classList.remove('liked');
        count--;
      } else {
        this.classList.add('liked');
        count++;
      }
      countSpan.innerText = count;
    });
  });

  document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const countSpan = this.querySelector('.comment-count');
      let count = parseInt(countSpan.innerText);
      count++;
      countSpan.innerText = count;
    });
  });

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting && entry.target.dataset.viewed === "false") {
        const viewSpan = entry.target.querySelector('.view-count');
        let count = parseInt(viewSpan.innerText);
        viewSpan.innerText = count + 1;
        entry.target.dataset.viewed = "true";
      }
    });
  }, { threshold: 0.5 });

  document.querySelectorAll('.post').forEach(post => {
    observer.observe(post);
  });
  document.querySelectorAll('.share-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    const shareUrl = window.location.href;
    navigator.clipboard.writeText(shareUrl).then(() => {
      alert("Post link copied to clipboard!");
    });
  });
});

</script>
@endsection
