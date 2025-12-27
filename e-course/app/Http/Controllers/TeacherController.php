<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Models\ContentProgress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();

        // Statistics
        $totalCourses = Course::where('teacher_id', $teacherId)->count();
        
        $activeCourses = Course::where('teacher_id', $teacherId)
            ->where('is_active', true)
            ->count();
        
        // Total students across all teacher's courses
        $totalStudents = DB::table('course_student')
            ->join('courses', 'course_student.course_id', '=', 'courses.id')
            ->where('courses.teacher_id', $teacherId)
            ->distinct('course_student.student_id')
            ->count('course_student.student_id');
        
        // Total contents across all teacher's courses
        $totalContents = Content::whereHas('course', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();

        // Recent Courses (5 latest with students and contents count)
        $recentCourses = Course::where('teacher_id', $teacherId)
            ->withCount(['students', 'contents'])
            ->latest()
            ->take(5)
            ->get();

        // Courses grouped by category
        $coursesByCategory = Course::where('teacher_id', $teacherId)
            ->select('kategori_id')
            ->with('kategori:id,kategori')
            ->get()
            ->groupBy('kategori_id')
            ->map(function($courses) {
                return (object)[
                    'kategori' => $courses->first()->kategori->kategori,
                    'courses_count' => $courses->count()
                ];
            })
            ->values();

        return view('teacher.dashboard', compact(
            'totalCourses',
            'activeCourses',
            'totalStudents',
            'totalContents',
            'recentCourses',
            'coursesByCategory'
        ));
    }

    public function student(Request $request)
    {
        $teacher = Auth::user();

        // Get teacher's courses
        $courses = Course::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->orderBy('nama_course')
            ->get();

        // Get all students enrolled in teacher's courses
        $query = User::where('role', 'student')
            ->whereHas('studentCourses', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->with(['studentCourses' => function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)
                  ->with(['kategori', 'contents']);
            }]);

        // Search by student name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by specific course
        if ($request->course) {
            $query->whereHas('studentCourses', function ($q) use ($request) {
                $q->where('courses.id', $request->course);
            });
        }

        $students = $query->paginate(10);

        // Calculate progress for each student
        foreach ($students as $student) {
            $enrolledCourses = $student->studentCourses;
            $totalProgress = 0;
            $courseCount = 0;

            foreach ($enrolledCourses as $course) {
                $totalContents = $course->contents->count();
                
                if ($totalContents > 0) {
                    $completedContents = ContentProgress::whereIn('content_id', $course->contents->pluck('id'))
                        ->where('student_id', $student->id)
                        ->where('is_completed', true)
                        ->count();

                    $progressPercentage = round(($completedContents / $totalContents) * 100);
                    
                    $course->progress_percentage = $progressPercentage;
                    $course->completed_contents = $completedContents;
                    $course->total_contents = $totalContents;
                    $course->enrolled_at = DB::table('course_student')
                        ->where('course_id', $course->id)
                        ->where('student_id', $student->id)
                        ->value('created_at');

                    $totalProgress += $progressPercentage;
                    $courseCount++;
                } else {
                    $course->progress_percentage = 0;
                    $course->completed_contents = 0;
                    $course->total_contents = 0;
                }
            }

            $student->overall_progress = $courseCount > 0 ? round($totalProgress / $courseCount) : 0;
            $student->enrolled_courses_count = $enrolledCourses->count();
        }

        // Filter by progress
        if ($request->progress) {
            $students = $students->filter(function ($student) use ($request) {
                if ($request->progress == 'completed') {
                    return $student->overall_progress == 100;
                } elseif ($request->progress == 'in_progress') {
                    return $student->overall_progress > 0 && $student->overall_progress < 100;
                } elseif ($request->progress == 'not_started') {
                    return $student->overall_progress == 0;
                }
                return true;
            });
        }

        // Statistics
        $totalStudents = User::where('role', 'student')
            ->whereHas('studentCourses', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->count();

        $activeStudents = User::where('role', 'student')
            ->whereHas('studentCourses', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->has('studentCourses')
            ->count();

        // Calculate average progress
        $allStudents = User::where('role', 'student')
            ->whereHas('studentCourses', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->with(['studentCourses' => function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->with('contents');
            }])
            ->get();

        $totalProgressSum = 0;
        $studentCount = 0;

        foreach ($allStudents as $student) {
            foreach ($student->studentCourses as $course) {
                $totalContents = $course->contents->count();
                if ($totalContents > 0) {
                    $completedContents = ContentProgress::whereIn('content_id', $course->contents->pluck('id'))
                        ->where('student_id', $student->id)
                        ->where('is_completed', true)
                        ->count();
                    $totalProgressSum += round(($completedContents / $totalContents) * 100);
                    $studentCount++;
                }
            }
        }

        $averageProgress = $studentCount > 0 ? round($totalProgressSum / $studentCount) : 0;
        $activeCourses = $courses->count();

        return view('teacher.student.index', compact(
            'students',
            'courses',
            'totalStudents',
            'activeStudents',
            'averageProgress',
            'activeCourses'
        ));
    }
}
