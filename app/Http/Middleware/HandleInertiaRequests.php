<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $shared = parent::share($request);

        return [
            ...$shared,
            'auth' => [
                ...($shared['auth'] ?? []),
                'user'          => $request->user()?->load('tasca'),
                'is_admin'      => $request->user()?->hasRole(Role::ADMIN->value),
                'is_customer'   => $request->user()?->hasRole(Role::CUSTOMER->value),
                'is_employee'   => $request->user()?->hasRole(Role::EMPLOYEE->value),
                'is_manager'    => $request->user()?->hasRole(Role::MANAGER->value),
                'is_tasca'      => $request->user()?->hasRole(Role::TASCA->value),
                'impersonating' => Session::has('impersonator_id'),
            ],
        ];
    }
}
