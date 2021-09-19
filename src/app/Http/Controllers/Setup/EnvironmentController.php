<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\AdminRequest;
use App\Http\Requests\Setup\EnvRequest;
use App\Services\Setup\InstallationService;

class EnvironmentController extends Controller
{
    public function __construct(InstallationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('setup.environment');
    }

    public function store(EnvRequest $request)
    {
        $this->service
            ->validatePurchaseCode($request)
            ->setDatabaseConfig()
            ->storeEnvironment($request);

        return response()->json(['status' => true, 'message' => __t('environment_setup_successfully')]);
    }

    public function show()
    {
        if (env('PURCHASE_CODE')) {
            return view('setup.admin');
        }

        return redirect()->route('environment.index');
    }

    public function emailSetup()
    {
        if (env('PURCHASE_CODE')) {
            return view('setup.email_setup');
        }

        return redirect()->route('environment.index');
    }

    public function broadcastSetup()
    {
        if (env('PURCHASE_CODE')) {
            return view('setup.broadcast_setup');
        }

        return redirect()->route('environment.index');
    }

    public function update(AdminRequest $request)
    {
        $this->service
            ->validatePurchaseCode()
            ->clearCache()
            ->migrate()
            ->storePurchaseCode()
            ->seedStatusAndType()
            ->createUser()
            ->seedDatabase()
            ->linkStorage();
        // ->finishInstallation();

        return response()->json(['status' => true, 'message' => trans('default.app_installed_successfully')]);
    }
}
