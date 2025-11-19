
# Laravel Multi-Vendor E-Commerce Platform

A complete multi-vendor e-commerce platform built with **Laravel**, featuring role-based dashboards, Stripe payments, automated vendor payouts, modular architecture, and a built-in customer–vendor messaging.

---

## 🚀 Features

### Core

* **Multi-Vendor Architecture** — Each vendor manages their own store, products, and orders.
* **Role-Based Dashboards**

  * **Admin:** Users, vendors, categories, reviews, platform moderation.
  * **Vendor:** Store, products, customer conversations, payout forecasts.
  * **Customer:** Browsing, shopping cart, orders, reviews, messaging.

### Commerce

* **Products & Categories** — Full CRUD with images, pricing, and rich details.
* **Persistent Shopping Cart** — Works across sessions.
* **Orders** — Status tracking, payment countdown timers, admin/vendor controls.
* **Reviews** — Customer product ratings influencing vendor reputation.

### Communication

**Chat Messaging** — Customer ↔ Vendor messaging using standard database-backed messages.
**Conversation History** — Stored and displayed chronologically.

### Payments & Payouts

* **Stripe Checkout / Elements** for secure customer payments.
* **Stripe Webhooks** for real-time order status synchronization.
* **Stripe Connect Payouts**
  Automated vendor payouts via scheduled console jobs (daily or configurable).

### Account Management

* User profiles, store settings, vendor onboarding, admin controls.
* Optional Stripe Connect onboarding wizard.

### Developer Features

* Modular service-based architecture.
* DTOs for strict data flow.
* Reusable Blade components & consistent UI structure.
* Pre-seeded demo users for instant testing.

---

## 🗂️ Architecture Overview

```
app/
  Http/Controllers     → Web, dashboard, Stripe, messaging, products, orders
  Services             → Stripe, payouts, orders, reviews, chat processing
  DataTransferObjects  → OrderDTO, ProductDTO, PayoutDTO, etc.
  Models               → User, Vendor, Product, Order, Review, Conversation...
routes/
  web.php, admin.php, vendor.php, customer.php, console.php
resources/views/       → Modular Blade components & layouts
resources/js/          → Echo, notifications, dropdowns, messaging UI
database/seeders/      → Default admin/vendor/customer + demo data
config/stripe.php      → Stripe keys, webhook secrets, Connect config
```

---

## 💳 Stripe Integration

### 1. Customer Payments

* Checkout initiated via a Stripe Checkout Session.
* Optional embedded **Stripe Elements** form.
* On success:

  * `checkout.session.completed` webhook received.
  * Order updated immediately.
  * Duplicate payments prevented.

### 2. Payment Webhooks

Handled by `StripeWebhookController` (`/stripe/webhook` route):

* Validates signature.
* Retrieves payment intent.
* Marks corresponding order as *paid*.
* Dispatches post-payment events.

### 3. Vendor Payouts (Stripe Connect)

* Vendors link Stripe accounts via `Vendor\StripeAccountController`.
* Stored in DB as `stripe_account_id`.
* Daily scheduled command:

  * Collects completed & paid vendor orders.
  * Computes commission.
  * Issues payout transfer via Stripe Connect.
  * Logs payout events for audit.

### Required `.env` keys

```
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
STRIPE_CLIENT_ID=
STRIPE_STATIC_VENDOR_ACCOUNT_ID=   # Optional fallback
```

---

## ⚡ Installation / Quick Start

### Requirements

PHP **8.2+**, Composer, Node.js, npm, MySQL/PostgreSQL, Stripe keys.

### Steps

```bash
git clone https://github.com/imad-npm/laravel-ecommerce-multi-vendor.git
cd laravel-ecommerce-multi-vendor

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```

### Stripe Setup

1. Insert your Stripe keys into `.env`.
2. Configure webhook in Stripe Dashboard → point to:

   ```
   https://your-domain.com/stripe/webhook
   ```
3.  Activate Stripe Connect for vendor payouts.

---

## 🔑 Demo Login Credentials

| Role     | Email                                               | Password |
| -------- | --------------------------------------------------- | -------- |
| Admin    | [admin@example.com](mailto:admin@example.com)       | password |
| Vendor   | [vendor@example.com](mailto:vendor@example.com)     | password |
| Customer | [customer@example.com](mailto:customer@example.com) | password |

---

## 📦 Extending the Platform

The platform is structured for easy extension:

* Add payment gateways (PayPal, Wise, Payoneer).
* Add shipping integrations (Shippo, EasyPost, custom rules).
* Add analytics dashboards.
* Add marketplace fees, tax rules, or invoice generation.
* Add wishlist, coupons, or product variants.

---
