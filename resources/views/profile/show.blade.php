<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100 font-roboto">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Profil Pengguna</h1>
            <p class="text-lg text-gray-600">Kelola informasi akun Anda dengan mudah.</p>
        </div>

        <!-- Main Profile Card -->
        <div
            class="bg-white rounded-md shadow-md overflow-hidden border border-gray-300 outline outline-1 outline-gray-400 relative">
            <!-- Close Button -->
            <div class="absolute top-4 right-4 z-10">
                <a href="{{ route('home') }}"
                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors duration-200 border border-gray-300">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            <!-- Header -->
            <div class="h-32 bg-gray-200"></div>

            <!-- Profile Content -->
            <div class="px-6 pb-8">
                <!-- Avatar Section -->
                <div class="flex justify-center -mt-16">
                    <div class="relative">
                        <div
                            class="w-24 h-24 bg-gray-500 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                            <span class="text-2xl font-bold text-white">
                                @php
                                    $nameParts = explode(' ', trim($user->name));
                                    $initials =
                                        count($nameParts) >= 2
                                            ? strtoupper(
                                                substr($nameParts[0], 0, 1) .
                                                    substr($nameParts[count($nameParts) - 1], 0, 1),
                                            )
                                            : strtoupper(substr($user->name, 0, 2));
                                @endphp
                                {{ $initials }}
                            </span>
                            <!-- Status Indicator -->
                            <div
                                class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Name & Email -->
                <div class="text-center mt-4 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <span
                        class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        Online
                    </span>
                </div>

                <!-- Info Cards Grid -->
                <div class="grid md:grid-cols-3 gap-4 mb-8">
                    <!-- Name Card -->
                    <div
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 outline outline-1 outline-gray-300">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-500 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="text-base font-medium text-gray-800">{{ $user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 outline outline-1 outline-gray-300">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-500 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Address</p>
                                <p class="text-base font-medium text-gray-800 break-all">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 outline outline-1 outline-gray-300">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-500 rounded-md flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status Akun</p>
                                <p class="text-base font-medium text-gray-800">Terverifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg text-center shadow-sm border border-gray-200 outline outline-1 outline-gray-300">
                        <div class="w-8 h-8 bg-gray-500 rounded-md mx-auto mb-2 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">Bergabung</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</p>
                    </div>

                    <div
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg text-center shadow-sm border border-gray-200 outline outline-1 outline-gray-300">
                        <div class="w-8 h-8 bg-gray-500 rounded-md mx-auto mb-2 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">Last Login</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : ' transférés N/A' }}</p>
                    </div>

                    <a href="{{ route('keranjang.index') }}"
                        class="transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-md bg-gray-50 p-4 rounded-lg text-center shadow-sm border border-gray-200 outline outline-1 outline-gray-300 block">
                        <div class="w-8 h-8 bg-gray-500 rounded-md mx-auto mb-2 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">Keranjang</p>
                        <p class="text-sm font-medium text-gray-800">Lihat</p>
                    </a>
                </div>

                <!-- Action Button -->
                <div class="flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md font-medium transition-all duration-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Interactions -->
    <script>
        $(document).ready(function() {
            $('.transition-all').hover(
                function() {
                    $(this).addClass('-translate-y-1 shadow-md');
                },
                function() {
                    $(this).removeClass('-translate-y-1 shadow-md');
                }
            );
        });
    </script>
</body>

</html>
