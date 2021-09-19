<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class HelperServiceProvider.
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        $rdi = new RecursiveDirectoryIterator(app_path('Helpers'.DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'Global'));
        $it = new RecursiveIteratorIterator($rdi);

        while ($it->valid()) {
            if (
                ! $it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php' &&
                strpos($it->current()->getFilename(), 'Helper')
            ) {
                require $it->key();
            }

            $it->next();
        }

        // Crm Helper

        $crmrdi = new RecursiveDirectoryIterator(app_path('Helpers'.DIRECTORY_SEPARATOR.'CRM'.DIRECTORY_SEPARATOR.'Global'));
        $crmit = new RecursiveIteratorIterator($crmrdi);

        while ($crmit->valid()) {
            if (
                ! $crmit->isDot() &&
                $crmit->isFile() &&
                $crmit->isReadable() &&
                $crmit->current()->getExtension() === 'php' &&
                strpos($crmit->current()->getFilename(), 'Helper')
            ) {
                require $crmit->key();
            }

            $crmit->next();
        }
    }
}
