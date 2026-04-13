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
            SavedFilter::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'page_url' => $request->page_url
                ],
                [
                    'user_id' => auth()->id(),
                    'page_url' =>  $request->page_url,
                    'filters' => $request->state
                ]
            );
        }

        return response()->json(['status' => 'saved']);
    }

    public function get(Request $request)
    {
        $filters = SavedFilter::where('user_id', auth()->id())->where('page_url', $request['page_url'])->first();
        return $filters->filters ?? [];
    }
}
