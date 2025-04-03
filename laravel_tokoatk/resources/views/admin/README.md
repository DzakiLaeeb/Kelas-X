# Admin Panel Documentation

This document provides instructions on how to set up and use the admin panel for TokoATK.

## Setup Instructions

1. Run the migration to add the `is_admin` column to the users table:
   ```
   php artisan migrate
   ```

2. Run the seeder to create an admin user:
   ```
   php artisan db:seed --class=AdminUserSeeder
   ```

3. The default admin credentials are:
   - Username: admin
   - Email: admin@example.com
   - Password: admin123

## Accessing the Admin Panel

1. Log in with the admin credentials
2. Navigate to `/admin` in your browser
3. You should see the admin dashboard

## Features

The admin panel includes the following features:

### Dashboard
- Overview of users, products, orders, and sales
- Quick access to recent orders
- System information

### User Management
- View all users
- Add new users
- Edit existing users
- Delete users
- Set admin privileges

### Product Management
- View all products
- Add new products
- Edit existing products
- Delete products
- Manage product images

### Order Management
- View all orders
- View order details
- Update order status

### Banner Management
- View all banners
- Add new banners
- Edit existing banners
- Delete banners

## Security

The admin panel is protected by the `admin` middleware, which checks if the logged-in user has the `is_admin` flag set to true. If a non-admin user tries to access the admin panel, they will be redirected to the home page with an error message.

## Customization

You can customize the admin panel by editing the following files:
- Layout: `resources/views/layouts/admin.blade.php`
- Dashboard: `resources/views/admin/dashboard.blade.php`
- Controllers: `app/Http/Controllers/Admin/*`
