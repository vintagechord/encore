<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Discipline;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class ArtistPublicController extends Controller
{
    public function index(Request $request, ?string $discipline = null)
    {
        $disciplineSlug = $discipline ?: (string) $request->query('discipline', '');
        $disciplineSlug = $disciplineSlug === 'all' ? '' : $disciplineSlug;

        $categories = [];
        if (Schema::hasTable('disciplines')) {
            $categories = Discipline::orderBy('id')
                ->get()
                ->map(fn($row) => ['id' => $row->id, 'slug' => $row->slug, 'name' => $row->name])
                ->filter(fn($row) => !empty($row['slug']))
                ->values()
                ->all();
        }
        if (empty($categories)) {
            $categories = [
                ['id' => null, 'slug' => 'music', 'name' => '음악'],
                ['id' => null, 'slug' => 'mc', 'name' => '사회(MC)'],
                ['id' => null, 'slug' => 'dance', 'name' => '댄스'],
                ['id' => null, 'slug' => 'performance', 'name' => '퍼포먼스'],
                ['id' => null, 'slug' => 'plan', 'name' => '기획공연'],
                ['id' => null, 'slug' => 'celebrity', 'name' => '셀럽'],
            ];
        }

        $genreOptions = [];
        if (Schema::hasTable('genres')) {
            $genreOptions = Genre::orderBy('name')->get();
        }

        $hasMeta = Schema::hasColumn('artists', 'meta');
        $hasFormats = Schema::hasColumn('artists', 'formats');
        $hasGenresColumn = Schema::hasColumn('artists', 'genres');
        $hasHomeCity = Schema::hasColumn('artists', 'home_city');
        $hasActive = Schema::hasColumn('artists', 'active');
        $hasDisciplineId = Schema::hasColumn('artists', 'discipline_id');
        $feeMinCol = Schema::hasColumn('artists', 'fee_min') ? 'fee_min' : (Schema::hasColumn('artists', 'min_fee') ? 'min_fee' : null);
        $feeMaxCol = Schema::hasColumn('artists', 'fee_max') ? 'fee_max' : (Schema::hasColumn('artists', 'max_fee') ? 'max_fee' : null);

        $query = Artist::query()->with('discipline');
        if ($hasActive) {
            $query->where('active', true);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($qq) use ($search) {
                $qq->where('stage_name', 'like', "%{$search}%");
                if (Schema::hasColumn('artists', 'name')) {
                    $qq->orWhere('name', 'like', "%{$search}%");
                }
                if (Schema::hasColumn('artists', 'legal_name')) {
                    $qq->orWhere('legal_name', 'like', "%{$search}%");
                }
            });
        }

        if ($disciplineSlug !== '' && Schema::hasTable('disciplines') && $hasDisciplineId) {
            $disciplineId = Discipline::where('slug', $disciplineSlug)->value('id');
            if ($disciplineId) {
                $query->where('discipline_id', $disciplineId);
            }
        } elseif ($disciplineSlug !== '' && $hasMeta) {
            $query->where('meta->discipline', $disciplineSlug);
        }

        $region = trim((string) $request->query('region', ''));
        if ($region !== '' && $hasHomeCity) {
            $query->where('home_city', 'like', "%{$region}%");
        }

        $date = trim((string) $request->query('date', ''));
        if ($date !== '' && Schema::hasTable('artist_unavailabilities')) {
            try {
                $day = Carbon::parse($date)->toDateString();
                $query->whereDoesntHave('unavailabilities', function ($qq) use ($day) {
                    $qq->where('starts_on', '<=', $day)->where('ends_on', '>=', $day);
                });
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        }

        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        if ((!is_numeric($minPrice) && !is_numeric($maxPrice)) && $request->filled('price')) {
            $raw = (string) $request->query('price');
            if (preg_match('/^(\d+)\-(\d+)$/', $raw, $m)) {
                $minPrice = $m[1];
                $maxPrice = $m[2];
            } elseif (preg_match('/^(\d+)\+$/', $raw, $m)) {
                $minPrice = $m[1];
                $maxPrice = null;
            }
        }
        $minPrice = is_numeric($minPrice) ? (int) $minPrice : null;
        $maxPrice = is_numeric($maxPrice) ? (int) $maxPrice : null;
        if (($minPrice || $maxPrice) && Schema::hasTable('artist_fees')) {
            $query->whereHas('fees', function ($qq) use ($minPrice, $maxPrice) {
                $qq->where('is_active', true);
                if ($minPrice !== null) {
                    $qq->where(function ($q2) use ($minPrice) {
                        $q2->whereNull('max_fee')->orWhere('max_fee', '>=', $minPrice);
                    });
                }
                if ($maxPrice !== null) {
                    $qq->where(function ($q2) use ($maxPrice) {
                        $q2->whereNull('min_fee')->orWhere('min_fee', '<=', $maxPrice);
                    });
                }
            });
        } elseif (($minPrice || $maxPrice) && ($feeMinCol || $feeMaxCol)) {
            if ($minPrice !== null && $feeMaxCol) {
                $query->where(function ($qq) use ($feeMaxCol, $minPrice) {
                    $qq->whereNull($feeMaxCol)->orWhere($feeMaxCol, '>=', $minPrice);
                });
            }
            if ($maxPrice !== null && $feeMinCol) {
                $query->where(function ($qq) use ($feeMinCol, $maxPrice) {
                    $qq->whereNull($feeMinCol)->orWhere($feeMinCol, '<=', $maxPrice);
                });
            }
        }

        $genreId = $request->query('genre_id');
        $genre = trim((string) $request->query('genre', ''));
        if (is_numeric($genreId) && Schema::hasTable('artist_genre')) {
            $query->whereHas('genresRelation', fn($qq) => $qq->where('genres.id', (int) $genreId));
        } elseif ($genre !== '' && $hasGenresColumn) {
            try {
                $query->whereJsonContains('genres', $genre);
            } catch (\Throwable $e) {
                $query->where('genres', 'like', "%{$genre}%");
            }
        } elseif ($genre !== '' && Schema::hasColumn('artists', 'genre')) {
            $query->where('genre', 'like', "%{$genre}%");
        }

        $teamType = trim((string) $request->query('team_type', ''));
        if ($teamType !== '' && ($hasFormats || $hasMeta)) {
            $query->where(function ($qq) use ($teamType, $hasFormats, $hasMeta) {
                $applied = false;
                if ($hasFormats) {
                    try {
                        $qq->whereJsonContains('formats', $teamType);
                        $applied = true;
                    } catch (\Throwable $e) {
                        $qq->where('formats', 'like', "%{$teamType}%");
                        $applied = true;
                    }
                }
                if ($hasMeta) {
                    if ($applied) {
                        $qq->orWhere('meta->team_type', $teamType);
                        try { $qq->orWhereJsonContains('meta->team_type', $teamType); } catch (\Throwable $e) {}
                    } else {
                        $qq->where('meta->team_type', $teamType);
                        try { $qq->orWhereJsonContains('meta->team_type', $teamType); } catch (\Throwable $e) {}
                    }
                }
            });
        }

        $mcType = trim((string) $request->query('mc_type', ''));
        if ($mcType !== '' && $hasMeta) {
            $query->where(function ($qq) use ($mcType) {
                $qq->where('meta->mc_type', $mcType);
                try { $qq->orWhereJsonContains('meta->mc_type', $mcType); } catch (\Throwable $e) {}
            });
        }

        $mcStyle = trim((string) $request->query('mc_style', ''));
        if ($mcStyle !== '' && $hasMeta) {
            $query->where(function ($qq) use ($mcStyle) {
                $qq->where('meta->mc_style', $mcStyle);
                try { $qq->orWhereJsonContains('meta->mc_style', $mcStyle); } catch (\Throwable $e) {}
            });
        }

        $danceStyle = trim((string) $request->query('dance_style', ''));
        if ($danceStyle !== '' && $hasMeta) {
            $query->where(function ($qq) use ($danceStyle) {
                $qq->where('meta->dance_style', $danceStyle);
                try { $qq->orWhereJsonContains('meta->dance_style', $danceStyle); } catch (\Throwable $e) {}
            });
        }

        $performanceType = trim((string) $request->query('performance_type', ''));
        if ($performanceType !== '' && $hasMeta) {
            $query->where(function ($qq) use ($performanceType) {
                $qq->where('meta->performance_type', $performanceType);
                try { $qq->orWhereJsonContains('meta->performance_type', $performanceType); } catch (\Throwable $e) {}
            });
        }

        $planType = trim((string) $request->query('plan_type', ''));
        if ($planType !== '' && $hasMeta) {
            $query->where(function ($qq) use ($planType) {
                $qq->where('meta->plan_type', $planType);
                try { $qq->orWhereJsonContains('meta->plan_type', $planType); } catch (\Throwable $e) {}
            });
        }

        $celebrityType = trim((string) $request->query('celebrity_type', ''));
        if ($celebrityType !== '' && $hasMeta) {
            $query->where(function ($qq) use ($celebrityType) {
                $qq->where('meta->celebrity_type', $celebrityType);
                try { $qq->orWhereJsonContains('meta->celebrity_type', $celebrityType); } catch (\Throwable $e) {}
            });
        }

        $career = trim((string) $request->query('career', ''));
        if ($career !== '' && $hasMeta) {
            $query->where(function ($qq) use ($career) {
                $qq->where('meta->career', $career);
                try { $qq->orWhereJsonContains('meta->career', $career); } catch (\Throwable $e) {}
            });
        }

        $ageGroup = trim((string) $request->query('age_group', ''));
        if ($ageGroup !== '' && $hasMeta) {
            $query->where(function ($qq) use ($ageGroup) {
                $qq->where('meta->age_group', $ageGroup);
                try { $qq->orWhereJsonContains('meta->age_group', $ageGroup); } catch (\Throwable $e) {}
            });
        }

        $artists = $query->latest('id')->paginate(12)->appends($request->query());

        $activeCategory = $disciplineSlug ?: 'all';

        $favoriteIds = [];
        try {
            if ($request->user() && Schema::hasTable('artist_favorites')) {
                $favoriteIds = $request->user()->favoriteArtists()->pluck('artists.id')->all();
            }
        } catch (\Throwable $e) {
            $favoriteIds = [];
        }

        return view('public.artists.index', [
            'artists' => $artists,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'genreOptions' => $genreOptions,
            'filters' => $request->all(),
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function show(Artist $artist)
    {
        $isFavorite = false;
        try {
            if (auth()->check() && Schema::hasTable('artist_favorites')) {
                $isFavorite = auth()->user()->favoriteArtists()->where('artists.id', $artist->id)->exists();
            }
        } catch (\Throwable $e) {
            $isFavorite = false;
        }

        return view('public.artist_show', compact('artist', 'isFavorite'));
    }
}
