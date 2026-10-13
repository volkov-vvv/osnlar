<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FormSetting\UpdateRequest;
use App\Models\Setting;

class FormSettingController extends Controller
{
    public function edit()
    {
        $lidCaptchaEnabled = Setting::isLidCaptchaEnabled();

        return view('admin.form-setting.edit', compact('lidCaptchaEnabled'));
    }

    public function update(UpdateRequest $request)
    {
        Setting::setValue('lid_captcha_enabled', $request->boolean('lid_captcha_enabled'));

        return redirect()
            ->route('admin.settings.forms.edit')
            ->with('success', 'Настройки форм сохранены');
    }
}
