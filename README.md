# 📚 Book Review Platform

A full-stack web application built with Laravel for managing books and reviews. Users can browse books, write reviews, rate books, and interact with a comprehensive RESTful API.

## ✨ Features

- 🔐 **User Authentication & Authorization**
  - User registration and login
  - Secure token-based authentication using Laravel Sanctum
  - Protected API endpoints

- 📖 **Book Management**
  - Browse all available books
  - View detailed book information
  - Search and filter books

- ⭐ **Review System**
  - Write reviews for books
  - Rate books with star ratings
  - View all reviews for each book
  - Edit and delete your own reviews

- 🔌 **RESTful API**
  - Complete API for all operations
  - JWT/Token-based authentication
  - JSON responses
  - Well-structured endpoints

## 🛠️ Technologies Used

### Backend
- **PHP** - Server-side programming language
- **Laravel Framework** - PHP web application framework
- **Laravel Sanctum** - API authentication
- **Eloquent ORM** - Database interactions

### Database
- **MySQL** - Relational database management system
- **Laravel Migrations** - Database version control

### Frontend
- **Blade Templates** - Laravel's templating engine
- **HTML5 & CSS3** - Structure and styling
- **JavaScript** - Client-side interactivity
- **Bootstrap** - Responsive design framework

## 📋 Requirements

- PHP >= 8.0
- Composer
- MySQL >= 5.7
- Node.js & NPM (optional, for asset compilation)

## 🚀 Installation

### 1. Clone the repository
```bash
git clone https://github.com/youssefNegm-BD/Book-review-project.git
cd Book-review-project
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment setup
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure database
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_review
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. (Optional) Seed database with sample data
```bash
php artisan db:seed
```

### 7. Start the development server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 📡 API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/register` | Register new user | No |
| POST | `/api/login` | Login user | No |
| POST | `/api/logout` | Logout user | Yes |

**Register Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Login Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Login Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|abc123xyz..."
}
```

### Books

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/books` | Get all books | No |
| GET | `/api/books/{id}` | Get single book | No |
| POST | `/api/books` | Create new book | Yes (Admin) |
| PUT | `/api/books/{id}` | Update book | Yes (Admin) |
| DELETE | `/api/books/{id}` | Delete book | Yes (Admin) |

**Get All Books Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Laravel in Action",
      "author": "John Smith",
      "description": "Complete guide to Laravel framework",
      "price": 299.99,
      "created_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

### Reviews

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/books/{book_id}/reviews` | Get all reviews for a book | No |
| POST | `/api/books/{book_id}/reviews` | Add review to book | Yes |
| PUT | `/api/reviews/{id}` | Update review | Yes (Owner) |
| DELETE | `/api/reviews/{id}` | Delete review | Yes (Owner) |

**Create Review Request Body:**
```json
{
  "rating": 5,
  "comment": "Excellent book! Highly recommended."
}
```

**Get Reviews Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "book_id": 1,
      "rating": 5,
      "comment": "Excellent book!",
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

### Authentication Header
For protected endpoints, include the token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

## 🗄️ Database Schema

### Users Table
```sql
- id (Primary Key)
- name (VARCHAR)
- email (VARCHAR, Unique)
- password (VARCHAR, Hashed)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Books Table
```sql
- id (Primary Key)
- title (VARCHAR)
- author (VARCHAR)
- description (TEXT)
- price (DECIMAL)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Reviews Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- book_id (Foreign Key → books.id)
- rating (INTEGER, 1-5)
- comment (TEXT)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## 🔐 Security Features

- ✅ Password hashing using bcrypt
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention via Eloquent ORM
- ✅ Token-based API authentication
- ✅ Authorization middleware for protected routes
- ✅ Input validation and sanitization

## 🧪 Testing

Run tests using PHPUnit:
```bash
php artisan test
```

## 📁 Project Structure

```
Book-review-project/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # API and Web Controllers
│   │   └── Middleware/     # Custom Middleware
│   ├── Models/             # Eloquent Models
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Database Migrations
│   └── seeders/            # Database Seeders
├── routes/
│   ├── api.php            # API Routes
│   └── web.php            # Web Routes
├── resources/
│   └── views/             # Blade Templates
└── public/                # Public Assets
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

## 👤 Author

**Youssef Negm**

- GitHub: [@youssefNegm-BD](https://github.com/youssefNegm-BD)

## 📧 Contact

For any questions or suggestions, please feel free to reach out!

---

⭐ If you found this project helpful, please give it a star!
