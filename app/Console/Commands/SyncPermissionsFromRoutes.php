<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class SyncPermissionsFromRoutes extends Command
{
    protected $signature = 'permissions:sync-from-routes';
    protected $description = 'Create permissions based on route names';

    public function handle()
    {
        $routes = Route::getRoutes();
        $created = 0;

        foreach ($routes as $route) {
            $name = $route->getName();

            // Only process named routes
            if (!$name || !Str::contains($name, '.')) {
                continue;
            }

            // Example: plans.store → plans-create
            $segments = explode('.', $name);
            if (count($segments) !== 2) continue;

            [$resource, $action] = $segments;

            $permission = $this->mapActionToPermission($resource, $action);

            if ($permission && !Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
                $created++;
                $this->info("Created permission: {$permission}");
            }
        }

        $this->info("✅ Done. Total permissions created: {$created}");
    }

    private function mapActionToPermission($resource, $action)
    {
        $actionMap = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
            'delete' => 'delete',
        ];

        if (!isset($actionMap[$action])) {
            return null;
        }

        return "{$resource}-{$actionMap[$action]}";
    }
}

