<?php


namespace App\Services\Setup;


use App\Models\CRM\User\User;
use App\Services\ApplicationBaseService;
use App\Services\Core\Auth\UserService;
use App\Setup\Helper\PermissionsHelper;
use App\Setup\Helper\Requirements;
use App\Setup\Manager\DatabaseManager;
use App\Setup\Manager\EnvironmentManager;
use App\Setup\Manager\FinalInstallManager;
use App\Setup\Manager\PurchaseCodeManager;
use App\Setup\Manager\StorageManager;
use App\Setup\Validator\PurchaseCodeValidator;
use Database\Seeders\SetupSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

class InstallationService extends ApplicationBaseService
{
    protected $requirement;
    protected $permission;
    protected $environment;
    protected $userService;
    protected $brandService;
    protected $purchaseCodeValidator;
    protected $databaseManager;
    protected $purchaseCodeManager;
    protected $manager;
    protected $storage;

    public function __construct(
        Requirements $requirements,
        PermissionsHelper $permission,
        EnvironmentManager $environment,
        UserService $userService,
        PurchaseCodeValidator $codeValidator,
        DatabaseManager $databaseManager,
        PurchaseCodeManager $codeManager,
        FinalInstallManager $manager,
        StorageManager $storage
    )
    {
        $this->requirement = $requirements;
        $this->permission = $permission;
        $this->environment = $environment;
        $this->userService = $userService;
        $this->purchaseCodeValidator = $codeValidator;
        $this->databaseManager = $databaseManager;
        $this->purchaseCodeManager = $codeManager;
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function validatePurchaseCode(Request $request = null)
    {
        $purchase_code = $request ?  $request->get('code') : env('PURCHASE_CODE') ;

        throw_if(
            !$this->purchaseCodeValidator->validate($purchase_code),
            ValidationException::withMessages([ 'code' => [trans('default.invalid_purchase_code')] ])
        );

        return $this;
    }

    public function setDatabaseConfig()
    {
        $this->databaseManager->setConfig();

        return $this;
    }

    public function storeEnvironment(Request $request)
    {
        $this->environment->saveFileWizard($request);

        return $this;
    }

    public function clearCache()
    {
        $this->manager->clear();

        return $this;
    }

    public function migrate()
    {
        $this->databaseManager->migrate();

        return $this;
    }

    public function storePurchaseCode()
    {
        $this->purchaseCodeManager->store(
            env('PURCHASE_CODE')
       );

        return $this;
    }

    public function seedStatusAndType()
    {
        $this->databaseManager->seedEssential();

        return $this;
    }

    public function createUser()
    {
        $user = $this->userService
            ->create()
            ->getModel();

        $this->databaseManager->seedRole();

        /** @var User $user */
        $user->assignRole(config('access.users.app_admin_role'));

        return $this;
    }

    public function seedDatabase()
    {
        Artisan::call('db:seed', [
            '--class' => SetupSeeder::class,
            '--force' => true
        ]);

        return $this;
    }

    public function linkStorage()
    {
        $this->storage->link();

        return $this;
    }

    public function finishInstallation()
    {
        $this->environment->setEnvironmentValue('APP_INSTALLED', 'true');

        $this->manager->finish();

        return $this;
    }
}
