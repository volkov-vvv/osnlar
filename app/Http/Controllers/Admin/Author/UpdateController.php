<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Author\UpdateRequest;
use App\Models\Author;

use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Author $author)
    {
        $data = $request->validated();
        $author->update($data);

        return redirect()->route('admin.author.show', compact('author'));

    }
}
