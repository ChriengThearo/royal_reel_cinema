<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Show the movie detail / playback page.
     * The route is protected by the 'auth' middleware.
     */
    public function show(Request $request, string $id): View
    {
        // Map known movie slugs to display-friendly titles
        $titles = [
            'featured'        => 'Thearo, INC.',
            'the-good-dinosaur' => 'The Good Dinosaur',
            'aladdin'         => 'Aladdin',
            'luca'            => 'Luca',
            'tangled'         => 'Tangled',
            'coco'            => 'Coco',
            'moana'           => 'Moana',
            'finding-nemo'    => 'Finding Nemo',
            'up'              => 'Up',
            'inside-out'      => 'Inside Out',
        ];

        $title = $titles[$id] ?? ucwords(str_replace('-', ' ', $id));

        return view('movies.show', compact('title', 'id'));
    }
}
