<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchPersonTrait
{
    public function searchByFullname(string $searchQuery, Builder $q)
    {

        $fullNameParts = preg_split("/\s+/", $searchQuery);
        $fullNamePartsLength = count($fullNameParts);

        if (! $fullNamePartsLength) {
            return;
        }

        if ($fullNamePartsLength > 3) {
            return;
        }

        if ($fullNamePartsLength === 1) {
            $q->where(function ($q) use ($searchQuery) {
                $q->where('surname', 'like', "%{$searchQuery}%");
                $q->orWhere('firstname', 'like', "%{$searchQuery}%");
                $q->orWhere('middlename', 'like', "%{$searchQuery}%");
            });

        } if ($fullNamePartsLength === 2) {

            $q->where(function ($q) use ($fullNameParts) {

                [$surname, $firstname] = $fullNameParts;

                $q->where(function ($q) use ($surname, $firstname) {
                    $q->where('surname', 'like', "%{$surname}%");
                    $q->where('firstname', 'like', "%{$firstname}%");
                });

                [$firstname, $surname] = $fullNameParts;

                $q->orWhere(function ($q) use ($surname, $firstname) {
                    $q->where('surname', 'like', "%{$surname}%");
                    $q->where('firstname', 'like', "%{$firstname}%");
                });
            });

        } if ($fullNamePartsLength === 3) {

            $q->where(function ($q) use ($fullNameParts) {

                [$surname, $firstname, $middlename] = $fullNameParts;
                $q->where('surname', 'like', "%{$surname}%");
                $q->where('firstname', 'like', "%{$firstname}%");
                $q->where('middlename', 'like', "%{$middlename}%");

            });
        }
    }
}
