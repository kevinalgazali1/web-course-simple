<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter status
        if ($request->status !== null) {
            $query->where('is_active', $request->status);
        }

        $users = $query->latest()->paginate(10);

        // Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();

        return view('admin.user.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'totalTeachers',
            'totalStudents'
        ));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:student,teacher',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,teacher,student',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'role.required' => 'Role wajib dipilih',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Jika password tidak diisi, hapus dari array validasi agar tidak ikut diupdate
        if (!$request->filled('password')) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($request->password);
        }
        $validated['is_active'] = $request->boolean('is_active');

        // Update data user
        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Check if user is a teacher with active courses
        if ($user->role === 'teacher') {
            $courseCount = $user->teacherCourses()->count();

            if ($courseCount > 0) {
                return redirect()
                    ->route('users.index')
                    ->with('error', "User tidak dapat dihapus karena masih mengajar {$courseCount} course. Hapus atau transfer course terlebih dahulu.");
            }
        }

        // Check if user is a student enrolled in courses
        if ($user->role === 'student') {
            $enrolledCount = $user->studentCourses()->count();

            if ($enrolledCount > 0) {
                return redirect()
                    ->route('users.index')
                    ->with('error', "User tidak dapat dihapus karena masih terdaftar di {$enrolledCount} course. Keluarkan dari course terlebih dahulu.");
            }
        }

        // Delete user if no restrictions
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
