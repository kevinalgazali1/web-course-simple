<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        // Total Statistics
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = Course::count();

        // Recent Courses (5 latest)
        $recentCourses = Course::with(['teacher', 'kategori'])
            ->latest()
            ->take(5)
            ->get();

        // Popular Categories with course count
        $popularCategories = Kategori::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->take(5)
            ->get();

        // Active users (users who are active)
        $activeUsers = User::where('is_active', true)->count();

        // Total enrollments
        $totalEnrollments = DB::table('course_student')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTeachers',
            'totalStudents',
            'totalCourses',
            'recentCourses',
            'popularCategories',
            'activeUsers',
            'totalEnrollments'
        ));
    }
}
