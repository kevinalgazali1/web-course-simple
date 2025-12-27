<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Course
            </h2>
            <div class="flex gap-2">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.courses.edit', $course->id) : route('teacher.courses.edit', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.courses.index') : route('teacher.courses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Course Header -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-6">
                <div class="relative">
                    <!-- Course Image -->
                    <div class="h-64 bg-gradient-to-br from-purple-400 to-indigo-600 overflow-hidden">
                        @if($course->gambar)
                        <img src="{{ asset('storage/' . $course->gambar) }}" alt="{{ $course->nama_course }}" class="w-full h-full object-cover">
                        @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-32 h-32 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        @endif
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-2 text-sm font-bold rounded-full shadow-lg {{ $course->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $course->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Category Badge -->
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $course->kategori->kategori }}
                                    </span>
                                </div>

                                <!-- Course Title -->
                                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $course->nama_course }}</h1>

                                <!-- Description -->
                                <p class="text-gray-600 mb-4">{{ $course->deskripsi }}</p>

                                <!-- Teacher Info -->
                                @if(Auth::user()->role === 'admin')
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold">{{ strtoupper(substr($course->teacher->name ?? 'N', 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-500">Pengajar</p>
                                        <p class="text-base font-semibold text-gray-900">{{ $course->teacher->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Meta Info -->
                                <div class="flex flex-wrap gap-6 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <span class="text-xs text-gray-500">Periode:</span>
                                            <p class="font-medium">{{ \Carbon\Carbon::parse($course->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($course->tanggal_selesai)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <div>
                                            <span class="text-xs text-gray-500">Siswa Terdaftar:</span>
                                            <p class="font-medium">{{ $course->students->count() }} Siswa</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <span class="text-xs text-gray-500">Total Konten:</span>
                                            <p class="font-medium">{{ $contents->count() }} Materi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Konten</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $contents->count() }}</h3>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $course->students->count() }}</h3>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Status Course</p>
                            <h3 class="text-2xl font-bold {{ $course->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $course->is_active ? 'Aktif' : 'Nonaktif' }}
                            </h3>
                        </div>
                        <div class="bg-{{ $course->is_active ? 'green' : 'red' }}-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-{{ $course->is_active ? 'green' : 'red' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($course->is_active)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contents List -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Daftar Konten Materi</h3>
                            <p class="text-sm text-gray-600 mt-1">Kelola materi pembelajaran untuk course ini</p>
                        </div>
                        @if(Auth::user()->role === 'teacher' && $course->teacher_id === Auth::id())
                        <a href="{{ route('teacher.contents.create', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-purple-700 hover:to-indigo-700 focus:outline-none transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Konten
                        </a>
                        @endif
                    </div>
                </div>

                @if($contents->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($contents as $index => $content)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start flex-1">
                                <!-- Number Badge -->
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-white font-bold">{{ $index + 1 }}</span>
                                </div>
                                
                                <!-- Content Info -->
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $content->judul }}</h4>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($content->konten, 200) }}</p>
                                    
                                    <!-- Meta Info -->
                                    <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Dibuat {{ $content->created_at->diffForHumans() }}
                                        </div>
                                        @if($content->updated_at != $content->created_at)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Diperbarui {{ $content->updated_at->diffForHumans() }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons (Only for Teacher) -->
                            @if(Auth::user()->role === 'teacher' && $course->teacher_id === Auth::id())
                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('teacher.contents.edit', [$course->id, $content->id]) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('teacher.contents.destroy', [$course->id, $content->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada konten materi</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan konten materi untuk course ini</p>
                    @if(Auth::user()->role === 'teacher' && $course->teacher_id === Auth::id())
                    <a href="{{ route('teacher.contents.create', $course->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Konten
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>