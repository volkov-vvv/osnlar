<?php

namespace App\Http\Requests\Api;

use App\Models\Course;
use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

class LidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => '',
            'data' => '',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:lids',
            'course_id' => 'integer',
            'lid_level_edu' => 'string',
            'region_id' => 'required|integer',
            'category' => 'string',
            'company_id' => 'required|integer',
            'politic' => 'required',
            'in_project' => '',
            'utm_source' => '',
            'utm_medium' => '',
            'utm_campaign' => '',
            'utm_term' => '',
            'utm_content' => '',
            'created_at' => 'nullable|string',
            'responsible_id' => 'nullable',
        ];
    }

    protected function prepareForValidation() {

        $course = Course::where('is_published', 1)
            ->where('title', $this->course)
            ->where('company_id', $this->company_id)
            ->first();

        $region = Region::where('title', $this->region)->first();

        $this->merge( [
                //'course_id' => $course->id,
                'region_id' => $region->id
            ] );
    }
}
