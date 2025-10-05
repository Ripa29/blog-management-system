# 📰 Blog Management System

This is a Laravel-based Admin Blog Management System that includes:
- Blog CRUD (Create, Read, Update, Delete)
- Blog Categories
- AJAX-based Delete and Edit operations
- Custom Toast Notifications
- Thumbnail Upload using Laravel Storage
- Search & Filter functionality
- Pagination
- Authentication-based Author tracking

---

## 🚀 Features

✅ Manage Blogs (Create, Edit, Delete, View)  
✅ Manage Blog Categories  
✅ Upload & Replace Thumbnails  
✅ Search Blogs by Title  
✅ Filter Blogs by Category  
✅ Toast Notifications for success messages  
✅ AJAX Delete & Update (No page reload)  
✅ Pagination with Query String  
✅ Blog Author Tracking (via Auth)  

---

## ⚙️ Installation

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/ripa29/blog-management-system.git
cd blog-management-system

2️⃣ Install Dependencies
composer install
npm install

3️⃣ Environment Setup

Copy .env.example to .env

cp .env.example .env


Update the following keys:

APP_NAME="Blog Admin"
APP_URL=http://127.0.0.1:8000

DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

4️⃣ Generate Key
php artisan key:generate

5️⃣ Run Migrations
php artisan migrate

6️⃣ Storage Link (for thumbnails)
php artisan storage:link

7️⃣ Run the Server
php artisan serve

Admin Login
Email: admin@blog.com
Password: password



🧠 Usage Guide
▶️ Access the Admin Panel

Open your browser and go to:

http://127.0.0.1:8000/admin/blogs

📄 Blog CRUD
✳️ Create Blog

Navigate to Blogs > Create New

Fill in title, category, description, thumbnail, and status

Submit to create a new blog post

A success toast will appear

📝 Edit Blog (AJAX)

Click Edit on any blog

Update fields as needed

Submit form → AJAX request updates instantly

Toast shows “Blog updated successfully!”

❌ Delete Blog (AJAX)

Click Delete

Confirmation alert appears

After confirming, blog deletes instantly with success toast

🔍 Search & Filter

Search by blog title or filter by category at the top of the Blog Index page

