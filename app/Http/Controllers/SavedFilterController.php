<?php

namespace App\Http\Controllers;

use App\Models\SavedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    public function save(Request $request)
    {
        // Валидация: проверяем, что state — это массив/json
        $request->validate(['state' => 'required|array']);

        $user = Auth::user();

        dd($request['state']);
        SavedFilter::updateOrCreate(
            ['user_id' => auth()->id(),
            'page_url' => 'lid',
            'filters' => json_encode($request['state'])]
        );

        return response()->json(['status' => 'saved']);
    }

    public function get()
    {
        //return response()->json(Auth::user()->table_state ?? []);
    }
}
