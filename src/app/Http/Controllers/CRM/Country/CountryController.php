<?php

    namespace App\Http\Controllers\CRM\Country;

    use App\Http\Controllers\Controller;
    use App\Services\CRM\Country\CountryService;

    class CountryController extends Controller
    {
        public function __construct(CountryService $countryService)
        {
            $this->service = $countryService;
        }

        public function index()
        {
            return $this->service->select('id', 'code', 'name')->get();
        }
    }
