<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.dashboard');
    Route::get('/teacher/students', [TeacherController::class, 'student'])->name('students.index');
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::resource('courses', CourseController::class);
        Route::controller(ContentController::class)->group(function () {
            Route::get('courses/{course}/contents/create', 'create')->name('contents.create');
            Route::post('courses/{course}/contents', 'store')->name('contents.store');
            Route::get('courses/{course}/contents/{content}/edit', 'edit')->name('contents.edit');
            Route::put('courses/{course}/contents/{content}', 'update')->name('contents.update');
            Route::delete('courses/{course}/contents/{content}', 'destroy')->name('contents.destroy');
        });
    });
});