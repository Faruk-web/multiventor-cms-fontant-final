<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsFeetController extends Controller
{
    //
     public function index() {
        return view('newsfeed.index'); 
    }
     public function create() {
        return view('newsfeed.create'); 
    }
}
