<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'firstname' => 'required_without_all:middlename,surname|max:50|alpha|nullable',
            'middlename' => 'required_without_all:firstname,surname|max:50|alpha|nullable',
            'surname' => 'required_without_all:firstname,middlename|max:50|alpha|nullable',
            'snils' => 'required|size:11',
            'birthday' => 'date|nullable',
            'homeAddress' => 'nullable|max:200|',
        ];
    }
}
