<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsFeetController extends Controller
{
    // Show newsfeed index
    public function index()
    {
        return view('newsfeed.index');
    }

    // Show create post form with product names as tags
    public function create()
    {
        $products = DB::table('products')->select('product_name')->distinct()->get();
        return view('newsfeed.create', compact('products'));
    }
}
