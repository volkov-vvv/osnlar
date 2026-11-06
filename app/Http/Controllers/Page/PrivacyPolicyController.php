<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        $pageTitle = 'Политика обработки персональных данных';
        $pageDescription = 'Политика в отношении обработки персональных данных ООО «Центр повышения квалификации и профессиональной подготовки «Основание»';

        return view('pages.privacy', compact('pageTitle', 'pageDescription'));
    }
}
