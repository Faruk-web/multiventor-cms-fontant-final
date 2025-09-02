<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NewsFeed;
use App\Models\Product;
use App\Models\Like;
use App\Models\Love;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Section;
use App\Models\OrdersProduct;
use App\Models\Admin;
use App\Models\VendorsBusinessDetail;
use Illuminate\Support\Facades\Auth; 

class NewsFeetController extends Controller
{
    // Show newsfeed index
    public function index()
    {
        // $newsfeed = DB::table('news_feeds')->distinct()->get();
        $newsfeed = Newsfeed::with('likes','comments.user','user')->latest()->get();
        $allProducts = Product::pluck('product_name', 'id')->toArray();
        return view('newsfeed.index',compact('allProducts','newsfeed'));
    }
    
    public function categoryfeet($id)
    {
        $section = Section::findOrFail($id);
        $newsfeed = Newsfeed::with('likes','comments.user')->where('feet_type_id',$section->id)->latest()->get();
        $allProducts = Product::pluck('product_name', 'id')->toArray();
        return view('newsfeed.index',compact('allProducts','newsfeed'));
    }

    public function likeToggle($id)
    {
        $newsfeed = Newsfeed::findOrFail($id);
        $user = auth()->user();

        $like = $newsfeed->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            $newsfeed->likes()->create(['user_id' => $user->id]);
            return response()->json(['liked' => true]);
        }
    }

public function like($id)
{
    try {
        $newsfeed = Newsfeed::findOrFail($id);
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        $like = Like::where('user_id', $userId)
                    ->where('newsfeed_id', $newsfeed->id)
                    ->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'like_count' => $newsfeed->likes()->count()]);
        } else {
            Like::create([
                'user_id' => $userId,
                'newsfeed_id' => $newsfeed->id,
            ]);
            return response()->json(['liked' => true, 'like_count' => $newsfeed->likes()->count()]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function love($id)
{
    try {
        $newsfeed = Newsfeed::findOrFail($id);
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        $love = Love::where('user_id', $userId)
                    ->where('newsfeed_id', $newsfeed->id)
                    ->first();

        if ($love) {
            $love->delete();
            return response()->json(['loved' => false, 'love_count' => $newsfeed->loves()->count()]);
        } else {
            Love::create([
                'user_id' => $userId,
                'newsfeed_id' => $newsfeed->id,
            ]);
            return response()->json(['loved' => true, 'love_count' => $newsfeed->loves()->count()]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
// comment section
// app/Http/Controllers/CommentController.php

public function commentstore(Request $request)
{
   $request->validate([
        'newsfeed_id' => 'required',
        'comment' => 'required|string',
    ]);

    Comment::create([
        'newsfeed_id' => $request->newsfeed_id,
        'user_id' => auth()->id(), // Only users login allowed
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Comment posted!');
}
public function reply(Request $request)
{
    // dd($request);
    $request->validate([
        'newsfeed_id' => 'required',
        'parent_id' => 'required',
        'comment' => 'required|string',
    ]);

    Comment::create([
        'newsfeed_id' => $request->newsfeed_id,
        'parent_id' => $request->parent_id,
        'user_id' => auth()->id(), // Must be from 'users' table
        'comment' => $request->comment,
    ]);

    return back();
}
public function commentdestroy($id)
{
    $comment = Comment::findOrFail($id);

    // Check: only the owner can delete
    if (auth()->id() !== $comment->user_id) {
        abort(403, 'Unauthorized');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted successfully');
}



    // Show create post form with product names as tags
    public function create()
    {
         $userName = Auth::user()->name;
         $userid = Auth::user()->id;
        $products = DB::table('products')->select('product_name','id')->distinct()->get();
        $shopname = DB::table('vendors_business_details')->select('shop_name','vendor_id')->distinct()->get();
        return view('newsfeed.create', compact('products','shopname','userName','userid'));
    }
    

public function store(Request $request)
{
    $request->validate([
        'feet_type_id' => 'required|integer',
        'user_id' => 'required|integer',
        'vendor_id' => 'required|integer',
        'tags' => 'nullable|array',
        'review' => 'required|string',
        'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
    ]);

    $mediaPath = null;

    if ($request->hasFile('media')) {
    $file = $request->file('media');

    // Extension check (optional)
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];
    $ext = $file->getClientOriginalExtension();

    if (!in_array(strtolower($ext), $allowedExt)) {
        return back()->withErrors(['media' => 'Only JPG, PNG, GIF images and MP4, MOV, AVI videos are allowed']);
    }

    // Move file
    $filename = uniqid() . '.' . $ext;
    $uploadPath = public_path('uploads/reviews');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    $file->move($uploadPath, $filename);

    $mediaPath = 'uploads/reviews/' . $filename;
}


    // Save the review
    NewsFeed::create([
        'name' => $request->name,
        'feet_type_id' => $request->feet_type_id,
        'user_id' => $request->user_id,
        'vendor_id' => $request->vendor_id,
        'tags' => json_encode($request->tags),
        'review' => $request->review,
        'media_path' => $mediaPath,
    ]);

    return back()->with('success', 'Review submitted successfully!');
}

 public function edit($id)
    {
       $review = NewsFeed::findOrFail($id);
        $shopname = VendorsBusinessDetail::all(); // adjust according to your model
        $products = Product::all();
        return view('newsfeed.edit', compact('review', 'shopname', 'products'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'feet_type_id' => 'required|integer',
        'vendor_id' => 'required|integer',
        'tags' => 'nullable|array',
        'review' => 'required|string',
        'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
    ]);

    $review = NewsFeed::findOrFail($id);
    $mediaPath = $review->media_path;

    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $ext = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $ext;
        $uploadPath = public_path('uploads/reviews');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $file->move($uploadPath, $filename);
        $mediaPath = 'uploads/reviews/' . $filename;
    }

    $review->update([
        'feet_type_id' => $request->feet_type_id,
        'user_id' => $request->user_id,
        'vendor_id' => $request->vendor_id,
        'tags' => json_encode($request->tags),
        'review' => $request->review,
        'media_path' => $mediaPath,
    ]);

    return redirect()->route('news.feed')->with('success', 'Review updated successfully!');
}
public function destroy($id)
{
    $review = NewsFeed::findOrFail($id);

    // Optional: delete associated media file
    if ($review->media_path && file_exists(public_path($review->media_path))) {
        unlink(public_path($review->media_path));
    }

    $review->delete();

    return redirect()->back()->with('success', 'Review deleted successfully!');
}
// ==================Wardrobe=============
public function Wardrobe()
    {
       $orders_products =DB::table('orders_products')->pluck('product_id')->unique();
       $products =DB::table('products')->pluck('id');
        $orders = Order::with('orders_products.product')->where('user_id', Auth::id())->get();

        return view('newsfeed.Wardrobe', compact('orders_products', 'products','orders'));
    }
    public function vendorsproduct($id)
    {
        $vendor = Admin::with('businessDetails')->findOrFail($id); // single object
        $products = Product::where('vendor_id', $vendor->id)->get(); // collection
        return view('newsfeed.vendorsproduct', compact('vendor', 'products'));
    }

}
