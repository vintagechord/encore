<?php

namespace App\Http\Controllers;

use App\Models\Artist;

class ArtistPublicController extends Controller
{
    public function show(Artist $artist)
    {
        return view('public.artist_show', compact('artist'));
    }
}

