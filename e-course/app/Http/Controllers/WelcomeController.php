<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is already logged in
        if (Auth::check()) {
            session()->flash('message', 'Anda sudah login');
            return redirect()->route(Auth::user()->role . '.dashboard');
        }

        // Get popular courses (top 5 by student count)
        $popularCourses = Course::with(['teacher', 'kategori'])
            ->where('is_active', true)
            ->withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // Get all categories
        $categories = Kategori::orderBy('kategori')->get();

        // Build query for all courses
        $query = Course::with(['teacher', 'kategori'])
            ->where('is_active', true)
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

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('students_count', 'desc');
                break;
            case 'most_content':
                $query->orderBy('contents_count', 'desc');
                break;
            default: // latest
                $query->latest();
                break;
        }

        $courses = $query->take(9)->get();

        return view('welcome', compact('courses', 'popularCourses', 'categories'));
    }
}