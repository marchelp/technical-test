# Mid Software Engineer - Technical Test
**Marchel Adias Pradana - marchel.adias@gmail.com** </br>
Repository ini merupakan hasil pengerjaan Technical Test untuk posisi Mid Software Engineer. API untuk manajemen keuangan sederhana (Pocket, Income, Expense, dan Report) yang dibangun menggunakan Laravel dan PostgreSQL.

## Tech Stack
* Language: PHP 8.3
* Framework: Laravel 12
* Database: PostgreSQL
* Authentication: JWT (JSON Web Token)

[ERD Schema](https://dbdiagram.io/d/Mid-Software-Engineer-6971aabdbd82f5fce23bde24)

## Installation
* `composer install`
* Copy file `.env.example` gunakan pada `.env`
  ```
  DB_CONNECTION=pgsql
  DB_HOST=127.0.0.1
  DB_PORT=5432
  DB_DATABASE=your_db_name
  DB_USERNAME=your_db_username
  DB_PASSWORD=your_db_password

  CACHE_STORE=database // Untuk mendapatkan update export excel tanpa queu:work
  ```
* Generate APP Key & JWT Secret
  ```
  php artisan key:generate
  php artisan jwt:secret
  ```

### User Seeder
  ```
  php artisan db:seed --class=UserSeeder
  ```

## API Specification
1. User login
   - Login will implement JWT for authentication
   - POST → `api/auth/login`
   - Request
     ```
     {
      "email": "example@mail.net",
      "password": "password"
     }
     ```
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil login.",
      "data": [
        "token": "jwt_token"
      ]
     }
     ```
2. Get user profile
   - GET → `api/auth/profile`
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil login.",
      "data": [
        "full_name": "User 1",
        "email": "example@mail.net",
      ]
     }
     ```
3. Add new pocket
   - POST → `api/pockets`
   - Request
     ```
     {
      "name": "Pocket 1",
      "initial_balance": 2000000
     }
     ```
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil membuat pocket baru.",
      "data": {
        "id": "pocket_id"
      }
     }
     ```
4. List pocket
   - GET → `api/pockets`
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil.",
      "data": [
        {
          "id": "pocket_id"
          "name": "Pocket 1",
          "current_balance": 2000000
        },
        ...
      ]
     }
     ```
5. Create income
   - POST → `api/incomes`
   - when create new income, it will add to pocket balance
   - Request
     ```
     {
      "pocket_id": "uuid",
      "amount": 300000,
      "notes": "Menemukan uang di jalan"
     }
     ```
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil menambahkan income.",
      "data": {
        "id": "income_id",
        "pocket_id": "pocket_id",
        "current_balance": 2300000
      }
     }
     ```
6. Create expense
   - POST → `api/expenses`
   - when create new expense, it will sub to pocket balance
   - Request
     ```
     {
      "pocket_id": "uuid",
      "amount": 2000000,
      "notes": "Ganti lecet mobil orang"
     }
     ```
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil menambahkan expense.",
      "data": {
        "id": "expense_id",
        "pocket_id": "pocket_id",
        "current_balance": 300000
      }
     }
     ```
7. Get total balances
   - GET → `api/pockets/total-balance`
   - Response
     ```
     {
      "status": 200,
      "error": false,
      "message": "Berhasil mendapatkan total balance.",
      "data": {
        "total": 300000
      }
     }
     ```
8. Create report by pocket id
   - POST → `api/pockets/:id/create-report`
   - When create a report, it will running as job process
   - Request
     ```
     {
      "type": "INCOME", //INCOME,EXPENSE,
      "date": "2026-01-01", //YYYY-MM-DD
     }
     ```
   - Respones
     ```
     {
      "status": 200,
      "error": false,
      "message": "Report sedang dibuat. Silahkan check berkala pada link berikut.",
      "data": {
        "link": "http://localhost:8000/reports/<uuid>-<timestamp>"
      }
     }
     ```
9. Create endpoint for stream report excel
   - GET → `reports/:id`
   - Description → When hit endpoint, it will stream an `<id>.xlsx` file inside local storage and download it
