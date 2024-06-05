<?php

namespace App\Http\Requests\Admin\Lid;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreNewRequest extends FormRequest
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
            'phone_prefix' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:lids',
            'course_id' => 'required|string',
            'lid_level_edu_id' => 'string',
            'region_id' => 'required|string',
            'category_id' => '',
            'agent_id' => 'string',
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

    protected function passedValidation() {
        $data = $this->validator->getData();

        $this->validator->setData( [
                'phone' => explode('+' . $data['phone_prefix'], $data['phone'])[1],
            ] + $data);

        if(isset($data['created_at'])){
            $data = $this->validator->getData();
            $this->validator->setData( [
                    'created_at' => Carbon::createFromFormat('Y-m-d\TH:i:s', $data['created_at'])->toDateTimeString()
                ] + $data);
        }

    }
}
