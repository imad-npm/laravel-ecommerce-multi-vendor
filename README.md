# Laravel E-commerce Platform

Multi-vendor e-commerce platform built with Laravel. Supports admin, vendors, and customers — with dashboards, orders, reviews, real-time chat, and more.

---

## 🚀 Features

- **Multi-Vendor System** – Vendors can register, manage stores, products, and orders.
- **User Roles**
  - **Admin** – Manages users, categories, and platform settings.
  - **Vendor** – Manages their own store and customers.
  - **Customer** – Browses, orders, reviews, and chats.
- **Product & Category Management** – Rich product listings with images, descriptions, prices.
- **Shopping Cart & Orders** – Persistent cart, order tracking.
- **Customer Reviews** – Product ratings & feedback.
- **Real-time Chat** – Vendor-customer messaging (Laravel Echo).
- **Profile Management** – Self-managed accounts for all roles.

---

## 🔐 Default Accounts (Seeded)

| Role     | Email                  | Password  |
|----------|------------------------|-----------|
| Admin    | admin@example.com      | password  |
| Vendor   | vendor@example.com     | password  |
| Customer | customer@example.com   | password  |

---

## 🧱 Project Structure

- `app/Http/Controllers` – HTTP controllers  
- `app/Services` – Business logic  
- `app/DataTransferObjects` – DTOs  
- `app/Models` – Eloquent models  
- `resources/views` – Blade templates  
- `routes` – Route definitions  
- `database/seeders` – Demo data

---

## ⚙️ Setup

**Requirements:** PHP 8.2+, Composer, Node.js, npm

```bash
git clone https://github.com/imad-npm/laravel-ecommerce-multi-vendor.git
cd laravel-ecommerce-multi-vendor

composer install
npm install

cp .env.example .env

php artisan migrate --seed
