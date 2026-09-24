<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Author\StoreRequest;
use App\Http\Requests\Admin\Author\UpdateRequest;
use App\Models\Author;

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
        Author::firstOrCreate($data);

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
        $author->update($data);

        return redirect()->route('admin.author.show', compact('author'));
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('admin.author.index');
    }
}
