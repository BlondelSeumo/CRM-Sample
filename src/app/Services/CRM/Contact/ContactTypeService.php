<?php


namespace App\Services\CRM\Contact;


use App\Models\CRM\Contact\ContactType;
use App\Services\ApplicationBaseService;

class ContactTypeService extends ApplicationBaseService
{
    public function __construct(ContactType $type)
    {
        $this->model = $type;
    }
}
