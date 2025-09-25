E-Commerce Platform
Overview

This is a full-featured E-commerce platform built with Laravel 12, Vue.js 3, and Tailwind CSS.
It supports multiple user roles: Admin, Vendor, and Customer, with full management of products, orders, and users.
The platform includes dynamic front-end pages, responsive layouts, toast notifications, and dark mode support.

Features
Admin Panel

Manage vendors, products, and orders

Control access to sensitive operations

Review and modify user accounts

Vendor Panel

Manage vendor-specific products

Track customer orders

Add or edit products easily

Customer Dashboard

Browse products, add to cart, and checkout

View order history and manage profile

Frontend Features

Responsive design with Tailwind CSS

Dark Mode support

Toast notifications for user actions

Dynamic pages built with Vue.js 3

Backend Features

Eloquent relationships between models (Cart, CartItem, Product, Order, User)

Secure user authentication with Laravel Breeze

Database management using Migrations

MVC-based code organization

Role-based access control for Admin, Vendor, and Customer

Technologies

Backend: Laravel 12

Frontend: Vue 3, Tailwind CSS

Database: MySQL

Version Control: Git & GitHub

Build Tools: Vite


Supports role-based login for Admin, Vendor, and Customer.

Default roles can be seeded using database seeders or created during registration.

Login page: /login

Redirects after login based on role:

Admin → /admin/dashboard

Vendor → /vendor/dashboard

Customer → /customer/dashboard

Password reset and email verification supported via Laravel Breeze.

Clone the repository:
git clone https://github.com/your-username/ecommerce-platform.git
cd ecommerce-platform

Folder Structure
app/Models/           # Eloquent models
app/Http/Controllers/ # Controllers (Admin, Vendor, Customer)
resources/js/          # Vue components and pages
resources/css/         # Tailwind CSS
routes/web.php         # Web routes
database/migrations/   # Migrations
