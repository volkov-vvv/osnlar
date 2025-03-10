<?php

namespace App\Http\Controllers\lid;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Author;
use App\Models\Category;
use App\Models\Course;
use App\Models\Leveledu;
use App\Models\Region;
use Illuminate\Http\Request;


class CreateNewController extends Controller
{
    public function __invoke(Request $request, $selectedCourse = null)
    {
        $cookies = $request->cookie();
        $utm = ['utm_source' => '', 'utm_medium' => '', 'utm_campaign' =>''];

        if(isset($_GET['utm_source'])){
            $utm['utm_source'] = $_GET['utm_source'];
        }
        elseif(isset($cookies['utm_source'])){
            $utm['utm_source'] = $cookies['utm_source'];
        }

        if(isset($_GET['utm_medium'])){
            $utm['utm_medium'] = $_GET['utm_medium'];
        }
        elseif(isset($cookies['utm_medium'])){
            $utm['utm_medium'] = $cookies['utm_medium'];
        }

        if(isset($_GET['utm_campaign'])){
            $utm['utm_campaign'] = $_GET['utm_campaign'];
        }
        elseif(isset($cookies['utm_campaign'])){
            $utm['utm_campaign'] = $cookies['utm_campaign'];
        }

        $categories = Category::whereNotIn('id',[4,5])->get()->sortBy('order');
        $categoriesMain = Category::find([4,5])->sortBy('order');
        $agents = Agent::all();
        $regions = Region::all();
        $authors = Author::all();
        $levelsedu = Leveledu::all();
        $courses = Course::where('is_published', 1)->whereNull('price')->get();
        if(isset($selectedCourse)){
            $pageDescription = 'Подать заявку на обучение по курсу ' . $courses->where('id', $selectedCourse)->first()->title . 'в рамках проекта Содействие занятости';
        }else{
            $pageDescription = 'Подать заявку на обучение в рамках проекта Содействие занятости';
        }

        return view('lid.create_new', compact('categories', 'authors','levelsedu','courses','regions','agents','categoriesMain', 'selectedCourse', 'pageDescription', 'utm'));
    }

}
