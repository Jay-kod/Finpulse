# Component 03: Authentication

## Overview
This component implements robust, secure user authentication using Laravel Breeze as the backend engine, paired with custom frontend views built on our Component 02 Design System.

## Features Implemented
- **Registration (`/register`)**: Custom UI form with Name, Email, Password, and Confirmation.
- **Login (`/login`)**: Custom UI with "Remember Me" toggle.
- **Password Reset (`/forgot-password`, `/reset-password`)**: Full email flow with token verification.
- **Email Verification (`/verify-email`)**: UI for resending verification links (currently optional per project settings).
- **Profile Management (`/profile`)**: Update profile info, change password, and delete account functionality all presented using `x-ui.card` and our custom form inputs.
- **Session Management**: Secure logout via POST form in the topbar dropdown.

## Architecture & Modifications
1. **Backend**: Installed `laravel/breeze` using the `blade` stack. We kept the generated controllers in `app/Http/Controllers/Auth` and the routes in `routes/auth.php`.
2. **Frontend Views**: 
   - We preserved our custom `resources/views/layouts/guest.blade.php` and `app.blade.php` to prevent Breeze from overwriting our overarching design.
   - We rewrote every view inside `resources/views/auth/` and `resources/views/profile/` to completely replace Breeze's `x-text-input` and `x-primary-button` components with our own `<x-ui.input>` and `<x-ui.button>` components.
3. **UI Enhancements**:
   - The `<x-ui.error>` and `<x-ui.form-group>` components were upgraded to flawlessly support custom Laravel ErrorBags (such as `updatePassword` and `userDeletion`).

## Testing
- `php artisan test` covers all authentication flows (37 tests, 101 assertions). All feature tests for Authentication, Registration, Password Reset, and Profile updates pass successfully.
