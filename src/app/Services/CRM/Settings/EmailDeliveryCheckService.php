<?php
	
	
	namespace App\Services\CRM\Settings;
	
	
	use App\Services\Core\BaseService;
	use App\Services\Core\Setting\DeliverySettingService;
	
	class EmailDeliveryCheckService extends BaseService
	{
		
		public function getMailSettings()
		{
			$service = resolve(DeliverySettingService::class);
			
			$default = $service
				->getDefaultSettings();
			
			return $service
				->getFormattedDeliverySettings([
					optional($default)->value,
					'default_mail_email_name'
				]);
		}
		
		public function getCachedMailSettings()
		{
			return cache()->remember('app-delivery-settings', 86400, function () {
				return $this->getMailSettings();
			});
		}
	}