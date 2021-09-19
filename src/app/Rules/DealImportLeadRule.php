<?php

namespace App\Rules;

use App\Helpers\Core\Traits\InstanceCreator;
use App\Helpers\Core\Traits\Memoization;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DealImportLeadRule implements Rule
{
    use Memoization;
    use InstanceCreator;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = explode(":",$value);
        $prefix = count($value) ? $value[0] : null;
        $lead = count($value) > 1 ? $value[1] : null;
        $contactPerson = count($value) > 2 ? $value[2] : null;

        //[$prefix, $lead, $contactPerson] = Str::of($value)->split("/:/");

        $prefix = strtolower($prefix);

        if (!in_array($prefix, ['p', 'o'])) {
            return false;
        }

        if ($prefix === 'p') {
            return $this->getPeopleLeads()
                ->contains($lead);
        }

        if ($prefix === 'o') {
            if (!$contactPerson)
            {
                return $this->getOrganizationLeads()
                    ->contains($lead);
            }
            elseif ($this->getOrganizationLeads()->contains($lead) && $this->getPeopleLeads()->contains($contactPerson))
            {
                $organizationId = $this->getOrganizationLeads()->flip()[$lead];
                $contactPersonId = $this->getPeopleLeads()->flip()[$contactPerson];
                $organizationPerson = $this->organizationContactPersons($organizationId);

                return $organizationPerson->contains($contactPersonId);
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The lead is not valid.';
    }

    public function getPeopleLeads()
    {
        return $this->memoize('peopleLeads', function () {
            return DB::table('people')
                ->pluck('name', 'id');
        });
    }

    public function getOrganizationLeads()
    {
        return $this->memoize('organizationLeads', function () {
            return DB::table('organizations')
                ->pluck( 'name', 'id');
        });
    }

    public function organizationContactPersons($id)
    {
        return $this->memoize('organizationContactPersons', function () use ($id) {
            return DB::table('person_organization')
                ->where('organization_id', $id)
                ->pluck('person_id');
        });
    }
}
