# TODO: Implement Hospital Workflow

## Database Changes
- [ ] Create migration to add price column to services table
- [ ] Create migration to update appointments status enum (add 'pending_payment', 'paid', 'released')
- [ ] Create migration to update users role enum (add 'cashier')

## Models Updates
- [ ] Update Service model to include price in fillable
- [ ] Update Appointment model if needed for new statuses

## Controllers
- [x] Create CashierController for payment validation
- [x] Update PatientController to show price and set appointment status to 'pending_payment'
- [x] Update NurseController to show only 'paid' appointments
- [x] Update DashboardController to add revenue statistics

## Views
- [ ] Create cashier dashboard view
- [ ] Update patient portal to display service prices
- [ ] Update nurse dashboard to filter paid appointments
- [ ] Update admin dashboard to show revenue card

## Routes
- [ ] Add routes for cashier functionality

## Testing
- [ ] Run migrations
- [ ] Test the workflow end-to-end
