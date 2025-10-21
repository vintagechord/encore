<?php
namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

class PublicHomeController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route('inquiry.create');
    }
}
