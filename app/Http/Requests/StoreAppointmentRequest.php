<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function __construct(Factory $validationFactory)
    {

        $validationFactory->extend(
            'time_available',
            function (string $attribute, string $value, array $parameters, Validator $validation) {

                $date = new Carbon($value);

                $minutes = $date->secondsSinceMidnight() / 60;
                $doctorId = $validation->attributes()['doctor_id'];
                $doctor = Doctor::whereId($doctorId)
                    ->where('workdayStart', '<=', $minutes)
                    ->where('workdayEnd', '>=', $minutes + 30)
                    ->first();

                if (! $doctor) {
                    return false;
                }

                $latestTimeBefore = (new Carbon($value))->subMinutes(30);
                $earliestTimeAfter = (new Carbon($value))->addMinutes(30);

                return ! Appointment::where('datetime', '>', $latestTimeBefore->format('Y-m-d H:i'))
                    ->where('datetime', '<', $earliestTimeAfter->format('Y-m-d H:i'))
                    ->first();
            },
            'Данное время не доступно для записи!'
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
            'doctor_id' => 'required|numeric',
            'patient_id' => 'required|numeric',
            'datetime' => 'required|date|time_available',
        ];
    }
}
