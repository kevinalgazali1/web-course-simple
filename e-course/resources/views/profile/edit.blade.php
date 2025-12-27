<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Profil Saya
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola informasi akun dan keamanan Anda</p>
                </div>
            </div>
            <div class="hidden sm:block">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                    {{ Auth::user()->role === 'admin'
                        ? 'bg-red-100 text-red-800'
                        : (Auth::user()->role === 'teacher'
                            ? 'bg-blue-100 text-blue-800'
                            : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Sidebar - User Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 sticky top-6">
                        <div class="text-center">
                            <!-- Avatar -->
                            <div
                                class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                <span
                                    class="text-white font-bold text-4xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>

                            <!-- User Info -->
                            <h3 class="mt-4 text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Auth::user()->email }}</p>

                            <!-- Role Badge -->
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                                    {{ Auth::user()->role === 'admin'
                                        ? 'bg-red-100 text-red-800'
                                        : (Auth::user()->role === 'teacher'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-green-100 text-green-800') }}">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>

                            <!-- Account Stats -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="space-y-3 text-left">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600">Bergabung sejak</span>
                                        <span
                                            class="ml-auto font-semibold text-gray-900">{{ Auth::user()->created_at?->format('M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600">Terakhir update</span>
                                        <span
                                            class="ml-auto font-semibold text-gray-900">{{ Auth::user()->updated_at?->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Update Profile Information -->
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                            <div class="flex items-center">
                                <div class="bg-purple-100 rounded-lg p-3 mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Informasi Profil</h3>
                                    <p class="text-sm text-gray-600 mt-1">Perbarui informasi profil dan alamat email
                                        Anda</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-cyan-50">
                            <div class="flex items-center">
                                <div class="bg-blue-100 rounded-lg p-3 mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Keamanan Akun</h3>
                                    <p class="text-sm text-gray-600 mt-1">Perbarui password untuk menjaga keamanan akun
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    @if (Auth::user()->role !== 'admin')
                        <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border-2 border-red-200">
                            <div class="p-6 border-b border-red-200 bg-gradient-to-r from-red-50 to-pink-50">
                                <div class="flex items-center">
                                    <div class="bg-red-100 rounded-lg p-3 mr-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-red-900">Zona Berbahaya</h3>
                                        <p class="text-sm text-red-600 mt-1">Tindakan ini bersifat permanen dan tidak
                                            dapat dibatalkan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
