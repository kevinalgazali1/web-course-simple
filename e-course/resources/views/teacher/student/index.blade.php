<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Student Terdaftar
                </h2>
                <p class="text-sm text-gray-600 mt-1">Pantau progress student di course Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Student</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</h3>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Student Aktif</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $activeStudents }}</h3>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Avg Progress</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $averageProgress }}%</h3>
                        </div>
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Course Aktif</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $activeCourses }}</h3>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <!-- Filter Bar -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <form method="GET" action="{{ route('students.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Cari nama student...">
                            </div>
                        </div>
                        <div class="w-full md:w-48">
                            <select name="course" class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Semua Course</option>
                                @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ request('course') == $c->id ? 'selected' : '' }}>{{ $c->nama_course }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-48">
                            <select name="progress" class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Semua Progress</option>
                                <option value="completed" {{ request('progress') == 'completed' ? 'selected' : '' }}>100% Selesai</option>
                                <option value="in_progress" {{ request('progress') == 'in_progress' ? 'selected' : '' }}>Sedang Belajar</option>
                                <option value="not_started" {{ request('progress') == 'not_started' ? 'selected' : '' }}>Belum Mulai</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none transition">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'course', 'progress']))
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Students List -->
                <div class="p-6">
                    @if($students->count() > 0)
                    <div class="space-y-4">
                        @foreach($students as $student)
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-purple-300 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <!-- Student Info -->
                                <div class="flex items-center flex-1">
                                    <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-full flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-xl">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $student->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                    </div>
                                </div>

                                <!-- Overall Stats -->
                                <div class="text-right">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                        {{ $student->overall_progress >= 80 ? 'bg-green-100 text-green-800' : 
                                           ($student->overall_progress >= 50 ? 'bg-yellow-100 text-yellow-800' : 
                                           ($student->overall_progress > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $student->overall_progress }}% Overall
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $student->enrolled_courses_count }} course</p>
                                </div>
                            </div>

                            <!-- Course Progress List -->
                            <div class="space-y-3 mt-4 pt-4 border-t border-gray-200">
                                @forelse($student->studentCourses as $enrollment)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center flex-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-800 mr-3">
                                                {{ $enrollment->kategori->kategori }}
                                            </span>
                                            <h4 class="font-semibold text-gray-900">{{ $enrollment->nama_course }}</h4>
                                        </div>
                                        <span class="text-sm font-bold text-purple-600">{{ $enrollment->progress_percentage }}%</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $enrollment->progress_percentage }}%">
                                        </div>
                                    </div>

                                    <!-- Progress Details -->
                                    <div class="flex items-center justify-between text-xs text-gray-600">
                                        <div class="flex items-center gap-4">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $enrollment->completed_contents }} / {{ $enrollment->total_contents }} materi
                                            </span>
                                            @if($enrollment->progress_percentage == 100)
                                            <span class="inline-flex items-center text-green-600 font-semibold">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Selesai
                                            </span>
                                            @elseif($enrollment->progress_percentage > 0)
                                            <span class="text-blue-600 font-semibold">Sedang Belajar</span>
                                            @else
                                            <span class="text-gray-500">Belum Mulai</span>
                                            @endif
                                        </div>
                                        <span>Terdaftar {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4 text-sm text-gray-500">
                                    Student belum mengikuti course Anda
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($students->hasPages())
                    <div class="mt-6">
                        {{ $students->links() }}
                    </div>
                    @endif
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada student terdaftar</h3>
                        <p class="text-gray-500">Student akan muncul di sini setelah mendaftar di course Anda</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>