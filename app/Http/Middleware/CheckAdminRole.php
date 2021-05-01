<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Exception;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user_role = Role::find(auth()->user()->role_id);

            if (!strpos($user_role->slug, 'admin')) {
                throw new Exception('User not permitted to perform such action');
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => true,
                'code' => 403,
                'err' => $e->getMessage()
            ], 403);
        }

        return $next($request);
    }
}
