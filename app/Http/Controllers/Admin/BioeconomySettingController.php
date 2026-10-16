<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BioeconomySetting\UpdateRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class BioeconomySettingController extends Controller
{
    public function edit()
    {
        $documents = collect(Setting::bioeconomyDocuments())->map(function (array $document) {
            $document['path'] = Setting::getValue($document['key']);

            return $document;
        });

        return view('admin.bioeconomy-setting.edit', compact('documents'));
    }

    public function update(UpdateRequest $request)
    {
        foreach (Setting::bioeconomyDocuments() as $document) {
            $current = Setting::getValue($document['key']);
            $input = $document['input'];

            if ($request->hasFile($input)) {
                $this->deleteStoredFile($current);
                Setting::setValue($document['key'], $request->file($input)->store('bioeconomy', 'public'));
                continue;
            }

            if ($request->boolean('remove_' . $input)) {
                $this->deleteStoredFile($current);
                Setting::setValue($document['key'], '');
            }
        }

        return redirect()
            ->route('admin.settings.bioeconomy.edit')
            ->with('success', 'Документы биоэкономики сохранены');
    }

    private function deleteStoredFile(?string $path): void
    {
        if (!$path || str_contains($path, '..')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
