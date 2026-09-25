<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\RedirectResponse;

trait ResolvesPanel
{
    protected function panel(): string
    {
        $name = request()->route()?->getName();

        if (! $name || ! str_contains($name, '.')) {
            throw new \LogicException('Named route with a panel prefix is required.');
        }

        return explode('.', $name, 2)[0];
    }

    protected function panelView(string $view): string
    {
        return $this->panel().'.'.$view;
    }

    protected function panelRedirect(string $name, mixed $parameters = []): RedirectResponse
    {
        return redirect()->route($this->panel().'.'.$name, $parameters);
    }
}
