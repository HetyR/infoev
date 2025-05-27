<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out; }
        .animate-bounce-gentle { animation: bounceGentle 2s infinite; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .card-hover {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .avatar-glow {
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, 0px); }
        }
        
        .pulse-ring {
            animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.33); }
            80%, 100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-gray-100 relative overflow-hidden">
        <!-- Background Animated Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-bounce-gentle"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4 py-8 max-w-6xl">
            <!-- Header Section -->
            <div class="text-center mb-12 animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-800 mb-4">
                    Profil Pengguna
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Selamat datang di dashboard profil Anda. Kelola dan pantau informasi akun dengan mudah.
                </p>
            </div>

            <!-- Main Profile Card -->
            <div class="glass-effect rounded-3xl overflow-hidden shadow-2xl animate-scale-in">
                <!-- Header with Parallax Effect -->
                <div class="relative h-48 md:h-64 bg-gradient-to-r from-green-500 via-green-600 to-green-700 overflow-hidden">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    
                    <!-- Floating Particles -->
                    <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white rounded-full animate-ping"></div>
                    <div class="absolute top-3/4 right-1/4 w-3 h-3 bg-yellow-300 rounded-full floating"></div>
                    <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-pink-300 rounded-full animate-pulse"></div>
                </div>

                <!-- Profile Content -->
                <div class="relative px-6 md:px-12 pb-12">
                    <!-- Avatar Section -->
                    <div class="flex justify-center -mt-24 md:-mt-32 mb-8">
                        <div class="relative">
                            <!-- Pulse Ring -->
                            <div class="absolute inset-0 w-40 h-40 md:w-48 md:h-48 rounded-full bg-gradient-to-r from-green-400 to-green-500 pulse-ring"></div>
                            
                            <!-- Main Avatar -->
                            <div class="relative w-40 h-40 md:w-48 md:h-48 bg-gradient-to-r from-green-500 via-green-600 to-green-700 rounded-full flex items-center justify-center shadow-2xl border-4 border-white avatar-glow floating">
                                <span class="text-4xl md:text-5xl font-bold text-white">
                                    @php
                                        $nameParts = explode(' ', trim($user->name));
                                        $initials = '';
                                        if (count($nameParts) >= 2) {
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($user->name, 0, 2));
                                        }
                                    @endphp
                                    {{ $initials }}
                                </span>
                                
                                <!-- Status Indicator -->
                                <div class="absolute bottom-4 right-4 w-6 h-6 bg-green-500 rounded-full border-3 border-white flex items-center justify-center">
                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Name & Title -->
                    <div class="text-center mb-12 animate-slide-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">{{ $user->name }}</h2>
                        <p class="text-lg text-gray-600 mb-4">{{ $user->email }}</p>
                        <div class="inline-flex items-center px-4 py-2 bg-green-500/20 backdrop-blur-sm rounded-full border border-green-400/30">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-green-700 font-medium">Online</span>
                        </div>
                    </div>

                    <!-- Info Cards Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        <!-- Name Card -->
                        <div class="card-hover glass-effect p-6 rounded-2xl border border-white/20 animate-slide-up" style="animation-delay: 0.1s">
                            <div class="flex items-center">
                                <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Nama Lengkap</p>
                                    <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="card-hover glass-effect p-6 rounded-2xl border border-white/20 animate-slide-up" style="animation-delay: 0.2s">
                            <div class="flex items-center">
                                <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Email Address</p>
                                    <p class="text-lg font-semibold text-gray-800 break-all">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Card -->
                        <div class="card-hover glass-effect p-6 rounded-2xl border border-white/20 animate-slide-up" style="animation-delay: 0.3s">
                            <div class="flex items-center">
                                <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Status Akun</p>
                                    <p class="text-lg font-semibold text-gray-800">Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
                        <div class="glass-effect p-4 rounded-xl text-center border border-white/20 card-hover animate-slide-up" style="animation-delay: 0.4s">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Bergabung</p>
                            <p class="text-gray-800 font-bold">{{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</p>
                        </div>

                        <div class="glass-effect p-4 rounded-xl text-center border border-white/20 card-hover animate-slide-up" style="animation-delay: 0.5s">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Last Login</p>
                            <p class="text-gray-800 font-bold text-xs">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</p>
                        </div>

                        <div class="glass-effect p-4 rounded-xl text-center border border-white/20 card-hover animate-slide-up" style="animation-delay: 0.6s">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Points</p>
                            <p class="text-gray-800 font-bold">1,250</p>
                        </div>

                        <div class="glass-effect p-4 rounded-xl text-center border border-white/20 card-hover animate-slide-up" style="animation-delay: 0.7s">
                            <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 font-medium">Level</p>
                            <p class="text-gray-800 font-bold">Pro</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 animate-slide-up" style="animation-delay: 0.8s">
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Interactions -->
    <script>
        $(document).ready(function() {
            // Smooth scroll and parallax effects
            $(window).scroll(function() {
                var scrolled = $(this).scrollTop();
                $('.floating').css('transform', 'translateY(' + (scrolled * 0.1) + 'px)');
            });

            // Button click animations
            $('#editBtn, #settingsBtn').click(function() {
                $(this).addClass('animate-pulse');
                setTimeout(() => {
                    $(this).removeClass('animate-pulse');
                }, 600);
            });

            // Card hover sound effect (optional)
            $('.card-hover').hover(
                function() {
                    $(this).css('transform', 'translateY(-8px) scale(1.02)');
                },
                function() {
                    $(this).css('transform', 'translateY(0) scale(1)');
                }
            );

            // Loading animation delay for cards
            $('.animate-slide-up').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });

            // Dynamic gradient animation for avatar
            setInterval(function() {
                $('.avatar-glow').toggleClass('shadow-2xl shadow-purple-500/50');
            }, 2000);

            // Random floating particles
            function createParticle() {
                const particle = $('<div class="absolute w-1 h-1 bg-white rounded-full opacity-70"></div>');
                const randomX = Math.random() * window.innerWidth;
                const randomY = Math.random() * window.innerHeight;
                
                particle.css({
                    left: randomX + 'px',
                    top: randomY + 'px',
                    animation: 'fadeIn 3s ease-out forwards'
                });
                
                $('body').append(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, 3000);
            }

            // Create particles every 5 seconds
            setInterval(createParticle, 5000);
        });
    </script>
</body>
</html>
