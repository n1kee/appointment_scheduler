<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class StoreDoctorRequest extends FormRequest
{
    public function __construct(Factory $validationFactory)
    {

        $validationFactory->extend(
            'workday_end',
            function (string $attribute, string $value, array $parameters, Validator $validation) {

                $workdayEnd = new Carbon($value);

                $workdayStart = new Carbon($validation->attributes()['workdayStart']);

                return ($workdayEnd->timestamp - $workdayStart->timestamp) > 0;
            },
            'Конец рабочего дня не может быть раньше его начала или равен ему!'
        );

    }

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
            'firstname' => 'required|max:50|alpha',
            'middlename' => 'nullable|max:50|alpha',
            'surname' => 'required|max:50|alpha',
            'phone' => 'digits:11',
            'workdayStart' => 'required|regex:/\d\d:\d\d/',
            'workdayEnd' => 'required|regex:/\d\d:\d\d/|workday_end',
            'birthday' => 'date|nullable',
            'email' => 'email|nullable|max:50',
        ];
    }
}
