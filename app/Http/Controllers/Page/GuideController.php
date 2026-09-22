<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GuideController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        $path = public_path('files/2_Документация,_содержащая_описание_функциональных_характеристик.pdf');

        return response()->file($path);
    }
}
