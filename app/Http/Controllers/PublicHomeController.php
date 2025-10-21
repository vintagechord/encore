<?php
namespace App\Http\Controllers;

class PublicHomeController extends Controller
{
    public function __invoke()
    {
        return view('public.home'); // ← 기존 랜딩
    }
}
