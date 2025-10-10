# 📰 **Blog Management System **

A modern and powerful **Laravel-based Blog Management System** with a complete admin panel for managing blogs, categories, likes, and comments all enhanced with **AJAX operations**, **responsive design**, and **Laravel Storage-based image handling**.

##  Preview

### 🖥️ Admin Dashboard  
![Admin Dashboard](public/images/blog-management-system-dashboard.png)

### 🏡 Frontend Blog Page  
![Frontend Blog](public/images/blog-management-system.png)


## 🚀 Features

 **Full Blog CRUD** — Create, Edit, Delete, and View blogs  
 **Category Management** — Add or edit blog categories  
**Author Tracking** — Blogs are linked to authenticated users (authors)  
 **Thumbnail Uploads** — Image upload & storage using Laravel Storage  
 **AJAX Operations** — Smooth Delete & Edit (no page reload)  
 **Toast Notifications** — Beautiful success & error messages  
 **Search & Filter** — Quickly find blogs by title or category  
 **Pagination** — Clean blog listing with query support  
 **Like & Comment System** — Integrated social interaction on each blog  
 **Slug Auto-Generation** — SEO-friendly URLs created automatically  


## ⚙️ Installation Guide

### 1️⃣ Clone the Repository

git clone https://github.com/ripa29/blog-management-system.git
cd blog-management-system

2️⃣ Install Dependencies

composer install
npm install

3️⃣ Environment Setup
Copy .env.example to .env:


APP_NAME="Blog Admin"
APP_URL=http://blog-management-system.test

DB_DATABASE=blog-management-system
DB_USERNAME=root
DB_PASSWORD=your_password

4️⃣ Generate App Key

php artisan key:generate

5️⃣ Run Migrations & Seeders

php artisan migrate --seed

6️⃣ Create Storage Link

php artisan storage:link

7️⃣ Start the Local Server
php artisan serve

🔐 Admin Login
Email: admin@blog.com
Password: password

🧠 Seeder Run 

php artisan db:seed --class=BlogSeeder


🔒 Admin Panel 

You can create an admin dashboard at /admin to:

Manage Blogs

Manage Categories

View Comments

Toggle Blog Status

Create a Blog

Go to Blogs → Create New

Fill in: Title, Category, Description, Thumbnail, and Status

Click Submit → You’ll see a success toast notification.

📝 Edit Blog (AJAX)

Click Edit on any blog.

Update desired fields → Submit form.

Changes update instantly (no page reload).

❌ Delete Blog (AJAX)

Click Delete on a blog.

Confirm the action.

Blog removes instantly with success toast.

🔍 Search & Filter

Use the search bar to find by title.

Use the category dropdown to filter results.

🧑‍💻 Contributing

Fork this repo

Create a feature branch (feature/new-feature)

Commit changes (git commit -m "Add new feature")

Push branch (git push origin feature/new-feature)

Create a Pull Request

