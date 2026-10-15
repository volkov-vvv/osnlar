<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Author\StoreRequest;
use App\Http\Requests\Admin\Author\UpdateRequest;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('admin.author.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.author.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        unset($data['photo']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('authors', 'public');
        }

        Author::create($data);

        return redirect()->route('admin.author.index');
    }

    public function show(Author $author)
    {
        return view('admin.author.show', compact('author'));
    }

    public function edit(Author $author)
    {
        return view('admin.author.edit', compact('author'));
    }

    public function update(UpdateRequest $request, Author $author)
    {
        $data = $request->validated();
        unset($data['photo']);

        if ($request->hasFile('photo')) {
            if ($author->photo) {
                Storage::disk('public')->delete($author->photo);
            }

            $data['photo'] = $request->file('photo')->store('authors', 'public');
        }

        $author->update($data);

        return redirect()->route('admin.author.show', compact('author'));
    }

    public function publish(Request $request, Author $author)
    {
        $author->update([
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'is_published' => $author->is_published,
            ]);
        }

        return redirect()->route('admin.author.index');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('admin.author.index');
    }
}
