# 🏆 Web API Quản lý Hội Viên  
(Membership Management Web API)  

## 📌 Tóm Tắt Dự Án  
Đây là hệ thống API RESTful phục vụ cho website quản lý hội viên. API cung cấp các chức năng như quản lý hội viên, tài liệu, câu lạc bộ, hội phí, hoạt động và thông báo.  

## 🚀 Tính Năng Chính  
- **Xác thực & Phân quyền** (Laravel Sanctum)  
- **Quản lý hội viên**: Đăng ký, đăng nhập, cập nhật, phân quyền  
- **Quản lý tài liệu**: Lưu trữ, tải xuống  
- **Quản lý khách hàng & đối tác**  
- **Quản lý câu lạc bộ & hội phí**  
- **Quản lý hoạt động, thông báo, lịch họp**  

## 🛠 Công Nghệ Sử Dụng  
- **Ngôn ngữ**: PHP  
- **Framework**: Laravel  
- **Cơ sở dữ liệu**: MySQL  
- **Xác thực**: Laravel Sanctum  
- **Tài liệu API**: Swagger  

## 📂 Cài Đặt & Chạy Dự Án  
### 1️⃣ Clone Repository  
```bash  
git clone https://github.com/Dat0801/web-api-quan-ly-hoi-vien.git
cd web-api-quan-ly-hoi-vien  
```
### 2️⃣ Cài Đặt Dependencies  
```bash  
composer install  
```
### 3️⃣ Cấu Hình `.env`  
Sao chép file `.env.example` và cập nhật thông tin database:  
```bash  
cp .env.example .env  
```
Chỉnh sửa `.env`:  
```env  
DB_DATABASE=ten_database  
DB_USERNAME=root  
DB_PASSWORD=your_password  
```
### 4️⃣ Tạo Key & Migrate Database  
```bash  
php artisan key:generate  
php artisan migrate --seed  
```
### 5️⃣ Chạy Server  
```bash  
php artisan serve  
```
API sẽ chạy tại `http://127.0.0.1:8000`.  
