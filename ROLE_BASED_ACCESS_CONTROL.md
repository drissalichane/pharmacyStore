# Role-Based Access Control (RBAC) Implementation

## Overview

This document describes the role-based access control system implemented in the Pharmacy Store application. The system provides three user roles with different access levels:

- **Admin**: Full access to all administrative features
- **User**: Regular customer access (can browse products, place orders)
- **Guest**: Unauthenticated users (limited access)

## User Roles

### Admin (`admin`)
- Full access to admin dashboard
- Manage products, categories, orders, locations
- Upload pharmacy and emergency info images
- Run scraping commands
- Access to all administrative functions

### User (`user`)
- Browse products and categories
- Add items to cart
- Place orders
- View order history
- Access to customer-facing features only

### Guest (unauthenticated)
- Browse products
- View product details
- Cannot access protected routes

## Implementation Details

### Database Migration
- Added `role` column to `users` table with default value 'user'
- Migration: `database/migrations/2025_08_30_022532_add_role_to_users_table.php`

### User Model
- Added role constants: `ROLE_ADMIN`, `ROLE_USER`, `ROLE_GUEST`
- Added helper methods: `isAdmin()`, `isUser()`, `isGuest()`
- Added query scopes: `admins()`, `users()`

### Middleware
- **AdminMiddleware**: Checks if authenticated user has admin role
- Applied to all admin routes in `routes/web.php`

### Registration
- New users are automatically assigned the 'user' role
- Modified `RegisteredUserController` to set default role

### Admin User Creation
- Use the artisan command: `php artisan user:create-admin email password --name="Name"`
- Example: `php artisan user:create-admin admin@example.com password123 --name="System Administrator"`

## Protected Routes

All admin routes are protected by the `admin` middleware group:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin routes here
});
```

## Testing

Run the admin access tests to verify the implementation:

```bash
php artisan test --filter AdminAccessTest
```

## Security Features

1. **Role Validation**: AdminMiddleware checks user role before granting access
2. **403 Forbidden**: Regular users receive 403 error when accessing admin routes
3. **Login Redirect**: Unauthenticated users are redirected to login page
4. **Default Role**: New registrations get 'user' role by default

## Adding New Roles

To add new roles:

1. Add role constant to `User` model
2. Update the migration if needed (default role)
3. Add helper methods and scopes
4. Create middleware if needed for specific role permissions
5. Update tests

## Best Practices

- Always use the helper methods (`isAdmin()`, etc.) instead of direct role comparisons
- Use the query scopes for filtering users by role
- Test all role-based access scenarios
- Keep the admin user creation command secure (don't expose in production)
