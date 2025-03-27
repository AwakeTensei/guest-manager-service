<?php

namespace App\Services;

class GuestService
{
    public function determineCountryFromPhone($phone)
    {
        $prefixes = [
            '+7' => 'Russia',
            '+1' => 'USA',
            '+44' => 'UK',
        ];

        foreach ($prefixes as $prefix => $country) {
            if (str_starts_with($phone, $prefix)) {
                return $country;
            }
        }
        return null;
    }
}