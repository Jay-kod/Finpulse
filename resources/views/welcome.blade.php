<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Finpulse | Financial Sentiment Intelligence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-[#0B0F19] text-gray-300 relative overflow-x-hidden min-h-screen selection:bg-emerald-500/30 font-sans" x-data="{ mobileMenuOpen: false }">
    
    <!-- Background Effects -->
    <div class="fixed inset-0 bg-grid-pattern z-0 pointer-events-none opacity-50"></div>
    <div class="fixed top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-blue-600/20 rounded-full mix-blend-screen filter blur-[128px] animate-blob z-0 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40rem] h-[40rem] bg-emerald-500/20 rounded-full mix-blend-screen filter blur-[128px] animate-blob animation-delay-2000 z-0 pointer-events-none"></div>

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 bg-[#0B0F19]/80 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Left Side: Site Name & Logo -->
            <div class="flex items-center space-x-2 md:space-x-3 group cursor-pointer">
                <div class="relative shrink-0">
                    <div class="absolute inset-0 bg-emerald-500 blur opacity-40 group-hover:opacity-70 transition-opacity rounded-xl"></div>
                    <img src="{{ asset('finpulse-icon.png') }}" alt="Finpulse" class="relative w-8 h-8 md:w-10 md:h-10 rounded-xl ring-1 ring-white/20">
                </div>
                <span class="text-xl md:text-2xl font-black text-white tracking-tighter hidden sm:block">Finpulse<span class="text-emerald-400">.</span></span>
            </div>
            
            <!-- Right Side: Links & Auth Buttons -->
            <div class="flex items-center md:space-x-8">
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-sm font-semibold text-white transition-colors">Home</a>
                    <a href="#features" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">Platform</a>
                    <a href="#audience" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">Portals</a>
                    <a href="{{ route('pages.show', 'about') }}" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">About</a>
                </nav>
                <div class="flex items-center space-x-3 md:space-x-4">
                    <a href="{{ route('login') }}" target="_blank" class="text-xs md:text-sm font-bold text-gray-300 hover:text-white transition-colors whitespace-nowrap hidden min-[360px]:block">User Login</a>
                    <a href="{{ route('analyst.login') }}" target="_blank" class="relative inline-flex group shrink-0">
                        <div class="absolute transition-all duration-1000 opacity-70 -inset-px bg-gradient-to-r from-[#44BCFF] via-[#34D399] to-[#10B981] rounded-xl blur-lg group-hover:opacity-100 group-hover:-inset-1 group-hover:duration-200"></div>
                        <span class="relative inline-flex items-center justify-center px-3 py-1.5 md:px-5 md:py-2 text-xs md:text-sm font-bold text-white transition-all duration-200 bg-gray-900 border border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 whitespace-nowrap">
                            Analyst Portal
                        </span>
                    </a>
                    <!-- Hamburger Button -->
                    <button @click="mobileMenuOpen = true" class="md:hidden flex items-center justify-center p-1.5 text-gray-400 hover:text-white transition-colors focus:outline-none shrink-0 ml-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-16">
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 md:px-6 py-20 md:py-32 flex flex-col items-center text-center">
            <div class="inline-flex items-center space-x-2 bg-white/5 border border-white/10 rounded-full px-4 py-1.5 md:px-5 md:py-2 mb-8 md:mb-10 shadow-lg backdrop-blur-sm hover:bg-white/10 transition-colors cursor-pointer">
                <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs md:text-sm font-semibold text-gray-200">Finpulse 2.0 is now live</span>
            </div>
            <h1 class="text-5xl sm:text-6xl md:text-8xl font-black tracking-tighter text-white mb-6 md:mb-8 max-w-5xl leading-[1.1] md:leading-[1.1]">
                Decode the Market's <br class="hidden sm:block">
                <span class="bg-gradient-to-r from-blue-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">True Sentiment.</span>
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl text-gray-400 max-w-3xl mb-10 md:mb-12 font-medium leading-relaxed px-2">
                Transform thousands of unstructured financial app reviews into crystal clear, actionable intelligence using state-of-the-art NLP models.
            </p>
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6 w-full sm:w-auto px-4 sm:px-0">
                <a href="#audience" class="w-full sm:w-auto px-8 py-4 rounded-xl text-lg font-bold text-gray-900 bg-emerald-400 hover:bg-emerald-300 transition-all shadow-[0_0_40px_-10px_rgba(52,211,153,0.8)] hover:shadow-[0_0_60px_-15px_rgba(52,211,153,1)] transform hover:-translate-y-1 text-center">
                    Select Your Portal
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-xl text-lg font-bold text-white bg-white/5 border border-white/10 hover:bg-white/10 transition-all transform hover:-translate-y-1 backdrop-blur-sm text-center">
                    Explore Features
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="max-w-7xl mx-auto px-6 py-24 relative">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Intelligence at Scale</h2>
                <p class="text-gray-400 text-xl max-w-2xl mx-auto font-medium">Everything you need to monitor, analyze, and react to your users' feedback in real-time.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group bg-gray-900/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-10 hover:bg-gray-800/60 hover:border-white/10 transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-8 text-blue-400 group-hover:scale-110 transition-transform duration-300 group-hover:shadow-[0_0_30px_-5px_rgba(59,130,246,0.4)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Real-time Ingestion</h3>
                    <p class="text-gray-400 leading-relaxed font-medium">Continuously fetch and aggregate reviews from the App Store and Google Play directly into a unified, structured dashboard.</p>
                </div>
                <!-- Feature 2 -->
                <div class="group bg-gray-900/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-10 hover:bg-gray-800/60 hover:border-white/10 transition-all duration-300">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-8 text-emerald-400 group-hover:scale-110 transition-transform duration-300 group-hover:shadow-[0_0_30px_-5px_rgba(52,211,153,0.4)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">NLP Sentiment Engine</h3>
                    <p class="text-gray-400 leading-relaxed font-medium">Automatically classify feedback into positive, neutral, or negative sentiments and extract key topics with precision.</p>
                </div>
                <!-- Feature 3 -->
                <div class="group bg-gray-900/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-10 hover:bg-gray-800/60 hover:border-white/10 transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-8 text-purple-400 group-hover:scale-110 transition-transform duration-300 group-hover:shadow-[0_0_30px_-5px_rgba(168,85,247,0.4)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Actionable Analytics</h3>
                    <p class="text-gray-400 leading-relaxed font-medium">Visualize trends over time, identify sudden bug reports, and prioritize high-value feature requests backed by hard data.</p>
                </div>
            </div>
        </section>

        <!-- Audience Section (Login Portals) -->
        <section id="audience" class="max-w-7xl mx-auto px-6 py-24 relative">
            <div class="absolute inset-0 bg-blue-900/5 skew-y-3 z-0"></div>
            <div class="relative z-10">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Access Your Workspace</h2>
                    <p class="text-gray-400 text-xl max-w-2xl mx-auto font-medium">Secure, role-based portals tailored exactly to what you need to achieve.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- User Portal -->
                    <div class="group relative bg-gray-900/60 backdrop-blur-2xl border border-white/5 rounded-[2rem] p-12 hover:border-blue-500/50 transition-all duration-500 flex flex-col h-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <div class="relative z-10 flex-1">
                            <div class="flex items-center space-x-5 mb-8">
                                <div class="w-20 h-20 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 shadow-[0_0_20px_-5px_rgba(59,130,246,0.3)]">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-white tracking-tight">Users Portal</h3>
                                    <p class="text-blue-400 font-medium">For Stakeholders</p>
                                </div>
                            </div>
                            <div class="mb-10 space-y-6">
                                <p class="text-gray-400 text-lg leading-relaxed font-medium">
                                    Designed exclusively for <strong class="text-gray-200">Product Managers, Executives, and General Viewers</strong> who need high-level insights instantly.
                                </p>
                                <ul class="space-y-4 text-gray-400 font-medium">
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 text-blue-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Track overall app performance</li>
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 text-blue-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> View beautiful sentiment reports</li>
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 text-blue-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Monitor satisfaction metrics</li>
                                </ul>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <a href="{{ route('login') }}" target="_blank" class="flex items-center justify-between w-full py-5 px-8 rounded-xl text-lg font-bold text-white bg-blue-600 hover:bg-blue-500 transition-all shadow-lg hover:shadow-[0_0_30px_-5px_rgba(59,130,246,0.6)] group-hover:ring-2 ring-blue-400 ring-offset-2 ring-offset-[#0B0F19]">
                                Enter Workspace
                                <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Analyst Portal -->
                    <div class="group relative bg-gray-900/60 backdrop-blur-2xl border border-white/5 rounded-[2rem] p-12 hover:border-emerald-500/50 transition-all duration-500 flex flex-col h-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <div class="relative z-10 flex-1">
                            <div class="flex items-center space-x-5 mb-8">
                                <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 shadow-[0_0_20px_-5px_rgba(52,211,153,0.3)]">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-white tracking-tight">Analyst Portal</h3>
                                    <p class="text-emerald-400 font-medium">For Data Scientists</p>
                                </div>
                            </div>
                            <div class="mb-10 space-y-6">
                                <p class="text-gray-400 text-lg leading-relaxed font-medium">
                                    Engineered for <strong class="text-gray-200">Data Scientists and NLP Researchers</strong> requiring deep access to raw data pipelines.
                                </p>
                                <ul class="space-y-4 text-gray-400 font-medium">
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Manage and clean raw review datasets</li>
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Execute ML preprocessing pipelines</li>
                                    <li class="flex items-center"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400 mr-4 shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Generate in-depth analytical reviews</li>
                                </ul>
                            </div>
                        </div>
                        <div class="relative z-10 mt-auto">
                            <a href="{{ route('analyst.login') }}" target="_blank" class="flex items-center justify-between w-full py-5 px-8 rounded-xl text-lg font-bold text-gray-900 bg-emerald-400 hover:bg-emerald-300 transition-all shadow-lg hover:shadow-[0_0_30px_-5px_rgba(52,211,153,0.6)] group-hover:ring-2 ring-emerald-300 ring-offset-2 ring-offset-[#0B0F19]">
                                Enter Workspace
                                <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="max-w-5xl mx-auto px-6 py-24">
            <div class="bg-gradient-to-br from-gray-900 to-gray-900/50 border border-white/5 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px]"></div>
                
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tight relative z-10">Why Finpulse?</h2>
                <p class="text-gray-400 text-xl md:text-2xl leading-relaxed mb-8 relative z-10 font-medium">
                    Finpulse bridges the gap between raw user feedback and strategic financial software improvements. By continuously ingesting reviews from major app stores, our platform applies advanced NLP algorithms to determine user sentiment, highlight feature requests, and detect critical bugs. 
                </p>
                <p class="text-gray-500 text-lg leading-relaxed relative z-10 font-medium max-w-3xl mx-auto">
                    Whether you are tracking the success of a new feature rollout or performing competitive analysis, Finpulse provides the intelligence needed to stay ahead in the fast-paced fintech space.
                </p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/5 bg-[#080B13] pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('finpulse-icon.png') }}" alt="Finpulse" class="w-10 h-10 rounded-xl grayscale opacity-70">
                    <span class="text-2xl font-black text-white tracking-tighter">Finpulse<span class="text-emerald-500">.</span></span>
                </div>
                <p class="text-gray-500 max-w-sm font-medium leading-relaxed">
                    The intelligence layer for your financial software. Monitor, analyze, and build better products.
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Product</h4>
                <ul class="space-y-4">
                    <li><a href="#features" class="text-gray-500 hover:text-white transition-colors font-medium">Features</a></li>
                    <li><a href="#audience" class="text-gray-500 hover:text-white transition-colors font-medium">Portals</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-white transition-colors font-medium">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Company</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('pages.show', 'about') }}" class="text-gray-500 hover:text-white transition-colors font-medium">About</a></li>
                    <li><a href="{{ route('pages.show', 'privacy-policy') }}" class="text-gray-500 hover:text-white transition-colors font-medium">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.show', 'terms-of-service') }}" class="text-gray-500 hover:text-white transition-colors font-medium">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-600 text-sm font-medium">
                &copy; {{ date('Y') }} Finpulse. All rights reserved.
            </div>
            <div class="flex space-x-4 mt-4 md:mt-0">
                <!-- Social Placeholders -->
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Backdrop -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden fixed inset-0"
         style="z-index: 60;"
         @click="mobileMenuOpen = false"
         x-cloak>
         <div style="background-color: rgba(0, 0, 0, 0.8); width: 100%; height: 100%;"></div>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         x-cloak
         style="z-index: 70;"
         class="md:hidden fixed top-0 right-0 h-full">
        
        <div style="background-color: #0B0F19; width: 16rem; height: 100%;" class="border-l border-white/10 shadow-2xl flex flex-col">
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/5">
                <span class="text-xl font-black text-white tracking-tighter">Finpulse<span class="text-emerald-400">.</span></span>
                <button @click="mobileMenuOpen = false" class="flex items-center justify-center p-1.5 text-gray-400 hover:text-white transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="flex flex-col px-6 py-8 space-y-6 flex-1">
                <a href="#" @click="mobileMenuOpen = false" class="text-lg font-semibold text-white transition-colors">Home</a>
                <a href="#features" @click="mobileMenuOpen = false" class="text-lg font-semibold text-gray-400 hover:text-white transition-colors">Platform</a>
                <a href="#audience" @click="mobileMenuOpen = false" class="text-lg font-semibold text-gray-400 hover:text-white transition-colors">Portals</a>
                <a href="{{ route('pages.show', 'about') }}" @click="mobileMenuOpen = false" class="text-lg font-semibold text-gray-400 hover:text-white transition-colors">About</a>
                <div class="border-t border-white/10 pt-6 mt-2 flex flex-col space-y-4">
                    <a href="{{ route('login') }}" target="_blank" @click="mobileMenuOpen = false" class="text-lg font-bold text-emerald-400 hover:text-emerald-300 transition-colors">User Login</a>
                    <a href="{{ route('analyst.login') }}" target="_blank" @click="mobileMenuOpen = false" class="text-lg font-bold text-blue-400 hover:text-blue-300 transition-colors">Analyst Portal</a>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
