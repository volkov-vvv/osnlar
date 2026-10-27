<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HelpController extends Controller
{
    public function index(): View
    {
        $pages = $this->pagesForRole((int) auth()->user()->role);
        $groups = collect($pages)->groupBy('group');

        return view('help.index', [
            'pageTitle' => 'Справка',
            'pageDescription' => 'Инструкция по работе с платформой «Основание»',
            'groups' => $groups,
        ]);
    }

    public function show(string $page): View
    {
        $pages = $this->pagesForRole((int) auth()->user()->role);
        $index = collect($pages)->search(fn (array $item) => $item['slug'] === $page);

        if ($index === false) {
            throw new NotFoundHttpException();
        }

        $current = $pages[$index];
        $markdown = $this->readMarkdown($current['file']);
        $html = $this->renderMarkdown($markdown);

        $prev = $index > 0 ? $pages[$index - 1] : null;
        $next = $index < count($pages) - 1 ? $pages[$index + 1] : null;

        return view('help.show', [
            'pageTitle' => $current['title'].' — Справка',
            'pageDescription' => $current['title'],
            'current' => $current,
            'html' => $html,
            'navPages' => $pages,
            'prev' => $prev,
            'next' => $next,
        ]);
    }

    public function image(Request $request, string $path): BinaryFileResponse
    {
        $path = str_replace('\\', '/', $path);
        if ($path === '' || str_contains($path, '..') || ! preg_match('/^[a-zA-Z0-9_\\/\\-]+\\.png$/', $path)) {
            throw new NotFoundHttpException();
        }

        $root = realpath(base_path('docs/images'));
        if ($root === false) {
            throw new NotFoundHttpException();
        }

        $full = realpath($root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path));
        if ($full === false || ! str_starts_with($full, $root.DIRECTORY_SEPARATOR) || ! is_file($full)) {
            throw new NotFoundHttpException();
        }

        return response()->file($full, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * @return list<array{slug: string, title: string, group: string, file: string, roles: list<int>}>
     */
    protected function pagesForRole(int $role): array
    {
        return array_values(array_filter(
            config('help.pages', []),
            fn (array $page) => in_array($role, $page['roles'] ?? [], true)
        ));
    }

    protected function readMarkdown(string $relativeFile): string
    {
        $docsRoot = realpath(base_path('docs'));
        if ($docsRoot === false) {
            throw new NotFoundHttpException();
        }

        $relativeFile = str_replace('\\', '/', $relativeFile);
        if (str_contains($relativeFile, '..') || ! preg_match('/^[a-zA-Z0-9_\\/\\-]+\\.md$/', $relativeFile)) {
            throw new NotFoundHttpException();
        }

        $full = realpath($docsRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeFile));
        if ($full === false || ! str_starts_with($full, $docsRoot.DIRECTORY_SEPARATOR) || ! is_file($full)) {
            throw new NotFoundHttpException();
        }

        return File::get($full);
    }

    protected function renderMarkdown(string $markdown): string
    {
        $html = Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = preg_replace_callback(
            '/<img\s+([^>]*?)src=(["\'])([^"\']+)\2([^>]*)>/i',
            function (array $matches) {
                $src = $matches[3];
                $rewritten = $this->rewriteImageSrc($src);
                if ($rewritten === null) {
                    return $matches[0];
                }

                return '<img '.$matches[1].'src='.$matches[2].e($rewritten).$matches[2].$matches[4].'>';
            },
            $html
        );

        // Internal .md links → help.show when slug known
        $slugByFile = [];
        foreach (config('help.pages', []) as $page) {
            $slugByFile[$page['file']] = $page['slug'];
            $slugByFile[basename($page['file'])] = $page['slug'];
        }

        $html = preg_replace_callback(
            '/<a\s+([^>]*?)href=(["\'])([^"\']+\.md(?:#[^"\']*)?)\2([^>]*)>/i',
            function (array $matches) use ($slugByFile) {
                $href = $matches[3];
                $hash = '';
                if (str_contains($href, '#')) {
                    [$href, $frag] = explode('#', $href, 2);
                    $hash = '#'.$frag;
                }
                $normalized = ltrim(preg_replace('#^\.\./#', '', str_replace('\\', '/', $href)), '/');
                $slug = $slugByFile[$normalized] ?? $slugByFile[basename($normalized)] ?? null;
                if ($slug === null) {
                    return $matches[0];
                }

                $url = route('help.show', $slug).$hash;

                return '<a '.$matches[1].'href='.$matches[2].e($url).$matches[2].$matches[4].'>';
            },
            $html
        );

        return $html ?? '';
    }

    protected function rewriteImageSrc(string $src): ?string
    {
        $src = str_replace('\\', '/', $src);
        if (preg_match('#^(?:https?:)?//#i', $src) || str_starts_with($src, 'data:')) {
            return null;
        }

        if (preg_match('#(?:^|/)images/(.+)$#', $src, $m)) {
            $relative = $m[1];
        } elseif (preg_match('#^\.\./images/(.+)$#', $src, $m)) {
            $relative = $m[1];
        } else {
            return null;
        }

        $relative = ltrim($relative, '/');
        if ($relative === '' || str_contains($relative, '..') || ! preg_match('/^[a-zA-Z0-9_\\/\\-]+\\.png$/', $relative)) {
            return null;
        }

        return route('help.image', ['path' => $relative]);
    }
}
