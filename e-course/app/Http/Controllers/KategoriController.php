<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kategori::withCount('courses');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where('kategori', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10);
        
        // Total courses across all kategori
        $totalCourses = \App\Models\Course::count();
        
        // Most popular category
        $mostPopularCategory = Kategori::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->first();

        return view('admin.kategori.index', compact(
            'categories',
            'totalCourses',
            'mostPopularCategory'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255|unique:kategoris,kategori',
        ], [
            'kategori.required' => 'Nama kategori wajib diisi',
            'kategori.unique' => 'Kategori sudah ada',
            'kategori.max' => 'Nama kategori maksimal 255 karakter',
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:255|unique:kategoris,kategori,' . $kategori->id,
        ], [
            'kategori.required' => 'Nama kategori wajib diisi',
            'kategori.unique' => 'Kategori sudah ada',
            'kategori.max' => 'Nama kategori maksimal 255 karakter',
        ]);

        $kategori->update($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $category)
    {
        // Check if category has courses
        if ($category->courses()->count() > 0) {
            return redirect()
                ->route('kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki course!');
        }

        $category->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}