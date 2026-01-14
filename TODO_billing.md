# TODO: Implement Evolutive Invoice System

## Database Changes
- [ ] Create Prestation model
- [ ] Create migration for appointment_prestations pivot table
- [ ] Update Service model (remove price dependency)
- [ ] Update Appointment model (add prestations relationship)

## Controllers
- [ ] Update CashierController for evolutive billing logic
- [ ] Add functionality for doctors to add prestations during appointments

## Views
- [ ] Update cashier/dashboard.blade.php to show consultation price
- [ ] Update portal/book-appointment.blade.php to show consultation price
- [ ] Add views for managing prestations during appointments

## Testing
- [ ] Test consultation payment flow
- [ ] Test adding additional prestations
- [ ] Test final invoice generation
- [ ] Seed prestations data for hospitals
