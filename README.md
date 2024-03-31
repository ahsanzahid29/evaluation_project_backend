## About Laravel API Project

This project is a Laravel-based API system that provides functionalities for user Signup, Login, and managing Categories and Cars. It utilizes JWT (JSON Web Tokens) for secure authentication.

- User Authentication (Signup/Login)
- JWT Token-based Authentication
- CRUD Operations for Categories
- CRUD Operations for Cars.



## Getting Started

To get a local copy up and running, follow these simple steps.


### Prerequisites

- PHP >= 7.3
- Composer
- Laravel 8.x
- A database system (MySQL)

### Installation

1. Clone the repository:
   git clone https://github.com/ahsanzahid29/evaluation_project_backend.git
2. Install Composer dependencies:
   <b>composer install</b>
3. Copy .env.example to .env and configure your environment variables, including database and JWT settings.
4. Generate an application key:
   <b>php artisan key:generate</b>
5. Run the migrations:
   <b>php artisan migrate</b>
6. Generate JWT secret key
   <b>php artisan jwt:secret</b>
7. Serve the application
   <b>php artisan serve</b>
