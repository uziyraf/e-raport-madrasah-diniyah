<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('kepala_sekolah')) {
            return redirect()->route('principal.dashboard');
        } elseif ($user->hasRole('wali_kelas')) {
            return redirect()->route('homeroom.dashboard');
        } elseif ($user->hasRole('guru_fan')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('wali_santri')) {
            return redirect()->route('guardian.dashboard');
        } else {
            // Default redirect if no role matches (e.g., to a general dashboard or login page)
            return redirect()->route('dashboard'); // Fallback to the default dashboard
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
