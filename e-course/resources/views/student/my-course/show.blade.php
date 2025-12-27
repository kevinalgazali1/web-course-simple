<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $course->nama_course }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $course->kategori->kategori }}</p>
            </div>
            <a href="{{ route('my-courses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Current Content Display -->
                    @if($currentContent)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                            <h3 class="text-2xl font-bold mb-2">{{ $currentContent->judul }}</h3>
                            <div class="flex items-center text-purple-100 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Materi {{ $currentContent->order }} dari {{ $totalContents }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Content Body -->
                            <div class="prose max-w-none mb-6">
                                <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $currentContent->deskripsi }}</div>
                            </div>

                            <!-- Mark as Done Button -->
                            @if($currentContent->is_completed)
                            <div class="flex items-center justify-between p-4 bg-green-50 border-l-4 border-green-500 rounded">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-green-800">Materi Selesai</p>
                                        <p class="text-sm text-green-600">Diselesaikan pada {{ $currentContent->completed_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <form action="{{ route('contents.complete', $currentContent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-green-500 to-teal-500 text-white text-lg font-bold rounded-lg hover:from-green-600 hover:to-teal-600 transition shadow-lg hover:shadow-xl">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tandai Selesai & Lanjut
                                </button>
                            </form>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="flex gap-3 mt-4">
                                @if($previousContent)
                                <a href="{{ route('my-courses.show', ['course' => $course->id, 'content' => $previousContent->id]) }}" class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Materi Sebelumnya
                                </a>
                                @endif

                                @if($nextContent)
                                <a href="{{ route('my-courses.show', ['course' => $course->id, 'content' => $nextContent->id]) }}" class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                                    Materi Selanjutnya
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- No Content Selected -->
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pilih Materi</h3>
                        <p class="text-gray-600">Pilih materi dari daftar di samping untuk mulai belajar</p>
                    </div>
                    @endif
                </div>

                <!-- Sidebar - Content List -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-6">
                        <!-- Course Progress -->
                        <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
                            <h4 class="text-lg font-bold mb-3">Progress Course</h4>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm">Penyelesaian</span>
                                <span class="text-xl font-bold">{{ $progressPercentage }}%</span>
                            </div>
                            <div class="w-full bg-white/30 rounded-full h-2 overflow-hidden">
                                <div class="bg-white h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            <p class="text-sm text-purple-100 mt-2">{{ $completedContents }} / {{ $totalContents }} materi selesai</p>
                        </div>

                        <!-- Content List -->
                        <div class="p-4">
                            <h5 class="font-bold text-gray-900 mb-3 px-2">Daftar Materi</h5>
                            <div class="space-y-2 max-h-[600px] overflow-y-auto">
                                @foreach($contents as $index => $content)
                                <a href="{{ route('my-courses.show', ['course' => $course->id, 'content' => $content->id]) }}" 
                                   class="block p-3 rounded-lg border-2 transition-all {{ $currentContent && $currentContent->id == $content->id ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300 hover:bg-gray-50' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($content->is_completed)
                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @else
                                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                                {{ $index + 1 }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $content->judul }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                @if($content->is_completed)
                                                <span class="text-green-600 font-semibold">✓ Selesai</span>
                                                @else
                                                <span>Belum selesai</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="p-4 border-t border-gray-200 bg-gray-50">
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $course->teacher->name }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($course->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($course->tanggal_selesai)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>