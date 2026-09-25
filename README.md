# 🏨 Dự Án Website Đặt Phòng Khách Sạn (Hotel Booking System) - Môn Kiểm Thử Phần Mềm

![PHP Native](https://img.shields.io/badge/Backend-PHP_8.x-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL_MariaDB-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap 5](https://img.shields.io/badge/Frontend-Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap)
![Glassmorphism](https://img.shields.io/badge/UI/UX-Glassmorphism_Full_Tiếng_Việt-0066CC?style=for-the-badge)
![Testing Complete](https://img.shields.io/badge/QA_Testing-70%2B_Test_Cases_PASS-137333?style=for-the-badge)

Dự án website đặt phòng khách sạn phục vụ bài tập lớn & thực hành môn **Kiểm thử Phần mềm (Software Testing)**. Hệ thống đã được nâng cấp toàn diện 6 Giai đoạn (Full Tiếng Việt, UI/UX Glassmorphism, Quét QR CCCD đa phương thức, Quản lý phòng 4 trạng thái, Lịch công suất realtime, Analytics Dashboard & Bộ tài liệu Test Plan / Test Cases chuẩn mực).

> 🤖 **DÀNH CHO AI AGENT (CLAUDE / GEMINI / CURSOR / COPILOT)**: Vui lòng đọc file [`AI_AGENT_GUIDE.md`](file:///e:/School/KiemThu/Hotel-Booking-Website/AI_AGENT_GUIDE.md) để kích hoạt **Chế Độ Hướng Dẫn Vận Hành & Kiểm Thử Dự Án** hỗ trợ thành viên trong team.

---

## 🚀 1. HƯỚNG DẪN CÀI ĐẶT & KHỞI CHẠY LOCAL (QUICK START)

### Bước 1: Clone Repository
```bash
git clone https://github.com/<User_Name>/Hotel-Booking-Website.git
cd Hotel-Booking-Website
```

### Bước 2: Import Cơ Sở Dữ Liệu
1. Mở **XAMPP / Laragon** hoặc **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Tạo mới cơ sở dữ liệu có tên: `hotelbooking`.
3. Bấm tab **Import (Nhập)** -> Chọn file `Database/hotelbooking.sql` nằm trong dự án -> Bấm **Go (Thực hiện)**.

### Bước 3: Khởi Chạy Web Server

#### ⚡ Cách 1: Chạy bằng 1-Click Script (Khuyên Dùng)
Nhấp đúp file **`start_server.bat`** nằm ở thư mục gốc dự án.

#### ⚡ Cách 2: Chạy lệnh PHP CLI Terminal
```bash
php -S localhost:8000 -t ./hotelbooking
```

---

## 🌐 2. ĐƯỜNG DẪN TRUY CẬP & TÀI KHOẢN MẶC ĐỊNH

- 👤 **Trang Khách Hàng (Customer Portal)**: [http://localhost:8000/index.php](http://localhost:8000/index.php)
  - Đăng ký tài khoản mới hoặc đăng nhập tài khoản có sẵn.
  - Tích hợp nút **Show/Hide Password (`👁️`)**, Đặt phòng & Thanh toán VietQR / MoMo / VNPay, Quét mã QR CCCD tự động điền form & Tải Hóa đơn PDF.
- 🔐 **Trang Quản Trị (Admin Portal)**: [http://localhost:8000/admin/index.php](http://localhost:8000/admin/index.php)
  - **Tài khoản Admin**: `amey`
  - **Mật khẩu Admin**: `12345`

---

## 📁 3. BỘ TÀI LIỆU KIỂM THỬ ĐÃ BÀN GIAO (`docs/`)

Toàn bộ tài liệu báo cáo kiểm thử hoàn chỉnh được lưu trữ tại thư mục [`docs/`](file:///e:/School/KiemThu/Hotel-Booking-Website/docs):

- 📄 **[TestPlan_HotelBooking.docx](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TestPlan_HotelBooking.docx)**: File Word Kế hoạch kiểm thử (Test Plan) chuẩn mực.
- 📊 **[TestDesign_HotelBooking.xlsx](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TestDesign_HotelBooking.xlsx)**: File Excel **70+ Test Cases** phân theo 7 Modules trọng tâm.
- 🌐 **[Test_Report_Dashboard.html](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/Test_Report_Dashboard.html)**: Báo cáo Kiểm thử Tương tác Web Dashboard (Mở bằng Chrome/Edge).
- 📘 **[USE_CASES.md](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/USE_CASES.md)**: Tài liệu Đặc tả Use Cases & Sơ đồ Mermaid Diagram.
- 🧪 **[TEST_CASES.md](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TEST_CASES.md)**: Bảng tra cứu Test Cases dạng Markdown.
- 📋 **[task.md](file:///e:/School/KiemThu/Hotel-Booking-Website/task.md)**: Bảng Theo Dõi Tiến Độ Nâng Cấp 6 Giai Đoạn Dự Án.

---

## 🧪 4. DANH MỤC CÁC MODULE KIỂM THỬ (TESTING MODULES)

1. **Module 1: Authentication & Profile**: Đăng ký, Đăng nhập, Show/Hide Password (`👁️`), Cập nhật hồ sơ cá nhân.
2. **Module 2: Search & Filtering**: Tìm kiếm phòng theo ngày, validation ngày Check-out > Check-in, lọc tiện ích, chi tiết phòng & đánh giá sao.
3. **Module 3: Booking & Multi-Payment**: Đặt phòng realtime timestamp, thanh toán VietQR / MoMo / VNPay / Tiền mặt quầy & Xuất Hóa đơn PDF Tiếng Việt.
4. **Module 4: CCCD QR Multi-Scanner & Residence**: Quét mã QR CCCD qua 3 phương thức (**Ảnh, Live Camera, Barcode Reader**), auto-fill thông tin & Xuất Báo cáo Tạm trú cho Công an (PDF/Excel).
5. **Module 5: Admin Room Management 4+1 States**: 6 Thẻ metric counters & Đổi trạng thái phòng 1-Click (*Trống 🟢, Dọn dẹp 🟡, Đang sử dụng 🔴, Đã đặt 🔵, Bảo trì ⚪*).
6. **Module 6: Admin Occupancy Calendar & Booking Operations**: Lịch công suất phòng realtime theo ngày/tháng, Check-in, Check-out, Hủy đơn & Hoàn tiền.
7. **Module 7: Admin Analytics Dashboard**: Biểu đồ Dual-Axis doanh thu/đơn hàng, Doughnut chart tỷ lệ lấp đầy phòng.
