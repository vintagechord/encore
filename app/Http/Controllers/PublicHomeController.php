<?php

namespace App\Http\Controllers;

class PublicHomeController extends Controller
{
    public function __invoke()
    {
        // 필요한 뷰로 바꿔도 됨
        return view('welcome');
    }
}
