<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ThemeSetting\UpdateRequest;
use App\Models\Setting;

class ThemeSettingController extends Controller
{
    public function edit()
    {
        $newYearThemeEnabled = Setting::isNewYearThemeEnabled();

        return view('admin.theme-setting.edit', compact('newYearThemeEnabled'));
    }

    public function update(UpdateRequest $request)
    {
        Setting::setValue('new_year_theme_enabled', $request->boolean('new_year_theme_enabled'));

        return redirect()
            ->route('admin.settings.themes.edit')
            ->with('success', 'Настройки тем сохранены');
    }
}
