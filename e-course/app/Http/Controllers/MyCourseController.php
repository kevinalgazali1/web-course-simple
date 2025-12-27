<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Content;
use App\Models\ContentProgress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    /**
     * Display list of enrolled courses
     */
    public function index()
    {
        $user = Auth::user();

        // Get enrolled courses with progress
        $courses = $user->studentCourses()
            ->with(['teacher', 'kategori', 'contents'])
            ->withCount('contents')
            ->latest('course_student.created_at')
            ->paginate(6);

        // Calculate progress for each course
        foreach ($courses as $course) {
            $totalContents = $course->contents_count;
            
            // Count completed contents
            $completedContents = ContentProgress::whereIn('content_id', $course->contents->pluck('id'))
                ->where('student_id', $user->id)
                ->where('is_completed', true)
                ->count();

            $course->completed_contents_count = $completedContents;
            $course->progress_percentage = $totalContents > 0 
                ? round(($completedContents / $totalContents) * 100) 
                : 0;
        }

        // Statistics
        $totalEnrolled = $user->studentCourses()->count();
        
        // Completed courses (100% progress)
        $completedCourses = $courses->filter(function ($course) {
            return $course->progress_percentage == 100;
        })->count();

        $totalContents = $user->studentCourses()
            ->withCount('contents')
            ->get()
            ->sum('contents_count');

        $enrolledCourseIds = $user->studentCourses()->pluck('courses.id');
        $completedContents = ContentProgress::whereHas('content', function ($query) use ($enrolledCourseIds) {
                $query->whereIn('course_id', $enrolledCourseIds);
            })
            ->where('student_id', $user->id)
            ->where('is_completed', true)
            ->count();

        return view('student.my-course.index', compact(
            'courses',
            'totalEnrolled',
            'completedCourses',
            'totalContents',
            'completedContents'
        ));
    }

    /**
     * Display course detail with contents
     */
    public function show(Course $course, Request $request)
    {
        $user = Auth::user();

        // Check if student is enrolled
        if (!$user->studentCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.index')
                ->with('error', 'Anda harus mendaftar terlebih dahulu untuk mengakses course ini.');
        }

        // Load course with relationships
        $course->load(['teacher', 'kategori', 'contents' => function ($query) use ($course) {
            $query->where('course_id', $course->id)->orderBy('id');
        }]);

        // Get all contents with completion status
        $contents = $course->contents->map(function ($content, $index) use ($user) {
            $progress = ContentProgress::where('content_id', $content->id)
                ->where('student_id', $user->id)
                ->first();

            $content->is_completed = $progress ? $progress->is_completed : false;
            $content->completed_at = $progress ? $progress->completed_at : null;
            $content->order = $index + 1; // Order based on collection index (1, 2, 3, ...)

            return $content;
        });

        // Get current content (from request or first uncompleted or first)
        $currentContent = null;
        if ($request->has('content')) {
            $currentContent = $contents->firstWhere('id', (int)$request->content);
        }
        
        if (!$currentContent) {
            // Get first uncompleted content
            $currentContent = $contents->firstWhere('is_completed', false);
            
            // If all completed, show first content
            if (!$currentContent) {
                $currentContent = $contents->first();
            }
        }

        // Get previous and next content
        $currentIndex = $contents->search(function ($content) use ($currentContent) {
            return $content->id === $currentContent->id;
        });

        $previousContent = $currentIndex > 0 ? $contents[$currentIndex - 1] : null;
        $nextContent = $currentIndex < $contents->count() - 1 ? $contents[$currentIndex + 1] : null;

        // Calculate progress
        $totalContents = $contents->count();
        $completedContents = $contents->where('is_completed', true)->count();
        $progressPercentage = $totalContents > 0 
            ? round(($completedContents / $totalContents) * 100) 
            : 0;

        return view('student.my-course.show', compact(
            'course',
            'contents',
            'currentContent',
            'previousContent',
            'nextContent',
            'totalContents',
            'completedContents',
            'progressPercentage'
        ));
    }
}