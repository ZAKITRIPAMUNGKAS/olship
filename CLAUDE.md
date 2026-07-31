# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development (Laravel server + queue + logs + Vite concurrently)
composer run dev

# Production build
npm run build

# Run tests
composer run test

# Full setup from scratch
composer run setup
```

## Architecture

Laravel 13 e-commerce WMS (Warehouse Management System) with:
- **Backend:** Laravel 13, PHP 8.3+, Eloquent ORM, Spatie permissions, Sanctum auth
- **Frontend:** Blade templates, Alpine.js, Tailwind CSS 4, Chart.js, Vite 8
- **Payments:** Midtrans integration
- **Exports:** Maatwebsite Excel

### Key Domain Models (`app/Models/`)

Core e-commerce entities: `Product`, `Order`, `OrderItem`, `Cart`, `Payment`, `Coupon`, `FlashSale`, `Review`, `Withdrawal`, `User`, `Store`, `Category`, `Brand`.

### Frontend Assets (`resources/`)

- `css/app.css` — storefront styles
- `css/voltgear.css` — admin dashboard styles
- `js/app.js` — main app, `js/cart.js` — cart logic, `js/admin.js` / `js/ui-admin.js` — admin UI

### Services Layer (`app/Services/`)

Business logic is extracted into service classes, keeping controllers thin.

### Routes

- `routes/web.php` — Blade view routes
- `routes/api.php` — REST API endpoints (Sanctum-protected)
