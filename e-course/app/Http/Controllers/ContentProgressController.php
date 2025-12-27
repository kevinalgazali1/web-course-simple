<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentProgress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContentProgressController extends Controller
{
    /**
     * Mark content as completed
     */
    public function complete(Content $content)
    {
        $user = Auth::user();

        // Check if student is enrolled in the course
        if (!$user->studentCourses()->where('course_id', $content->course_id)->exists()) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di course ini.');
        }

        // Create or update progress
        $progress = ContentProgress::updateOrCreate(
            [
                'student_id' => $user->id,
                'content_id' => $content->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        // Get next content
        $nextContent = Content::where('course_id', $content->course_id)
            ->where('id', '>', $content->id)
            ->orderBy('id')
            ->first();

        // Redirect to next content or back to current
        if ($nextContent) {
            return redirect()
                ->route('student.my-courses.show', ['course' => $content->course_id, 'content' => $nextContent->id])
                ->with('success', 'Materi berhasil diselesaikan! Lanjut ke materi berikutnya.');
        }

        // If this is the last content
        return redirect()
            ->route('my-courses.show', ['course' => $content->course_id, 'content' => $content->id])
            ->with('success', 'Selamat! Anda telah menyelesaikan semua materi di course ini! 🎉');
    }
}