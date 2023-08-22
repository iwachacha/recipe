<?php

namespace App\Http\Controllers;
use App\Http\Requests\TagRequest;
use App\Models\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(TagRequest $request, Tag $tag)
    {
        $tag->create([
            'name' => $request['name']
        ]);
        return redirect()->route('recipe.create');
    }
}
