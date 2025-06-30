# Laravel E-commerce Platform

This is a multi-vendor e-commerce platform built with the Laravel framework. It provides a robust set of features for administrators, vendors, and customers, creating a complete online marketplace experience.

## Features

*   **Multi-Vendor System:** Allows multiple vendors to sign up, create their own stores, and sell products.
*   **User Roles & Permissions:**
    *   **Administrator:** Manages the entire platform, including users, categories, and site settings.
    *   **Vendor:** Manages their own store, products, orders, and interacts with customers.
    *   **Customer:** Can browse products, place orders, write reviews, and interact with vendors.
*   **Product & Category Management:** Administrators can define product categories, and vendors can list and manage their products within these categories.
*   **Shopping Cart & Orders:** A complete cart and order management system for customers.
*   **Customer Reviews:** Customers can leave reviews and ratings on products they have purchased.
*   **Real-time Chat:** Enables direct communication between customers and vendors for support and inquiries.
*   **Profile Management:** Users can manage their own profiles and settings.

## Tech Stack

*   **Backend:** Laravel 12, PHP 8.2
*   **Frontend:** Vite, Tailwind CSS, Alpine.js
*   **Database:** Mysql
*   **Testing:** Pest

## Getting Started

Follow these instructions to get the project up and running on your local machine for development and testing purposes.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & npm
*   A web server (Nginx or Apache), or you can use the built-in Laravel server.

### Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/your-repository.git
    cd your-repository
    ```

2.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

3.  **Install NPM dependencies:**
    ```bash
    npm install
    ```

4.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Create the database file:**
    ```bash
    touch database/database.sqlite
    ```

7.  **Run the database migrations:**
    ```bash
    php artisan migrate
    ```

8.  **Seed the database with sample data:**
    This will create admin, vendor, and customer users, along with sample stores, products, orders, and reviews.
    ```bash
    php artisan db:seed
    ```

### Running the Application

The project is configured to run all necessary development services concurrently.

*   **Start the development servers (Vite, Laravel server, queue worker, and logs):**
    ```bash
    composer run dev
    ```
    This will:
    - Start the Vite development server for frontend assets.
    - Start the PHP development server at `http://127.0.0.1:8000`.
    - Start a queue listener to process background jobs.
    - Start `pail` to tail the application logs.

You can now access the application at [http://127.0.0.1:8000](http://127.0.0.1:8000).

