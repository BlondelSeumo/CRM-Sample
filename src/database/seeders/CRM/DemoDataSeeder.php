<?php

namespace Database\Seeders\CRM;

use App\Models\Core\Auth\Role;
use App\Models\CRM\Deal\DealPerson;
use App\Models\CRM\Discussion\Discussion;
use App\Models\CRM\Tag\Tag;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Note\Note;
use App\Models\CRM\Email\Email;
use App\Models\CRM\Phone\Phone;
use App\Models\CRM\User\User;
use Illuminate\Database\Seeder;
use App\Models\CRM\Tag\Taggable;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Activity\Activity;
use App\Models\CRM\Follower\Follower;
use App\Models\CRM\Pipeline\Pipeline;
use App\Models\CRM\Proposal\Proposal;
use App\Models\CRM\PersonOrganization;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\Traits\TruncateTable;
use App\Models\CRM\Participant\Participant;
use App\Models\CRM\Organization\Organization;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\ConsoleMessageTrait;
use Database\Seeders\CRM\Stage\DemoStagesTableSeeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys, ConsoleMessageTrait;

    public function run()
    {
        Model::unguard();
        $this->disableForeignKeys();

        // Demo Stages Seeder
        $this->call(DemoStagesTableSeeder::class);

        // Demo Data for Pipelines
        Pipeline::truncate();
        $this->truncateMessage(new Pipeline());
        factory(Pipeline::class, 3)->create();
        $this->seededMessage(new Pipeline());

        // Demo Data for Persons
        Person::truncate();
        $this->truncateMessage(new Person());
        $persons = factory(Person::class, 15)->create();
        $this->seededMessage(new Person());

        // Demo Data for Organizations
        Organization::truncate();
        $this->truncateMessage(new Organization());
        factory(Organization::class, 15)->create();
        $this->seededMessage(new Organization());

        //demo data for PersonOrganization

        PersonOrganization::truncate();
        $this->truncateMessage(new PersonOrganization());
        factory(PersonOrganization::class, 15)->create();
        $this->seededMessage(new PersonOrganization());

        // Demo Data for Emails
        Email::truncate();
        $this->truncateMessage(new Email());
        factory(Email::class, 50)->create();
        $this->seededMessage(new Email());

        // Demo Data for Phones
        Phone::truncate();
        $this->truncateMessage(new Phone());
        factory(Phone::class, 50)->create();
        $this->seededMessage(new Phone());

        // Demo Data for Followers
        Follower::truncate();
        $this->truncateMessage(new Follower());
        factory(Follower::class, 30)->create();
        $this->seededMessage(new Follower());

        // Demo Data for Notes
        Note::truncate();
        $this->truncateMessage(new Note());
        factory(Note::class, 15)->create();
        $this->seededMessage(new Note());

        // Demo Data for Activities
        Activity::truncate();
        $this->truncateMessage(new Activity());
        factory(Activity::class, 30)->create();
        $this->seededMessage(new Activity());

        // Demo Data for Tags
        Tag::query()->truncate();
        $this->truncateMessage(new Tag());
        factory(Tag::class, 28)->create();
        $this->seededMessage(new Tag());

        // Demo Data for Deals
        Deal::truncate();
        $this->truncateMessage(new Deal());
        // Check Dispatcher
        Deal::getEventDispatcher();

        // Remove Dispatcher
        Deal::unsetEventDispatcher();

        $deals = factory(Deal::class, 50)->create();
        $this->seededMessage(new Deal());

        // Add Dispatcher
        Deal::setEventDispatcher(new \Illuminate\Events\Dispatcher);
        $dealsPersons = $deals->where('contextable_type', Person::class)
            ->map(function ($deal) {
                return [
                    'deal_id' => $deal->id,
                    'person_id' => $deal->contextable_id
                ];
            })->values()
            ->toArray();

        $dealsOrgs = $deals->where('contextable_type', '!=', Person::class)
            ->filter(function ($deal) {
                return PersonOrganization::where('organization_id', $deal->contextable_id)->count() > 0;
            })
            ->map(function ($deal) {
                return [
                    'deal_id' => $deal->id,
                    'person_id' => PersonOrganization::where('organization_id', $deal->contextable_id)->first()->person_id
                ];
            })->values()
            ->toArray();

        DealPerson::query()->truncate();
        $this->truncateMessage(new DealPerson());
        DealPerson::query()->insert($dealsPersons);
        DealPerson::query()->insert($dealsOrgs);
        $this->seededMessage(new DealPerson());

        Taggable::query()->truncate();
        $this->truncateMessage(new Taggable());
        $tags = factory(Taggable::class, 100)->make();
        foreach ($tags as $tag) {
            repeat:
            try {
                $tag->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $tag = factory(Taggable::class)->make();
                goto repeat;
            }
        }
        $this->seededMessage(new Taggable());

        // Demo Data for Proposal
        Proposal::truncate();
        $this->truncateMessage(new Proposal());
        factory(Proposal::class, 15)->create();
        $this->seededMessage(new Proposal());

        //Client user data create start
        $clientUser = $deals->where('contextable_type', Person::class)->first()->client();
        Email::query()->insert([
            'value' => 'client@demo.com',
            'type_id' => 1,
            'contextable_id' => $clientUser->id,
            'contextable_type' => Person::class
        ]);
        $userData = [
            'first_name' => $clientUser->name,
            'last_name' => null,
            'email' => 'client@demo.com',
            'password' => Hash::make('123456'),
            'status_id' => 1
        ];
        $userId = User::query()->create($userData);
        $clientUser->update(['attach_login_user_id' => $userId->id]);
        $role = Role::whereId(4)->first();
        $userId->roles()->sync($role);

        //Client user data create end

        // Demo Data for Participants
        Participant::truncate();
        $this->truncateMessage(new Participant());
        factory(Participant::class, 30)->create();
        $this->seededMessage(new Participant());

        Discussion::truncate();
        $this->truncateMessage(new Discussion());
        factory(Discussion::class, 30)->create();
        $this->seededMessage(new Discussion());

        $this->enableForeignKeys();
        Model::reguard();
    }
}
