

@extends('front.layout.layout')
@section('content')
  <style>
  

    .post {
      background: #d7d7d770;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 2px 3px rgba(0,0,0,0.1);
      margin-top: 40px;
      overflow: hidden;
      position: relative;
    }
    .post-header {
      display: flex;
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

  <div class="post" data-viewed="false">
    <div class="post-header">
      <img src="https://randomuser.me/api/portraits/men/10.jpg" alt="avatar">
      <div class="username">Alex Turner</div>
    </div>
    <div style="position:relative">
      <img class="post-img" src="https://picsum.photos/id/1005/600/300" alt="post image">
      <div class="overlay-buttons">
        <button>🔖</button>
        <button>⋯</button>
      </div>
    </div>
    <div class="post-content">
      <p>Nature is not a place to visit. It is home. 🌳</p>
    </div>
    <div class="post-actions">
       <div class="action-btn like-btn"><i class="ion ion-md-heart-empty"></i> <span class="like-count">0</span></div>
        <div class="action-btn comment-btn"><i class="ion ion-md-chatbubbles"></i> <span class="comment-count">0</span></div>
        <div class="action-btn view-btn"><i class="ion ion-md-eye"></i> <span class="view-count">0</span></div>
        <div class="action-btn share-btn"><i class="ion ion-md-share"></i></div>
    </div>
  </div>
<div class="post" data-viewed="false">
    <div class="post-header">
      <img src="https://randomuser.me/api/portraits/men/10.jpg" alt="avatar">
      <div class="username">Alex Turner</div>
    </div>
    <div style="position:relative">
      <img class="post-img" src="https://picsum.photos/id/1005/600/300" alt="post image">
      <div class="overlay-buttons">
        <button>🔖</button>
        <button>⋯</button>
      </div>
    </div>
    <div class="post-content">
      <p>Nature is not a place to visit. It is home. 🌳</p>
    </div>
    <div class="post-actions">
       <div class="action-btn like-btn"><i class="ion ion-md-heart-empty"></i> <span class="like-count">0</span></div>
        <div class="action-btn comment-btn"><i class="ion ion-md-chatbubbles"></i> <span class="comment-count">0</span></div>
        <div class="action-btn view-btn"><i class="ion ion-md-eye"></i> <span class="view-count">0</span></div>
        <div class="action-btn share-btn"><i class="ion ion-md-share"></i></div>
    </div>
  </div>
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
