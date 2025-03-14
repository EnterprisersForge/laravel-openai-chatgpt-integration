# Laravel 12 OpenAI ChatGPT Integration With Livewire Starter Kit

## Overview

This is a Laravel 12 starter kit with Livewire 3 with Volt, featuring an OpenAI ChatGPT chat and a prompt builder. This setup provides a foundation for building AI-powered applications using OpenAI's API.

## Features

- Laravel 12 framework
- Livewire 3 with Volt for reactive components
- OpenAI integration for AI-powered chat
- Prompt builder to create and manage AI prompts

## Requirements

Before installing, ensure you have the following installed:

- PHP ^8.2
- Composer
- Node.js & NPM
- MySQL or SQLite (for database management)

## Installation Guide

### 1. Clone the Repository

```sh
git clone https://github.com/EnterprisersForge/laravel-openai-chatgpt-integration
cd laravel-livewire-starter-kit
```

### 2. Install Dependencies

```sh
composer install
npm install
```

### 3. Configure Environment

```sh
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your database and OpenAI API key.

### 4. Set Up the Database

```sh
php artisan migrate
```

For SQLite, ensure the `database/database.sqlite` file exists:

```sh
touch database/database.sqlite
```

Then update the `.env` file:

```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 5. Serve the Application

```sh
php artisan serve
```

For development, you can use the `dev` script:

```sh
npm run dev
```

This will:

- Start the Laravel server
- Listen to the queue
- Run Vite for frontend assets


## Usage

- Access the application at `http://localhost:8000`
- Use the chat page to interact with OpenAI
- Manage AI prompts using the Prompt Builder

## OpenAI
Add your OpenAI API Key in the `.env` file:

```
OPENAI_API_KEY=
OPENAI_ORGANIZATION=
```
## Contributing

Feel free to submit issues or pull requests to improve this starter kit.

## License

This project is open-sourced under the MIT License.

