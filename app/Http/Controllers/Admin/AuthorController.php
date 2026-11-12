<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Author\StoreRequest;
use App\Http\Requests\Admin\Author\UpdateRequest;
use App\Models\Author;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function exportPdf(Author $author)
    {
        $photoDataUri = null;

        if ($author->photo && Storage::disk('public')->exists($author->photo)) {
            $contents = Storage::disk('public')->get($author->photo);
            $mime = Storage::disk('public')->mimeType($author->photo) ?: 'image/jpeg';
            $photoDataUri = 'data:'.$mime.';base64,'.base64_encode($contents);
        }

        $filename = 'author-'.$author->id;
        $nameSlug = Str::slug($author->full_name ?: $author->name);
        if ($nameSlug !== '') {
            $filename .= '-'.$nameSlug;
        }

        return Pdf::loadView('admin.author.pdf', [
            'author' => $author,
            'photoDataUri' => $photoDataUri,
            'descriptionHtml' => $this->prepareAuthorDescriptionForPdf($author->description),
        ])
            ->setPaper('a4')
            ->download($filename.'.pdf');
    }

    private function prepareAuthorDescriptionForPdf(?string $description): string
    {
        $html = trim((string) $description);
        if ($html === '') {
            return '';
        }

        // DomPDF does not embed web fonts from HTML (e.g. Google Sans) and then
        // renders Cyrillic as "???". Keep markup, drop foreign font-family rules.
        $html = preg_replace('/font-family\s*:\s*[^;"]+;?/i', '', $html) ?? $html;
        $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;

        return trim($html);
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
