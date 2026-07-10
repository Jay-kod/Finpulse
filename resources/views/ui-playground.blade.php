@extends('layouts.app')

@section('title', '| UI Playground')

@section('content')
    <div class="space-y-12">
        <div class="border-b border-gray-200 pb-5">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Design System & UI Library</h1>
            <p class="mt-2 text-sm text-gray-500">A demonstration of all custom Blade UI components available in the application.</p>
        </div>

        {{-- Typography & Colors --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">1. Buttons & Actions</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-ui.card>
                    <x-slot name="header">
                        <h3 class="font-medium text-gray-900">Button Variants</h3>
                    </x-slot>
                    <div class="flex flex-wrap gap-4">
                        <x-ui.button variant="primary">Primary</x-ui.button>
                        <x-ui.button variant="secondary">Secondary</x-ui.button>
                        <x-ui.button variant="danger">Danger</x-ui.button>
                        <x-ui.button variant="outline">Outline</x-ui.button>
                        <x-ui.button variant="ghost">Ghost</x-ui.button>
                        <x-ui.button disabled>Disabled</x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <x-slot name="header">
                        <h3 class="font-medium text-gray-900">Button Sizes</h3>
                    </x-slot>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-ui.button size="sm">Small</x-ui.button>
                        <x-ui.button size="md">Medium</x-ui.button>
                        <x-ui.button size="lg">Large</x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Forms --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">2. Form Elements</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-ui.card>
                    <form class="space-y-4">
                        <x-ui.form-group label="Email Address" for="email" help="We'll never share your email.">
                            <x-ui.input type="email" id="email" name="email" placeholder="you@example.com" />
                        </x-ui.form-group>

                        <x-ui.form-group label="Password" for="password">
                            <x-ui.input type="password" id="password" name="password" placeholder="••••••••" />
                        </x-ui.form-group>

                        <x-ui.form-group label="Select Role" for="role">
                            <x-ui.select id="role" name="role" :options="['admin' => 'Administrator', 'user' => 'User']" placeholder="Choose a role..." />
                        </x-ui.form-group>

                        <x-ui.form-group label="Biography" for="bio">
                            <x-ui.textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself..."></x-ui.textarea>
                        </x-ui.form-group>

                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <x-ui.checkbox id="terms" name="terms" />
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <x-ui.label for="terms">I accept the terms and conditions</x-ui.label>
                            </div>
                        </div>
                    </form>
                </x-ui.card>

                <x-ui.card>
                    <x-slot name="header">
                        <h3 class="font-medium text-gray-900">Validation States</h3>
                    </x-slot>
                    
                    <div class="space-y-4">
                        <x-ui.form-group label="Invalid Input" for="invalid" error="This field is required.">
                            <x-ui.input type="text" id="invalid" error="true" placeholder="Error state..." />
                        </x-ui.form-group>
                        
                        <x-ui.form-group label="Disabled Input">
                            <x-ui.input type="text" disabled placeholder="You can't type here" />
                        </x-ui.form-group>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Badges and Alerts --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">3. Feedback & Data Display</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-ui.card>
                    <x-slot name="header">
                        <h3 class="font-medium text-gray-900">Badges</h3>
                    </x-slot>
                    <div class="flex flex-wrap gap-4 mb-6">
                        <x-ui.badge variant="success">Success</x-ui.badge>
                        <x-ui.badge variant="warning">Warning</x-ui.badge>
                        <x-ui.badge variant="error">Error</x-ui.badge>
                        <x-ui.badge variant="info">Info</x-ui.badge>
                        <x-ui.badge variant="dark">Dark</x-ui.badge>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <x-ui.badge variant="success" size="sm" rounded="md">Square Small</x-ui.badge>
                        <x-ui.badge variant="info" rounded="md">Square Normal</x-ui.badge>
                    </div>
                </x-ui.card>

                <div class="space-y-4">
                    <x-ui.alert variant="info" dismissible>A new software update is available. See what's new.</x-ui.alert>
                    <x-ui.alert variant="success" dismissible>Successfully saved your profile settings.</x-ui.alert>
                    <x-ui.alert variant="warning" dismissible>Your account is nearing its storage limit.</x-ui.alert>
                    <x-ui.alert variant="error" dismissible>There was a problem processing your payment.</x-ui.alert>
                </div>
            </div>
        </section>

        {{-- Modals --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">4. Modals (Alpine.js)</h2>
            <x-ui.card>
                <div x-data>
                    <x-ui.button x-on:click="$dispatch('open-modal', 'demo-modal')">Open Demo Modal</x-ui.button>
                </div>

                <x-ui.modal name="demo-modal">
                    <x-slot name="title">Deactivate account</x-slot>
                    
                    <p class="text-sm text-gray-500">
                        Are you sure you want to deactivate your account? All of your data will be permanently removed. This action cannot be undone.
                    </p>

                    <x-slot name="footer">
                        <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'demo-modal')">Cancel</x-ui.button>
                        <x-ui.button variant="danger" x-on:click="$dispatch('close-modal', 'demo-modal')">Deactivate</x-ui.button>
                    </x-slot>
                </x-ui.modal>
            </x-ui.card>
        </section>

        {{-- Tables --}}
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-6">5. Data Tables</h2>
            
            <x-ui.table>
                <x-slot name="headers">
                    <x-ui.table.th>Name</x-ui.table.th>
                    <x-ui.table.th>Title</x-ui.table.th>
                    <x-ui.table.th>Status</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot>

                @foreach(range(1, 3) as $row)
                    <x-ui.table.tr>
                        <x-ui.table.td>
                            <div class="font-medium text-gray-900">Jane Cooper</div>
                            <div class="text-gray-500">jane.cooper@example.com</div>
                        </x-ui.table.td>
                        <x-ui.table.td>Regional Paradigm Technician</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="success">Active</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <x-ui.button variant="ghost" size="sm">Edit</x-ui.button>
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforeach
            </x-ui.table>
        </section>
    </div>
@endsection
