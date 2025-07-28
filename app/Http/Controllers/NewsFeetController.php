<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NewsFeed;
use App\Models\Product;
use App\Models\VendorsBusinessDetail;
use Illuminate\Support\Facades\Auth; 

class NewsFeetController extends Controller
{
    // Show newsfeed index
    public function index()
    {
        $newsfeed = DB::table('news_feeds')->distinct()->get();
        $allProducts = Product::pluck('product_name', 'id')->toArray();

        return view('newsfeed.index',compact('allProducts','newsfeed'));
    }

    // Show create post form with product names as tags
    public function create()
    {
         $userName = Auth::user()->name;
        $products = DB::table('products')->select('product_name','id')->distinct()->get();
        $shopname = DB::table('vendors_business_details')->select('shop_name','vendor_id')->distinct()->get();
        return view('newsfeed.create', compact('products','shopname','userName'));
    }
    

public function store(Request $request)
{
    $request->validate([
        'product_name' => 'required|string|max:255',
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
        'product_name' => $request->product_name,
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
        'product_name' => 'required|string|max:255',
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
        'product_name' => $request->product_name,
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


}
