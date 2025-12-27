<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Kursus LearnVel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            scroll-behavior: smooth;
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        LearnVel
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-purple-600 font-medium transition">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-lg transition">
                        Daftar Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-float"
                style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                        <span class="text-sm font-semibold">🎉 Platform Nomor 1 untuk Belajar</span>
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Kuasai Pelajaran dengan Cara yang Menyenangkan
                    </h1>
                    <p class="text-xl text-purple-100 mb-8">
                        Belajar dengan instruktur berpengalaman dan kurikulum terstruktur.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#courses"
                            class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:shadow-2xl transition text-center">
                            Mulai Belajar
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 shadow-2xl">
                            <div class="space-y-4">
                                <div class="h-4 bg-white/30 rounded w-3/4"></div>
                                <div class="h-4 bg-white/30 rounded w-1/2"></div>
                                <div class="h-40 bg-white/20 rounded-lg mt-6"></div>
                                <div class="flex space-x-2 mt-4">
                                    <div class="h-12 bg-white/30 rounded flex-1"></div>
                                    <div class="h-12 bg-white/30 rounded flex-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses Section -->
    @if ($popularCourses->count() > 0)
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">🔥 Course Paling Populer</h2>
                    <p class="text-gray-600">Dipilih oleh banyak siswa</p>
                </div>

                <div class="grid md:grid-cols-5 gap-6">
                    @foreach ($popularCourses as $course)
                        <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition cursor-pointer"
                            onclick="openModal({{ $course->id }})">
                            <div
                                class="w-16 h-16 mx-auto mb-3 rounded-lg bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center">
                                @if ($course->gambar)
                                    <img src="{{ asset('storage/' . $course->gambar) }}"
                                        alt="{{ $course->nama_course }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                            <h4 class="font-semibold text-sm text-gray-900 text-center mb-2 line-clamp-2">
                                {{ $course->nama_course }}</h4>
                            <div class="flex items-center justify-center text-xs text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="font-semibold">{{ $course->students_count }} siswa</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Courses Section with Search & Filter -->
    <section id="courses" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Jelajahi Semua Course</h2>
                <p class="text-xl text-gray-600">Temukan course yang sesuai dengan kebutuhanmu</p>
            </div>

            <!-- Search & Filter Bar -->
            <form method="GET" action="{{ route('welcome') }}" class="mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Cari course...">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select name="kategori"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <select name="sort"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    Terpopuler</option>
                                <option value="most_content"
                                    {{ request('sort') == 'most_content' ? 'selected' : '' }}>Terbanyak Materi</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $courses->count() }} course
                            @if (request('search') || request('kategori'))
                                dari hasil pencarian
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-purple-700 transition">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari
                            </button>
                            @if (request()->hasAny(['search', 'kategori', 'sort']))
                                <a href="{{ route('welcome') }}"
                                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <!-- Courses Grid -->
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($courses as $course)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                        <!-- Course Image -->
                        <div
                            class="h-48 bg-gradient-to-r from-purple-400 to-indigo-600 flex items-center justify-center overflow-hidden">
                            @if ($course->gambar)
                                <img src="{{ asset('storage/' . $course->gambar) }}"
                                    alt="{{ $course->nama_course }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @endif
                        </div>

                        <div class="p-6">
                            <!-- Category Badge -->
                            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $course->kategori->kategori }}
                            </span>

                            <!-- Course Title -->
                            <h3 class="text-xl font-bold text-gray-900 mt-4 mb-2">{{ $course->nama_course }}</h3>

                            <!-- Description -->
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($course->deskripsi, 100) }}</p>

                            <!-- Stats -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $course->students_count ?? 0 }}
                                        siswa</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $course->contents_count ?? 0 }}
                                        materi</span>
                                </div>
                            </div>

                            <!-- Teacher Info -->
                            <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-white font-bold text-xs">{{ strtoupper(substr($course->teacher->name ?? 'N', 0, 1)) }}</span>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs text-gray-500">Pengajar</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $course->teacher->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button onclick="openModal({{ $course->id }})"
                                class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-purple-700 transition">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kursus tersedia</h3>
                        <p class="text-gray-500">Kursus akan segera hadir</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Modal Detail Course (sama seperti sebelumnya) -->
    @foreach ($courses->merge($popularCourses)->unique('id') as $course)
        <div id="modal-{{ $course->id }}" class="modal">
            <div class="modal-content bg-white rounded-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <!-- Modal content sama seperti sebelumnya -->
                <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 rounded-t-2xl">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <span
                                class="inline-block bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-2">
                                {{ $course->kategori->kategori }}
                            </span>
                            <h2 class="text-2xl font-bold mb-2">{{ $course->nama_course }}</h2>
                            <div class="flex items-center text-purple-100">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm">{{ $course->teacher->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <button onclick="closeModal({{ $course->id }})"
                            class="text-white hover:bg-white/20 rounded-full p-2 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if ($course->gambar)
                        <div class="mb-6 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $course->gambar) }}" alt="{{ $course->nama_course }}"
                                class="w-full h-64 object-cover">
                        </div>
                    @endif

                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-2xl font-bold text-gray-900">{{ $course->students_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Siswa</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-2xl font-bold text-gray-900">{{ $course->contents_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Materi</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <svg class="w-8 h-8 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($course->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($course->tanggal_selesai)) }}
                            </p>
                            <p class="text-xs text-gray-600">Hari</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Course</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $course->deskripsi }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <a href="{{ route('login') }}"
                            class="block w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-center px-8 py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition">
                            Ikuti Course - Login Sekarang
                        </a>
                        <p class="text-center text-sm text-gray-500 mt-3">
                            Belum punya akun? <a href="{{ route('register') }}"
                                class="text-purple-600 font-semibold hover:underline">Daftar gratis</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Siap Memulai Perjalanan Belajarmu?</h2>
            <p class="text-xl text-purple-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan siswa yang telah meningkatkan pengetahuan mereka bersama kami
            </p>
            <a href="{{ route('register') }}"
                class="inline-block bg-white text-purple-600 px-10 py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition">
                Daftar Sekarang - Gratis!
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-6">
        <div class="text-center text-sm">
            <p>&copy; 2025 LearnVel. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        function openModal(courseId) {
            const modal = document.getElementById(`modal-${courseId}`);
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(courseId) {
            const modal = document.getElementById(`modal-${courseId}`);
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('.modal.active');
                modals.forEach(modal => {
                    modal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }
        });
    </script>
</body>

</html>
