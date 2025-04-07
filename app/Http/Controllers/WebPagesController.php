<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class WebPagesController extends Controller
{
    public function index() {
        $blogs = Blog::with(['author', 'categories', 'tags', 'branch']) 
            ->where('status', 'published')
            ->where('post_type', 'post')
            ->latest() 
            ->paginate(6);  
        return view('web.pages.front_page', compact('blogs'));
    }
}
