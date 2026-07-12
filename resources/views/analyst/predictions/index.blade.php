@extends('layouts.app')

@section('title', 'Predictions Lab')

@section('content')
<div class="max-w-7xl mx-auto" x-data="predictionsLab()">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Predictions Lab</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Paste any review text to instantly run sentiment analysis, topic classification, and bug detection.</p>
    </div>

    {{-- Service Status Banner --}}
    <div class="mb-6 rounded-lg border px-4 py-3 flex items-center gap-3 {{ $serviceOnline ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }}">
        <span class="flex h-3 w-3 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $serviceOnline ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 {{ $serviceOnline ? 'bg-green-500' : 'bg-red-500' }}"></span>
        </span>
        <span class="text-sm font-medium {{ $serviceOnline ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300' }}">
            ML Microservice: {{ $serviceOnline ? 'Online — Ready to analyze' : 'Offline — Please start the Python service' }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Input Panel --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Review Input
                    </h2>
                </div>
                <div class="p-5 space-y-4">
                    {{-- Source Selection --}}
                    <div class="mb-4">
                        <label for="review-source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Review Source</label>
                        <select id="review-source" x-model="source" class="w-full sm:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <option value="Google Play">Google Play Store</option>
                            <option value="App Store">Apple App Store</option>
                            <option value="X (Twitter)">X (Twitter)</option>
                            <option value="Other">Other / Web</option>
                        </select>
                    </div>

                    <div>
                        <label for="review-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Paste or type a review</label>
                        <textarea
                            id="review-text"
                            x-model="inputText"
                            rows="8"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                            placeholder="e.g. This app is great! Sending money is fast and the UI is beautiful. But sometimes the fingerprint login fails..."
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500" x-text="inputText.length + ' / 5000 characters'"></p>
                    </div>

                    {{-- Quick Samples --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Quick samples:</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="inputText = 'This app is amazing! Transfers are instant and the interface is very clean. Best banking app in Nigeria.'" class="px-2.5 py-1 text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors border border-green-200 dark:border-green-800">
                                😊 Positive
                            </button>
                            <button type="button" @click="inputText = 'The app keeps crashing when I try to send money. I lost N5000 yesterday and customer service is not responding. Very frustrating!'" class="px-2.5 py-1 text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors border border-red-200 dark:border-red-800">
                                😠 Negative
                            </button>
                            <button type="button" @click="inputText = 'The app works fine for basic transactions. Nothing special but gets the job done. Would be nice to have a dark mode.'" class="px-2.5 py-1 text-xs font-medium bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-600">
                                😐 Neutral
                            </button>
                            <button type="button" @click="inputText = 'There is a bug where the app freezes on the payment confirmation screen. This happens every time I use my Visa card. Please fix this ASAP.'" class="px-2.5 py-1 text-xs font-medium bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full hover:bg-orange-100 dark:hover:bg-orange-900/50 transition-colors border border-orange-200 dark:border-orange-800">
                                🐛 Bug Report
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="analyze()"
                        :disabled="loading || inputText.trim().length < 5"
                        class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-500/25 text-sm font-bold text-white bg-primary-600 hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Analyze Review
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Running Pipeline...
                            </span>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        {{-- Results Panel --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Empty State --}}
            <template x-if="!result && !error">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No results yet</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Enter a review in the text box and click <span class="font-semibold text-primary-600 dark:text-primary-400">Analyze Review</span> to see predictions for sentiment, topic, intent, and bug detection.
                    </p>
                </div>
            </template>

            {{-- Error State --}}
            <template x-if="error">
                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">Analysis Failed</h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-400" x-text="error"></p>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Results --}}
            <template x-if="result">
                <div class="space-y-5" x-transition>
                    {{-- Sentiment Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="text-lg" x-text="sentimentEmoji"></span>
                                Sentiment Analysis
                            </h2>
                        </div>
                        <div class="p-5">
                            {{-- Overall Label --}}
                            <div class="flex items-center justify-between mb-5">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Overall Sentiment</span>
                                <span class="px-3 py-1 text-sm font-bold rounded-full" :class="sentimentLabelClass" x-text="result.sentiment.label"></span>
                            </div>

                            {{-- Score Bars --}}
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-medium text-green-700 dark:text-green-400">Positive</span>
                                        <span class="text-gray-500" x-text="(result.sentiment.positive * 100).toFixed(1) + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-green-500 h-2.5 rounded-full transition-all duration-700 ease-out" :style="'width: ' + (result.sentiment.positive * 100) + '%'"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Neutral</span>
                                        <span class="text-gray-500" x-text="(result.sentiment.neutral * 100).toFixed(1) + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-gray-400 h-2.5 rounded-full transition-all duration-700 ease-out" :style="'width: ' + (result.sentiment.neutral * 100) + '%'"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-medium text-red-700 dark:text-red-400">Negative</span>
                                        <span class="text-gray-500" x-text="(result.sentiment.negative * 100).toFixed(1) + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-red-500 h-2.5 rounded-full transition-all duration-700 ease-out" :style="'width: ' + (result.sentiment.negative * 100) + '%'"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Compound Score --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Compound Score</span>
                                <span class="text-lg font-bold" :class="result.sentiment.compound >= 0.05 ? 'text-green-600 dark:text-green-400' : (result.sentiment.compound <= -0.05 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400')" x-text="result.sentiment.compound.toFixed(4)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Classification & Preprocessing Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Classification --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    Classification
                                </h2>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Topic</span>
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg border border-blue-200 dark:border-blue-800" x-text="result.classification.topic || 'N/A'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Intent</span>
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-lg border border-purple-200 dark:border-purple-800" x-text="result.classification.intent || 'N/A'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Bug Detected</span>
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg border" :class="result.classification.is_bug ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800' : 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'" x-text="result.classification.is_bug ? '🐛 Yes' : '✅ No'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Preprocessing --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    Preprocessing
                                </h2>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Language</span>
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg" x-text="(result.preprocessing.language || 'unknown').toUpperCase()"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Word Count</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="result.preprocessing.word_count || 0"></span>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block mb-1.5">Cleaned Text</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 font-mono leading-relaxed max-h-24 overflow-y-auto" x-text="result.preprocessing.cleaned_text || ''"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Analysis History --}}
                    <template x-if="history.length > 1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Session History
                                </h2>
                                <button @click="history = []; result = null" class="text-xs text-gray-400 hover:text-red-500 transition-colors">Clear all</button>
                            </div>
                            <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-60 overflow-y-auto">
                                <template x-for="(item, idx) in history" :key="idx">
                                    <button @click="result = item; scrollToResults()" class="w-full text-left px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex items-center gap-3">
                                        <span class="text-lg shrink-0" x-text="item.sentiment.label === 'positive' ? '😊' : (item.sentiment.label === 'negative' ? '😠' : '😐')"></span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 truncate" x-text="item.original_text"></p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                <span x-text="item.source" class="font-medium text-gray-500 dark:text-gray-300"></span> · 
                                                <span x-text="item.classification.topic || 'N/A'"></span> · 
                                                <span x-text="'Compound: ' + item.sentiment.compound.toFixed(2)"></span>
                                            </p>
                                        </div>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full shrink-0" :class="item.sentiment.label === 'positive' ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400' : (item.sentiment.label === 'negative' ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400')" x-text="item.sentiment.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function predictionsLab() {
    return {
        source: 'Google Play',
        inputText: '',
        loading: false,
        result: null,
        error: null,
        history: [],

        init() {
            // Auto-fill from ?text= query parameter (e.g., when clicking "Predict" from Reviews)
            const params = new URLSearchParams(window.location.search);
            const prefill = params.get('text');
            const prefillSource = params.get('source');
            if (prefillSource) {
                this.source = prefillSource;
            }
            if (prefill && prefill.trim().length >= 5) {
                this.inputText = prefill;
                // Auto-trigger analysis after a short delay
                this.$nextTick(() => this.analyze());
            }
        },

        get sentimentEmoji() {
            if (!this.result) return '';
            const label = this.result.sentiment?.label;
            if (label === 'positive') return '😊';
            if (label === 'negative') return '😠';
            return '😐';
        },

        get sentimentLabelClass() {
            if (!this.result) return '';
            const label = this.result.sentiment?.label;
            if (label === 'positive') return 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400';
            if (label === 'negative') return 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400';
            return 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
        },

        async analyze() {
            if (this.inputText.trim().length < 5) return;

            this.loading = true;
            this.error = null;
            this.result = null;

            try {
                const response = await fetch('{{ route("analyst.predictions.analyze") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ text: this.inputText }),
                });

                const data = await response.json();

                if (data.success) {
                    // Inject source into the result object so we can show it in history
                    data.data.source = this.source;
                    this.result = data.data;
                    // Add to history (most recent first)
                    this.history.unshift(data.data);
                    // Keep only last 10
                    if (this.history.length > 10) this.history.pop();
                } else {
                    this.error = data.message || 'Analysis failed. Please try again.';
                }
            } catch (e) {
                this.error = 'Network error. Please check your connection and try again.';
            } finally {
                this.loading = false;
            }
        },

        scrollToResults() {
            // no-op for now, already visible
        }
    };
}
</script>
@endsection
