# TODO: Implement Two-Step Authentication for SuperAdmin Login

## Steps to Complete:
- [x] Modify AuthenticatedSessionController::store to handle SuperAdmin login differently
- [x] Add GET and POST routes for /admin-system/verify in routes/superadmin.php
- [x] Add verifyCode method in SuperAdminController (GET and POST handling)
- [x] Create resources/views/superadmin/verify-code.blade.php view
- [x] Test the login flow (routes are working correctly)

## Details:
- Store SuperAdmin ID in session as 'pending_superadmin_id' instead of logging in immediately
- Redirect to /admin-system/verify after password verification
- Verify code against 'ADMIN2026' in verifyCode method
- Login with Auth::guard('superadmin')->login($admin) if code is correct
- Remove session temp after successful login
