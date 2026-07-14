<div x-data="{
        show: false,
        message: '',
        type: 'success',
        timeout: null,
        init() {
            @if(session()->has('flash'))
                this.fire('{{ session('flash')['message'] ?? session('flash') }}', '{{ session('flash')['type'] ?? 'success' }}');
            @elseif(session()->has('success'))
                this.fire('{{ session('success') }}', 'success');
            @elseif(session()->has('error'))
                this.fire('{{ session('error') }}', 'error');
            @endif
        },
        fire(msg, t = 'success') {
            this.message = msg;
            this.type = t;
            this.show = true;
            if(this.timeout) clearTimeout(this.timeout);
            this.timeout = setTimeout(() => { this.show = false; }, 4000);
        }
     }"
     @open-toast.window="fire($event.detail.message, $event.detail.type)"
     aria-live="assertive"
     class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50">
    
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        <div x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :style="type === 'success' ? 'background-color: #15803d;' : 'background-color: #b91c1c;'"
             class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-2xl ring-1 ring-black ring-opacity-10 text-white"
             style="display: none;">
             
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Success Icon -->
                        <svg x-show="type === 'success'" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <!-- Error Icon -->
                        <svg x-show="type === 'error'" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-extrabold text-white" x-text="type === 'success' ? 'Task Complete' : 'Error'"></p>
                        <p class="mt-1 text-sm text-white" style="opacity: 0.9;" x-text="message"></p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button type="button" @click="show = false" class="inline-flex rounded-md text-white hover:text-gray-200 focus:outline-none" style="opacity: 0.8;">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
