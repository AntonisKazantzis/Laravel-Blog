# Laravel BLog

## Overview
Laravel Blog is a web application built with Vue.js, Filament, Livewire and PHP Laravel, providing a modern and responsive interface for exploring Posts data sourced from the [CactusApi](https://laraveltests.cactuscrm.gr/api). Key features include:

- Blog Page.
- CRUD categories.
- CRUD posts.
- Admin Panel.
- Charts.

## Installation
Ensure you have [Git](https://git-scm.com/), [Composer](https://getcomposer.org/), and [Node.js](https://nodejs.org/) installed.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AntonisKazantzis/Laravel-Blog.git
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrate database and seed initial data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Compile assets and start server:**
   ```bash
   npm run dev
   php artisan serve
   ```

6. **Access the application:**
   - URL: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
   - Credentials: 
     - Email: test@test.com 
     - Password: password

7. **Access the admin panel:**
   - URL: [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
   - Credentials: 
     - Email: test@test.com 
     - Password: password

## Dependencies
- Laravel 10
- Laravel Pint 1.16
- Laravel Jetstream 4.3
- Calebporzio Sushi 2.5
- Flowframe Laravel-Trend *
- Filament 3.2
- Inertia.js 0.6.8
- Inertia.js Vue3 1.0.0
- Tabler Icons-Vue 3.5.0
- Lodash 4.17.21
- Md Editor 4.15.6

## Tags
#VueJS #Laravel #Tailwind #Jetstream #FullStackDevelopment #CactusApi #InertiaJS #Filament #Livewire

## Developer
Antonis Kazantzis
