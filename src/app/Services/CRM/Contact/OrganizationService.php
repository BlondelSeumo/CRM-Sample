<?php

namespace App\Services\CRM\Contact;

use App\Helpers\Core\Traits\FileHandler;
use App\Models\Core\Builder\Form\CustomField;
use App\Services\ApplicationBaseService;
use App\Helpers\CRM\Traits\StoreFileTrait;
use App\Models\CRM\Organization\Organization;
use App\Services\CRM\Settings\CrmCustomFiledService;
use App\Services\CRM\Traits\ExportCustomFieldTrait;
use App\Services\CRM\Traits\personOrganizationDetails;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationService extends ApplicationBaseService
{
    use personOrganizationDetails, FileHandler, StoreFileTrait, ExportCustomFieldTrait;

    public function __construct(Organization $organization)
    {
        $this->model = $organization;
    }

    public function showAll()
    {
        return $this
        ->withCount(['openDeals', 'closeDeals'])
        ->with([
            'contactType:id,name,class',
            'owner:id,first_name,last_name',
            // 'CreatedBy:id,first_name,last_name',
            'tags:id,name,color_code',
            'customFields',
            'persons',
            'country',
            'profilePicture'
            // 'email.type',
            // 'phone.type'
        ]);
    }

    public function showOrganization($id, $pivot_data)
    {
        return $this->personOrganizationDetails($this->model, $id, $pivot_data);
    }

    public function fileSync($path, $organization)
    {
        foreach ($path as $key => $value) {
            $file_path = $this->fileStore(
                $value,
                'organization'
            );
            $file = $organization->files()->create([
                'type' => 'organization',
                'path' => $file_path,
            ]);

            //            $organization->activity()->create([
            //                'file' => $file->id,
            //            ]);
        }
    }

    public function downloadOrganization($batch = 0)
    {
        $export_count = config('excel.exports.chunk_size');
        $skip = ($export_count * $batch) - $export_count;
        $data = $this->mapper();
        $relation = [
            'contactType:id,name',
            'owner:id,first_name,last_name',
            'createdBy:id,first_name,last_name',
            'country:id,name',
            'customFields'];
        $organizations = getChunk($skip, $export_count, $this->model, $data, $relation);
        $title= __t('organization');
        return Excel::download(exportBuilder
        (
            $organizations,
            $this->getHeadings(),
            $title
        ), "$title-$batch.xlsx"
        );
    }
    private function getHeadings()
    {
        $customFields = resolve(CrmCustomFiledService::class)->customFieldsContext('organization')->toArray();
        return array_merge(
            [
                'Name',
                'Address',
                'Lead groups',
                'Created by',
                'Owner name',
                'Country',
                'Area',
                'State',
                'City',
                'Zip code'
            ], $customFields);
    }
    private function mapper()
    {
        return fn($organization) => [
            'name' => $organization->name,
            'address' => $organization->address,
            'contact_type_id' => $organization->contactType->name,
            'created_by' => $organization->createdBy->full_name,
            'owner_id' => $organization->owner->full_name,
            'country_id' => $organization->country ? $organization->country->name : '',
            'area' => $organization->area,
            'state' => $organization->state,
            'city' => $organization->city,
            'zip_code' => $organization->zip_code,
            ...$this->formatCustomData($organization->customFields, $contextType = 'organization'),
        ];
    }
}
