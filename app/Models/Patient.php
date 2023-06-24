<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'surname',
        'middlename',
        'snils',
        'birthday',
        'homeAddress',
    ];

    protected $casts = [
        'birthday' => 'datetime:Y-m-d',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $fullNamePartList = array_filter([
            $this->firstname,
            $this->middlename,
            $this->surname,
        ]);

        return implode(' ', $fullNamePartList);
    }
}
