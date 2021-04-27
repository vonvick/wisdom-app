<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        function altPermissions ($permission) {
            $altPermissions = ['*', $permission];
            $permParts = explode('.', $permission);

            if ($permParts && count($permParts) > 1) {
                $currentPermission = '';
                for ($i = 0; $i < (count($permParts) - 1); $i++) {
                    $currentPermission .= $permParts[$i] . '.';
                    $altPermissions[] = $currentPermission . '*';
                }
            }

            return $altPermissions;
        }

        $cacheKey = 'permissions';
        $permissions = Cache::get($cacheKey);

        if (!$permissions) {
            $permissions = Permission::pluck('slug');
            Cache::put($cacheKey, $permissions->toArray());
        }
        else {
            $permissions = collect($permissions);
        }

        $permissions->each(function(string $slug) {
            Gate::define($slug, function (User $user, Model $resource = null, String $resource_field = '') use($slug) {
                $cacheKey = 'user.' . $user->id . '.permissions';
                $userPermissions = Cache::get($cacheKey);

                if (!$userPermissions) {
                    $userClosure = function($query) use($user) {
                        $query->where('users.id', '=', $user->id);
                    };

                    $userPermissions = Permission::query()
                        ->whereHas('roles', function($query) use($userClosure) {
                            $query->where('active', '=', 1)->whereHas('users', $userClosure);
                        })
                        // TODO: We will enable this when we want to access permissions given to users directly
                        // ->orWhereHas('users', $userClosure)
                        ->groupBy('permissions.id')
                        ->where('active', '=', 1)
                        ->pluck('slug');
                    Cache::put($cacheKey, $userPermissions->toArray());
                }
                else {
                    $userPermissions = collect($userPermissions);
                }

                if ($userPermissions) {
                    $altPermissions = altPermissions($slug);
                    return null !== $userPermissions->first(function(string $slug) use($altPermissions) {
                        return \in_array($slug, $altPermissions, true);
                    });
                }
                else if ($resource && $resource[$resource_field] === $user->id) {
                    return true;
                }

                return false;
            });
        });
    }
}
