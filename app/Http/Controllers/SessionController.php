<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SessionController extends Controller
{
    /**
     * Mostrar sesiones activas del usuario.
     */
    public function index(Request $request): View
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($request) {
                return (object) [
                    'id'              => $session->id,
                    'ip_address'      => $session->ip_address,
                    'user_agent'      => $session->user_agent,
                    'last_activity'   => $session->last_activity,
                    'is_current'      => $session->id === $request->session()->getId(),
                    'last_active_at'  => \Carbon\Carbon::createFromTimestamp(
                        $session->last_activity
                    )->diffForHumans(),
                ];
            });

        return view('sessions.index', compact('sessions'));
    }

    /**
     * Cerrar todas las demás sesiones (requiere contraseña).
     */
    public function destroyOthers(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ]);
        }

        // Eliminar todas excepto la sesión actual
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('status', 'Otras sesiones cerradas.');
    }

    /**
     * Cerrar una sesión específica.
     */
    public function destroy(Request $request, string $sessionId): RedirectResponse
    {
        // Solo permite cerrar sesiones propias
        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        if ($deleted) {
            return back()->with('status', 'Sesión cerrada.');
        }

        return back()->withErrors(['session' => 'Sesión no encontrada.']);
    }
}
