<?php

namespace App\Http\Controllers;

use App\Models\SavedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    public function save(Request $request)
    {
        if (auth()->check()) {
            $state = $request->state;
            if (is_string($state)) {
                $state = json_decode($state, true) ?? [];
            }

            SavedFilter::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'page_url' => $request->page_url
                ],
                [
                    'user_id' => auth()->id(),
                    'page_url' => $request->page_url,
                    'filters' => $state
                ]
            );
        }

        return response()->json(['status' => 'saved']);
    }

    public function get(Request $request)
    {
        $filters = SavedFilter::where('user_id', auth()->id())
            ->where('page_url', $request['page_url'])
            ->first();

        $state = $filters->filters ?? null;

        // Старые записи могли сохраниться JSON-строкой
        if (is_string($state)) {
            $state = json_decode($state, true);
        }

        return response()->json($state);
    }
}
