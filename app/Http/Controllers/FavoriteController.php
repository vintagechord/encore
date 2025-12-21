<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    public function store(Artist $artist, Request $request)
    {
        $user = $request->user();
        if (!$user || !Schema::hasTable('artist_favorites')) {
            return back();
        }
        $user->favoriteArtists()->syncWithoutDetaching([$artist->id]);
        return back();
    }

    public function destroy(Artist $artist, Request $request)
    {
        $user = $request->user();
        if (!$user || !Schema::hasTable('artist_favorites')) {
            return back();
        }
        $user->favoriteArtists()->detach($artist->id);
        return back();
    }
}
