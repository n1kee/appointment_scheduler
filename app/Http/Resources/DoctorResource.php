<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'surname' => $this->surname,
            'fullName' => $this->fullName,
            'phone' => $this->phone,
            'workdayStart' => $this->formattedWorkdayStart ?? null,
            'workdayEnd' => $this->formattedWorkdayEnd ?? null,
            'birthday' => $this->birthday ? $this->birthday->format('Y-m-d') : null,
            'email' => $this->email,
        ];
    }
}
