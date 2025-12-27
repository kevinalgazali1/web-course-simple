<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ContentProgressController;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/student/courses', [StudentController::class, 'courses'])->name('student.courses');
    Route::post('/student/courses/{course}', [StudentController::class, 'enroll'])->name('student.enroll');
    Route::get('my-courses', [MyCourseController::class, 'index'])->name('my-courses.index');
    Route::get('my-courses/{course}', [MyCourseController::class, 'show'])->name('my-courses.show');

    // Content Progress
    Route::post('contents/{content}/complete', [ContentProgressController::class, 'complete'])->name('contents.complete');
});
