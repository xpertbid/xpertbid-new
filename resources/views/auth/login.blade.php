<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - XpertBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-10 rounded-full floating-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white opacity-10 rounded-full floating-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/4 w-60 h-60 bg-white opacity-5 rounded-full pulse-animation"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Main Login Card -->
        <div class="glass-effect rounded-3xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4">
                    <i class="fas fa-gem text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                <p class="text-white text-opacity-80">Sign in to your XpertBid account</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-white text-opacity-60"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           class="w-full pl-12 pr-4 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-white placeholder-opacity-60 input-focus focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                           placeholder="Enter your email"
                           required>
                    <div class="shimmer absolute inset-0 rounded-xl opacity-0"></div>
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-white text-opacity-60"></i>
                    </div>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full pl-12 pr-12 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-white placeholder-opacity-60 input-focus focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                           placeholder="Enter your password"
                           required>
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-white text-opacity-60 hover:text-opacity-80 transition-all">
                        <i class="fas fa-eye" id="password-toggle"></i>
                    </button>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-white text-opacity-80">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-blue-600 bg-white bg-opacity-10 border-white border-opacity-20 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="ml-2 text-sm">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-white text-opacity-80 hover:text-opacity-100 transition-all">
                        Forgot password?
                    </a>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 border-opacity-30 rounded-xl p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                            <div class="text-red-200 text-sm">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-white text-gray-800 py-4 rounded-xl font-semibold btn-hover focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 relative overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 opacity-0 hover:opacity-10 transition-opacity"></div>
                </button>

                <!-- Social Login -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white border-opacity-20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-white text-opacity-60">Or continue with</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" 
                            class="flex items-center justify-center py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white hover:bg-opacity-20 transition-all">
                        <i class="fab fa-google text-xl mr-2"></i>
                        Google
                    </button>
                    <button type="button" 
                            class="flex items-center justify-center py-3 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white hover:bg-opacity-20 transition-all">
                        <i class="fab fa-facebook text-xl mr-2"></i>
                        Facebook
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-white text-opacity-60">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-white hover:text-opacity-80 transition-all font-semibold">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-6">
            <p class="text-white text-opacity-60 text-sm">
                <i class="fas fa-shield-alt mr-2"></i>
                Your data is protected with enterprise-grade security
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add shimmer effect on focus
        document.querySelectorAll('.input-focus').forEach(input => {
            input.addEventListener('focus', function() {
                const shimmer = this.parentElement.querySelector('.shimmer');
                if (shimmer) {
                    shimmer.classList.remove('opacity-0');
                    setTimeout(() => shimmer.classList.add('opacity-0'), 2000);
                }
            });
        });

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add floating particles effect
            createFloatingParticles();
        });

        function createFloatingParticles() {
            const container = document.querySelector('.gradient-bg');
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute w-2 h-2 bg-white opacity-20 rounded-full';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animation = 'float 8s ease-in-out infinite';
                container.appendChild(particle);
            }
        }
    </script>
</body>
</html>
