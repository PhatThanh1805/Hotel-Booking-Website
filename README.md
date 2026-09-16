# 🏨 Dự Án Hệ Thống Đặt Phòng Khách Sạn (Hotel Booking System) - Môn Kiểm Thử Phần Mềm

Dự án website đặt phòng khách sạn phục vụ bài tập lớn & thực hành môn **Kiểm thử Phần mềm (Software Testing)**.

---

## 🛠️ Công Nghệ Sử Dụng (Tech Stack)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5, SwiperJS
- **Backend**: PHP Native
- **Database**: MySQL / MariaDB (Import file `Database/hotelbooking.sql`)
- **Server**: Apache / XAMPP / Laragon hoặc PHP Built-in Web Server

---

## 🚀 Hướng Dẫn Cài Đặt & Khởi Chạy Local (Cho Thành Viên Team)

### Bước 1: Clone Repository
```bash
git clone https://github.com/<User_Name>/Hotel-Booking-Website.git
cd Hotel-Booking-Website
```

### Bước 2: Import Cơ Sở Dữ Liệu
1. Mở **XAMPP / Laragon** hoặc **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Tạo mới một cơ sở dữ liệu có tên: `hotelbooking`.
3. Bấm tab **Import (Nhập)** -> Chọn file `Database/hotelbooking.sql` nằm trong dự án -> Bấm **Go (Thực hiện)**.

### Bước 3: Cấu Hình Database (Nếu Cần)
Mở file `hotelbooking/admin/inc/db_config.php` kiểm tra thông số kết nối:
```php
  $hname = 'localhost';
  $uname = 'root';
  $pass = ''; // Mật khẩu MySQL của bạn (XAMPP mặc định để trống)
  $db = 'hotelbooking';
```

### Bước 4: Khởi Chạy Web Server

#### Cách 1: Chạy bằng 1-Click Script (`start_server.bat`)
Nhấp đúp file **`start_server.bat`** trong thư mục dự án.

#### Cách 2: Chạy lệnh PHP CLI
```bash
php -S localhost:8000 -t ./hotelbooking
```

#### Cách 3: Copy vào XAMPP `htdocs`
Copy thư mục `hotelbooking` vào `C:\xampp\htdocs\` và truy cập `http://localhost/hotelbooking/`.

---

## 🌐 Đường Dẫn Đăng Nhập & Truy Cập

- 👤 **Trang Khách Hàng**: [http://localhost:8000/index.php](http://localhost:8000/index.php)
- 🔐 **Trang Quản Trị (Admin Portal)**: [http://localhost:8000/admin/index.php](http://localhost:8000/admin/index.php)
  - **Tài khoản Admin**: `amey`
  - **Mật khẩu Admin**: `12345`

---

## 🧪 Các Danh Mục Phục Vụ Kiểm Thử (Testing Modules)

Dự án phân chia sẵn các module để anh em trong team nhận kịch bản test (Test Plan / Test Cases / Automation Test):

1. **Module 1: Quản lý Tài khoản (User Authentication)**
   - Đăng ký tài khoản mới (`Register`)
   - Đăng nhập / Đăng xuất (`Login / Logout`)
   - Khôi phục mật khẩu

2. **Module 2: Tìm kiếm & Đặt phòng (Booking System)**
   - Tìm phòng theo ngày Check-in/Check-out, số lượng người lớn/trẻ em
   - Xem chi tiết từng phòng & đánh giá
   - Đặt phòng & Xác nhận đơn hàng

3. **Module 3: Quản trị Hệ thống (Admin Portal)**
   - Quản lý danh sách phòng (Thêm, Sửa, Xóa phòng, Bật/Tắt trạng thái)
   - Quản lý Banner Carousel trang chủ
   - Quản lý người dùng, phản hồi liên hệ & đơn đặt phòng
