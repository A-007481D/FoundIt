# FoundIt

Lost-and-Found Web Application

## Overview

FoundIt is a robust lost-and-found web application that enables users to report and discover lost or found items within a community. It features a modern Vue.js frontend and a Laravel API backend, offering real-time notifications, image classification, and powerful search capabilities.

## Features

- Create and manage lost item reports
- Create and manage found item reports
- Browse and search items by category or keywords
- Real-time notifications via Pusher and Laravel Echo
- Image classification powered by TensorFlow.js (MobileNet & KNN classifier)
- Full-text search using Laravel Scout and MeiliSearch
- Secure authentication with JWT
- Responsive design with Tailwind CSS

## Architecture

This project follows a decoupled architecture:

- **Frontend**: Vue.js Single Page Application (SPA) built with Vite
- **Backend**: Laravel RESTful API
- **Database**: PostgreSQL
- **Broadcasting**: Pusher (backend uses pusher-php-server; frontend uses Laravel Echo & Pusher JS)
- **Search**: MeiliSearch via Laravel Scout

## Tech Stack

### Frontend

- Vue.js 3.x
- Vite
- Pinia for state management
- Vue Router
- Tailwind CSS, PostCSS, Autoprefixer
- Axios (HTTP client)
- Laravel Echo & Pusher JS
- Vue3-Toastify (notification alerts)
- Lucide Vue Next (icons)
- Date-fns (date utilities)
- @tensorflow/tfjs, @tensorflow-models/mobilenet, @tensorflow-models/knn-classifier (image classification)

### Backend

- PHP 8.1+
- Laravel 10
- tymon/jwt-auth (authentication)
- Laravel Scout & meilisearch-php (search)
- Pusher PHP Server (broadcasting)
- GuzzleHTTP (HTTP client)
- PHPUnit, Mockery (testing)
- Laravel Pint (code style)
<!-- - Laravel Sail (local Docker environment) -->

<!-- ## Folder Structure

```
founditv4/
├── frontend/          # Vue.js SPA
│   ├── src/           # Vue components, stores, views
│   └── public/        # Static assets
├── backend/           # Laravel API
│   ├── app/           # Controllers, Models, Events
│   ├── database/      # Migrations & seeders
│   └── routes/        # API & web routes
└── README.md          # Project overview & docs
```

## Installation

### Prerequisites

- Node.js >= 16
- npm or Yarn
- PHP >= 8.1
- Composer
- MySQL or compatible database
- Docker (optional for Sail)

### Setup

```bash
# Frontend
cd frontend
npm install

# Backend
cd ../backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

# (Optional) Start Sail for Docker environment
# ./vendor/bin/sail up -d

# Start development servers
# Backend
php artisan serve

# Frontend
cd ../frontend
npm run dev
```

## Environment Variables

- **Frontend**
  - `VITE_API_BASE_URL`
  - `VITE_PUSHER_KEY`
  - `VITE_PUSHER_CLUSTER`

- **Backend**
  - `APP_NAME`, `APP_ENV`, `APP_KEY`
  - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
  - `BROADCAST_DRIVER=pusher`
  - `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`
  - `SCOUT_DRIVER=meilisearch`
  - `MEILISEARCH_HOST`, `MEILISEARCH_KEY`

## Testing

```bash
# Backend tests
cd backend
php artisan test

# Frontend tests (if configured)
cd ../frontend
npm test
```

## Contributing

Contributions are welcome! Please open issues or pull requests for bug fixes, features, or improvements. -->

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.
