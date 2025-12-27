<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Statistics
        // Course yang sudah diikuti/enrolled
        $enrolledCourses = Auth::user()->studentCourses()->count();
        
        // Course yang belum diikuti (aktif dan belum enrolled)
        $enrolledCourseIds = Auth::user()->studentCourses()->pluck('courses.id');
        $availableCourses = Course::active()
            ->whereNotIn('id', $enrolledCourseIds)
            ->count();
        
        // Total konten dari course yang diikuti
        $totalContents = Auth::user()->studentCourses()
            ->withCount('contents')
            ->get()
            ->sum('contents_count');

        // My Courses (Course yang diikuti dengan teacher dan contents count)
        $myCourses = Auth::user()->studentCourses()
            ->with(['teacher', 'kategori'])
            ->withCount('contents')
            ->latest('course_student.created_at')
            ->take(5)
            ->get();

        // Popular Categories (kategori dengan course terbanyak)
        $popularCategories = Kategori::withCount(['courses' => function($query) {
            $query->where('is_active', true);
        }])
            ->having('courses_count', '>', 0)
            ->orderBy('courses_count', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'enrolledCourses',
            'availableCourses',
            'totalContents',
            'myCourses',
            'popularCategories'
        ));
    }

    public function courses(Request $request)
    {
        $user = Auth::user();
        
        // Get enrolled course IDs
        $enrolledCourseIds = $user->studentCourses()->where('courses.is_active', true)->pluck('courses.id')->toArray();

        $query = Course::active()->with(['teacher', 'kategori'])
            ->withCount(['students', 'contents']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_course', 'like', '%'.$request->search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$request->search.'%');
            });
        }

        // Filter by category
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by enrollment status
        if ($request->filter === 'enrolled') {
            $query->whereIn('id', $enrolledCourseIds);
        } elseif ($request->filter === 'not_enrolled') {
            $query->whereNotIn('id', $enrolledCourseIds);
        }

        $courses = $query->latest()->paginate(9);

        // Add is_enrolled flag to each course
        foreach ($courses as $course) {
            $course->is_enrolled = in_array($course->id, $enrolledCourseIds);
        }

        // Statistics
        $totalAvailable = Course::active()->count();
        $totalEnrolled = count($enrolledCourseIds);
        $totalCategories = Kategori::count();
        $categories = Kategori::orderBy('kategori')->get();

        return view('student.course.index', compact(
            'courses',
            'totalAvailable',
            'totalEnrolled',
            'totalCategories',
            'categories'
        ));
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Check if user is student
        if ($user->role !== 'student') {
            return redirect()->back()->with('error', 'Hanya student yang bisa mendaftar course.');
        }

        // Check if course is active
        if (!$course->is_active) {
            return redirect()->back()->with('error', 'Course tidak aktif.');
        }

        // Check if already enrolled
        if ($user->studentCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('info', 'Anda sudah terdaftar di course ini.');
        }

        // Enroll student
        $user->studentCourses()->attach($course->id);

        return redirect()->back()->with('success', 'Berhasil mendaftar course: ' . $course->nama_course);
    }
}
