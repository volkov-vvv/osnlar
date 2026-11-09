<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect to the role cabinet (legacy /home route).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect(auth()->user()->homePath());
    }
}
