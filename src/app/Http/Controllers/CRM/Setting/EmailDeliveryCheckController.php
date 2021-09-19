<?php

namespace App\Http\Controllers\CRM\Setting;

use App\Http\Controllers\Controller;
use App\Services\CRM\Settings\EmailDeliveryCheckService;

class EmailDeliveryCheckController extends Controller
{
    public function __construct(EmailDeliveryCheckService $service)
    {
    	$this->service = $service;
    }
	
	public function isExists()
	{
		return count($this->service->getCachedMailSettings()) ? 1 : 0;
	}
}
