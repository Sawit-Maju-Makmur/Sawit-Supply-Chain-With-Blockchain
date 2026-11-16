<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pemakaian di route: EnsureUserHasRole::class.':management,sustainability_officer'
 * (tidak perlu didaftarkan sebagai alias, jadi cocok di semua versi Laravel).
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (method_exists($user, 'hasAnyRole')) {
            try {
                if ($user->hasAnyRole($roles)) {
                    return $next($request);
                }
            } catch (\Throwable $e) {
                // Lanjut ke pengecekan atribut role
            }
        }

        $role = self::roleOf($user);

        abort_unless($role !== null && in_array($role, $roles, true), 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }

    /** Mengembalikan role sebagai string, baik kolom role berupa enum maupun string biasa. */
    public static function roleOf($user): ?string
    {
        if (! $user) {
            return null;
        }

        if (! empty($user->role)) {
            return $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
        }

        if (method_exists($user, 'getRoleNames')) {
            try {
                $roleName = $user->getRoleNames()->first();
                if ($roleName) {
                    return (string) $roleName;
                }
            } catch (\Throwable $e) {
                //
            }
        }

        return null;
    }
}

