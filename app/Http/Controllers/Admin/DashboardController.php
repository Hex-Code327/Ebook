<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin
     */
    public function index()
    {
        // Hitung statistik dasar
        $stats = [
            'total_users' => User::count(),
            'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_ebooks' => Ebook::count(),
            'free_ebooks' => Ebook::where('is_free', true)->count(),
            'premium_users' => User::where('is_paid', true)->count(),
            'premium_percentage' => User::count() > 0 ? round((User::where('is_paid', true)->count() / User::count()) * 100, 2) : 0,
        ];

        // Data untuk grafik aktivitas
        $activityChart = $this->getActivityChartData();

        // Data ebook terpopuler
        $popularEbooks = Ebook::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pengguna terbaru
        $recentUsers = User::latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'is_paid', 'created_at']);

        return view('admin.dashboard', [
            'stats' => $stats,
            'activityChart' => $activityChart,
            'popularEbooks' => $popularEbooks,
            'recentUsers' => $recentUsers
        ]);
    }

    /**
     * Mengambil data untuk grafik aktivitas
     */
    protected function getActivityChartData()
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        // Generate all dates in range
        $dates = [];
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dates[$currentDate->format('Y-m-d')] = 0;
            $currentDate->addDay();
        }

        // Get actual registration data
        $registrations = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->pluck('count', 'date')
        ->toArray();

        return [
            'labels' => array_keys($dates),
            'registrations' => array_values(array_merge($dates, $registrations)),
        ];
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
}