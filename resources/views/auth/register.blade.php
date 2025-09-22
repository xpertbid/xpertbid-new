<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - XpertBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
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
        <!-- Main Register Card -->
        <div class="glass-effect rounded-3xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4">
                    <i class="fas fa-user-plus text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Join XpertBid</h1>
                <p class="text-white text-opacity-80">Create your account and start bidding</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                @csrf
                
                <!-- Name Field -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-white text-opacity-60"></i>
                    </div>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full pl-12 pr-4 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-white placeholder-opacity-60 input-focus focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                           placeholder="Full name"
                           required>
                </div>

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
                           placeholder="Email address"
                           required>
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
                           placeholder="Password"
                           required>
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-white text-opacity-60 hover:text-opacity-80 transition-all">
                        <i class="fas fa-eye" id="password-toggle"></i>
                    </button>
                </div>

                <!-- Confirm Password Field -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-white text-opacity-60"></i>
                    </div>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           class="w-full pl-12 pr-12 py-4 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl text-white placeholder-white placeholder-opacity-60 input-focus focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                           placeholder="Confirm password"
                           required>
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-white text-opacity-60 hover:text-opacity-80 transition-all">
                        <i class="fas fa-eye" id="password_confirmation-toggle"></i>
                    </button>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input type="checkbox" 
                           name="terms" 
                           id="terms"
                           class="w-4 h-4 text-blue-600 bg-white bg-opacity-10 border-white border-opacity-20 rounded focus:ring-blue-500 focus:ring-2 mt-1"
                           required>
                    <label for="terms" class="ml-3 text-sm text-white text-opacity-80">
                        I agree to the <a href="#" class="text-white hover:text-opacity-100 transition-all">Terms of Service</a> 
                        and <a href="#" class="text-white hover:text-opacity-100 transition-all">Privacy Policy</a>
                    </label>
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

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-white text-gray-800 py-4 rounded-xl font-semibold btn-hover focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 relative overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-500 opacity-0 hover:opacity-10 transition-opacity"></div>
                </button>

                <!-- Social Login -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white border-opacity-20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-white text-opacity-60">Or sign up with</span>
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
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-white hover:text-opacity-80 transition-all font-semibold">
                        Sign in here
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle');
            
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
