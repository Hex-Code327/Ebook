<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_ebooks' => Ebook::count(),
            'free_ebooks' => Ebook::where('is_free', true)->count(),
        ];

        $recentUsers = User::latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'created_at']);

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers
        ]);
    }

    /**
     * Menampilkan daftar user
     */
    public function users()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form edit user
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'is_paid' => 'boolean'
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Data user berhasil diperbarui');
    }

    /**
     * Menonaktifkan user
     */
    public function deactivateUser(User $user)
    {
        $user->update(['is_active' => false]);

        return back()
            ->with('success', 'User berhasil dinonaktifkan');
    }

    /**
     * Menampilkan statistik dalam format JSON (untuk AJAX)
     */
    public function getStats()
    {
        $stats = [
            'users' => User::select(
                DB::raw('COUNT(*) as count'),
                DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get(),

            'ebooks' => Ebook::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('is_free as type')
            )
            ->groupBy('type')
            ->get()
        ];

        return response()->json($stats);
    }
}