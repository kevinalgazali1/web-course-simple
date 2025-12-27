<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // ============================
        // ROLE ADMIN
        // ============================
        if ($user->role === 'admin') {

            $query = Course::with(['teacher', 'kategori', 'students']);

            // Search
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_course', 'like', '%' . $request->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
                });
            }

            // Filter kategori
            if ($request->kategori) {
                $query->where('kategori_id', $request->kategori);
            }

            // Filter status
            if ($request->status !== null) {
                $query->where('is_active', $request->status);
            }

            $courses = $query->latest()->paginate(9);

            // Statistik admin
            $totalCourses     = Course::count();
            $activeCourses    = Course::where('is_active', true)->count();
            $totalStudents    = DB::table('course_student')->distinct('student_id')->count();
            $totalCategories  = Kategori::count();

            $categories = Kategori::orderBy('kategori')->get();

            return view(
                'admin.course.index',
                compact(
                    'courses',
                    'totalCourses',
                    'activeCourses',
                    'totalStudents',
                    'totalCategories',
                    'categories'
                )
            );
        }

        // ============================
        // ROLE TEACHER
        // ============================
        if ($user->role === 'teacher') {

            $query = Course::with(['kategori', 'students'])
                ->where('teacher_id', $user->id);

            // Search
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_course', 'like', '%' . $request->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
                });
            }

            // Filter kategori
            if ($request->kategori) {
                $query->where('kategori_id', $request->kategori);
            }

            // Filter status
            if ($request->status !== null) {
                $query->where('is_active', $request->status);
            }

            $courses = $query->latest()->paginate(9);

            // Statistik teacher
            $totalCourses    = Course::where('teacher_id', $user->id)->count();
            $activeCourses   = Course::where('teacher_id', $user->id)->where('is_active', true)->count();
            $totalStudents   = Course::where('teacher_id', $user->id)
                ->withCount('students')
                ->get()
                ->sum('students_count');
            $totalCategories = Course::where('teacher_id', $user->id)
                ->distinct('kategori_id')
                ->count();

            $categories = Kategori::orderBy('kategori')->get();

            return view(
                'teacher.course.index',
                compact(
                    'courses',
                    'totalCourses',
                    'activeCourses',
                    'totalStudents',
                    'totalCategories',
                    'categories'
                )
            );
        }

        // ============================
        // Jika Student atau selainnya
        // ============================
        abort(403, 'You do not have permission to access this page.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $categories = Kategori::orderBy('kategori')->get();

        // Jika admin
        if ($user->role === 'admin') {
            $teachers = User::where('role', 'teacher')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            return view('admin.course.create', [
                'categories' => $categories,
                'teachers'   => $teachers
            ]);
        }

        // Jika teacher
        if ($user->role === 'teacher') {
            return view('teacher.course.create', [
                'categories' => $categories
            ]);
        }

        // Jika role tidak dikenali (opsional)
        abort(403, 'Unauthorized');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ROLE USER SAAT INI
        $role = Auth::user()->role;

        // ========== VALIDASI DINAMIS ==========
        $rules = [
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_course' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Jika ADMIN → teacher_id wajib dipilih
        if ($role === 'admin') {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // ========== SET TEACHER ID ==========
        if ($role === 'teacher') {
            // teacher → otomatis menjadi pengajar
            $validated['teacher_id'] = Auth::id();
        }

        // ========== UPLOAD GAMBAR ==========
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('courses', 'public');
        }

        Course::create($validated);

        // ========== REDIRECT BERDASARKAN ROLE ==========
        return $role === 'admin'
            ? redirect()->route('courses.index')->with('success', 'Course berhasil ditambahkan!')
            : redirect()->route('teacher.courses.index')->with('success', 'Course berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Pastikan teacher hanya bisa melihat course miliknya
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua konten terkait course
        $contents = $course->contents()->orderBy('id')->paginate(10);

        return view('teacher.course.show', compact('course', 'contents'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $user = Auth::user();

        // --- AMAN: Teacher hanya boleh edit course miliknya ---
        if ($user->role === 'teacher' && $course->teacher_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Data kategori untuk semua role
        $categories = Kategori::orderBy('kategori')->get();

        // Jika ADMIN → perlu list teacher
        if ($user->role === 'admin') {
            $teachers = User::where('role', 'teacher')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            return view('admin.course.edit', compact('course', 'categories', 'teachers'));
        }

        // Jika TEACHER → view berbeda, tidak butuh teacher list
        return view('teacher.course.edit', compact('course', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $user = Auth::user();
        $role = $user->role;

        // ========== VALIDASI ROLE ==========
        if ($role === 'teacher' && $course->teacher_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // ========== VALIDASI DINAMIS BERDASARKAN ROLE ==========
        $rules = [
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_course' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ];

        // Admin → bisa ganti teacher
        if ($role === 'admin') {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Teacher → teacher_id tidak boleh diganti
        if ($role === 'teacher') {
            $validated['teacher_id'] = $course->teacher_id;
        }

        // UPLOAD GAMBAR
        if ($request->hasFile('gambar')) {
            if ($course->gambar) {
                Storage::disk('public')->delete($course->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('courses', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        // UPDATE DATA
        $course->update($validated);

        // ========== REDIRECT ==========
        return $role === 'admin'
            ? redirect()->route('courses.index')->with('success', 'Course berhasil diperbarui!')
            : redirect()->route('teacher.courses.index')->with('success', 'Course berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $user = Auth::user();
        $role = $user->role;

        // ========== CEK AKSES BERDASARKAN ROLE ==========
        if ($role === 'teacher' && $course->teacher_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // ========== HAPUS GAMBAR JIKA ADA ==========
        if ($course->gambar) {
            Storage::disk('public')->delete($course->gambar);
        }

        // ========== DELETE COURSE ==========
        $course->delete();

        // ========== REDIRECT BERDASARKAN ROLE ==========
        return $role === 'admin'
            ? redirect()->route('courses.index')->with('success', 'Course berhasil dihapus!')
            : redirect()->route('teacher.courses.index')->with('success', 'Course berhasil dihapus!');
    }
}
