<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'surname',
        'phone',
        'birthday',
        'middlename',
        'email',
        'workdayStart',
        'workdayEnd',
    ];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
    ];

    /**
     * Get the doctor's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $fullNamePartList = array_filter([
            $this->surname,
            $this->firstname,
            $this->middlename,
        ]);

        return implode(' ', $fullNamePartList);
    }

    /**
     * @return string
     */
    public function getFormattedWorkdayStartAttribute()
    {
        $datetime = Carbon::parse();
        $datetime->setTime(0, $this->workdayStart);

        return $datetime->format('H:i');
    }

    /**
     * @return string
     */
    public function getFormattedWorkdayEndAttribute()
    {
        $datetime = Carbon::parse();
        $datetime->setTime(0, $this->workdayEnd);

        return $datetime->format('H:i');
    }

    public function getWorkdayStart(Carbon $date)
    {
        $workdayStartDate = clone $date;
        $workdayStartDate->setTime(0, $this->workdayStart);

        return $workdayStartDate;
    }

    public function getWorkdayEnd(Carbon $date)
    {
        $workdayEndDate = clone $date;
        $workdayEndDate->setTime(0, $this->workdayEnd);

        return $workdayEndDate;
    }
}
