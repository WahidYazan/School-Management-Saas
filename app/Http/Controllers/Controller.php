<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Resolve the school id the current request should operate on.
     * A super admin works on the school stored in the session (active school),
     * all other users are always bound to their own school.
     */
    protected function schoolId(Request $request): ?int
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return session('active_school_id') ?: $user->school_id;
        }

        return $user->school_id;
    }

    /**
     * Like schoolId(), but aborts when no school is available
     * (e.g. a super admin who has not selected an active school yet).
     */
    protected function requireSchool(Request $request): int
    {
        if (! $schoolId = $this->schoolId($request)) {
            $message = $request->user()->isSuperAdmin()
                ? 'Pilih sekolah aktif terlebih dahulu.'
                : 'Akun Anda belum terhubung ke sekolah mana pun.';

            abort(403, $message);
        }

        return (int) $schoolId;
    }
}
