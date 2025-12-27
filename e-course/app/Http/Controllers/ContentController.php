<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $this->checkTeacherAssignment($course);

        return view('teacher.content.create', compact('course'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->checkTeacherAssignment($course);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'judul.required' => 'Judul konten wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Konten materi wajib diisi',
        ]);

        $course->contents()->create($validated);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Konten berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Course $course, Content $content)
    {
        $this->checkTeacherAssignment($course);
        $this->checkContentBelongsToCourse($content, $course);

        return view('teacher.content.edit', compact('course', 'content'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, Course $course, Content $content)
    {
        $this->checkTeacherAssignment($course);
        $this->checkContentBelongsToCourse($content, $course);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'judul.required' => 'Judul konten wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Konten materi wajib diisi',
        ]);

        $content->update($validated);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Konten berhasil diperbarui!');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Course $course, Content $content)
    {
        $this->checkTeacherAssignment($course);
        $this->checkContentBelongsToCourse($content, $course);

        $content->delete();

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Konten berhasil dihapus!');
    }

    /**
     * Check if the authenticated teacher is assigned to this course
     * This checks the teacher_id field in the course, regardless of who created it
     */
    private function checkTeacherAssignment(Course $course)
    {
        // Check if current logged in teacher is the assigned teacher for this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course ini. Hanya teacher yang ditugaskan yang dapat mengelola konten.');
        }
    }

    /**
     * Check if content belongs to the course
     */
    private function checkContentBelongsToCourse(Content $content, Course $course)
    {
        if ($content->course_id !== $course->id) {
            abort(404, 'Konten tidak ditemukan dalam course ini.');
        }
    }
}