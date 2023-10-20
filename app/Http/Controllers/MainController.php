<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function HomePage(){
        return view('homepage');
    }

    public function SinglePostPage(){
        return view('Single-post');
    }
}
