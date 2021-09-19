<?php


namespace App\Services\CRM\Country;


use App\Models\CRM\Country\Country;
use App\Services\ApplicationBaseService;

class CountryService extends ApplicationBaseService
{
    public function __construct(Country $country)
    {
        $this->model= $country;
    }
}