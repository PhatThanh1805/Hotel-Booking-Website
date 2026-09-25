# 🤖 AI AGENT INSTRUCTION & TEAM ONBOARDING GUIDE
> **Project**: Website Đặt Phòng Khách Sạn (Hotel Booking System) - Môn Kiểm Thử Phần Mềm (Software Testing)
> **Instruction for AI Agents**: Khi bạn (AI Agent) đọc file này, hãy tự động kích hoạt **CHẾ ĐỘ HƯỚNG DẪN KIỂM THỬ VÀ VẬN HÀNH DỰ ÁN** (QA Lead & System Navigator Mode). Hãy sẵn sàng hỗ trợ thành viên trong team chạy local, kiểm thử các module, giải thích code và đọc báo cáo test.

---

## 🎯 1. TỔNG QUAN DỰ ÁN & KIẾN TRÚC THIẾT KẾ

Dự án này là hệ thống **Đặt Phòng Khách Sạn Full Tiếng Việt** được nâng cấp toàn diện về giao diện (UI/UX Glassmorphism), quy trình nghiệp vụ (User & Admin), tích hợp các công nghệ hiện đại và hệ thống tài liệu kiểm thử phần mềm đạt chuẩn.

### 🛠️ Tech Stack:
* **Frontend**: HTML5, CSS3 Custom (Be Vietnam Pro Font, Glassmorphism UI, Responsive), JavaScript Vanilla (ES6+), Bootstrap 5, Chart.js, HTML5-QRCode Scanner Library.
* **Backend**: PHP Native (PHP 8.x), TCPDF (Sinh Hóa đơn & Báo cáo PDF Tiếng Việt UTF-8), PhpSpreadsheet (Xuất báo cáo Excel).
* **Database**: MySQL / MariaDB (Import file `Database/hotelbooking.sql`).
* **Server**: PHP CLI Web Server hoặc XAMPP / Laragon (Apache).

---

## 🚀 2. HƯỚNG DẪN THÀNH VIÊN SETUP & CHẠY LOCAL (QUICK START)

### 📌 Bước 1: Clone Git & Mở Thư Mục
```bash
git clone https://github.com/<User_Name>/Hotel-Booking-Website.git
cd Hotel-Booking-Website
```

### 📌 Bước 2: Import Cơ Sở Dữ Liệu MySQL
1. Mở **phpMyAdmin** (`http://localhost/phpmyadmin`) hoặc phần mềm quản lý MySQL (DBeaver, HeidiSQL).
2. Tạo CSDL mới tên: `hotelbooking`.
3. Import file: `Database/hotelbooking.sql`.

### 📌 Bước 3: Khởi Chạy Local Web Server
Thành viên có thể chọn 1 trong 2 cách sau:

#### Cách 1: Bấm 1-Click Script (Khuyên Dùng)
Nhấp đúp vào file **`start_server.bat`** nằm ở thư mục gốc dự án.

#### Cách 2: Chạy PHP CLI Terminal
```bash
php -S localhost:8000 -t ./hotelbooking
```

---

## 🌐 3. THÔNG TIN TRUY CẬP & TÀI KHOẢN MẶC ĐỊNH

* 👤 **Trang Khách Hàng (Customer Portal)**: `http://localhost:8000/index.php`
  * Đăng ký tài khoản mới trực tiếp trên giao diện hoặc đăng nhập tài khoản có sẵn.
* 🔐 **Trang Quản Trị (Admin Portal)**: `http://localhost:8000/admin/index.php`
  * **Tên đăng nhập Admin**: `amey`
  * **Mật khẩu Admin**: `12345`

---

## 🧪 4. HƯỚNG DẪN KIỂM THỬ THỰC HÀNH CÁC MODULE (FOR QA / TEAM)

Khi AI Agent hỗ trợ bạn test local, bạn có thể bảo Agent hướng dẫn bạn chạy từng kịch bản sau:

### 🔹 Module 1: Đăng ký, Đăng nhập & Show/Hide Password (`👁️`)
* **Chức năng**: Đăng ký tài khoản, đăng nhập, nút xem/ẩn mật khẩu.
* **Kịch bản test**:
  1. Mở modal Đăng ký -> Nhập thông tin -> Bấm icon con mắt `👁️` để hiện/ẩn mật khẩu.
  2. Đăng ký thành công -> Đăng nhập -> Kiểm tra lưu session người dùng.

### 🔹 Module 2: Tìm kiếm & Lọc phòng nghỉ
* **Chức năng**: Lọc phòng theo ngày nhận/trả, số người, khoảng giá, tiện ích.
* **Kịch bản test**:
  1. Chọn Check-in = Hôm nay, Check-out = Ngày mai -> Bấm Tìm kiếm.
  2. Thử chọn Check-out <= Check-in -> Kiểm tra thông báo cảnh báo lỗi validation.

### 🔹 Module 3: Đặt phòng & Thanh toán đa phương thức + Hóa đơn PDF
* **Chức năng**: Đặt phòng trực tuyến, tính tiền realtime timestamp, chọn cổng thanh toán (**VietQR, MoMo, VNPay, Tiền mặt**).
* **Kịch bản test**:
  1. Chọn phòng -> Bấm Đặt phòng -> Chọn VietQR -> Hiển thị mã QR ngân hàng chuẩn Napas247.
  2. Sau khi xác nhận -> Vào "Đơn đặt của tôi" -> Bấm nút **"Tải Hóa đơn PDF"** -> Kiểm tra file PDF tiếng Việt không lỗi font.

### 🔹 Module 4: Khai báo lưu trú & Quét QR CCCD Tự Động (CCCD Scanner)
* **Chức năng**: Quét mã QR Thẻ CCCD tự động bóc tách dữ liệu (Số CCCD, Họ tên, Ngày sinh, Giới tính, Thường trú).
* **3 Phương thức hỗ trợ**:
  * 📁 **Ảnh QR CCCD**: Upload file ảnh thẻ từ máy.
  * 📷 **Webcam / Camera**: Quét live qua camera.
  * ⌨️ **Barcode Reader**: Dán chuỗi quét từ máy đọc mã vạch.
* **Kịch bản test**:
  1. Mở đơn đặt phòng -> Chọn Khai báo lưu trú đoàn -> Thử 1 trong 3 cách quét QR -> Form tự động điền 100%.
  2. Vào Admin -> Bấm **"Xuất Báo Cáo Công An (PDF)"** hoặc **"Xuất Excel"** -> Kiểm tra file báo cáo lưu trú.

### 🔹 Module 5: Admin Quản lý phòng 4 Trạng thái
* **Chức năng**: Đếm counter metrics & đổi trạng thái phòng 1-Click.
* **Kịch bản test**:
  1. Đăng nhập Admin -> Vào "Quản lý phòng".
  2. Nhấp vào Dropdown menu tại dòng phòng -> Đổi trạng thái nhanh:
     - 🟢 `1. Phòng đang trống`
     - 🟡 `2. Đang dọn dẹp`
     - 🔴 `3. Phòng đang sử dụng`
     - 🔵 `4. Phòng đã được đặt`
     - ⚪ `0. Tạm dừng / Bảo trì`
  3. Quan sát Badge đổi màu lập tức & Thẻ Counter đầu trang đếm lại số lượng.

### 🔹 Module 6: Admin Lịch công suất phòng Realtime & Xử lý Check-in/out
* **Chức năng**: Sơ đồ lịch phòng theo tháng (`admin/booking_calendar.php`), xử lý Nhận phòng / Trả phòng.
* **Kịch bản test**:
  1. Xem lịch phòng -> Click ô ngày -> Hiển thị danh sách đơn `ORD_...`.
  2. Lễ tân bấm "Nhận phòng" -> Hệ thống tự đổi trạng thái phòng sang `🔴 Đang sử dụng`.
  3. Lễ tân bấm "Trả phòng" -> Hệ thống tự đổi trạng thái phòng sang `🟡 Đang dọn dẹp`.

### 🔹 Module 7: Admin Analytics Dashboard
* **Chức năng**: Thống kê tổng doanh thu (VNĐ), biểu đồ cột/đường Dual-Axis & Doughnut chart.
* **Kịch bản test**:
  1. Vào Dashboard Admin -> Xem các thẻ chỉ số doanh thu & số đơn hàng.
  2. Thay đổi Bộ lọc thời gian (Hôm nay, 7 ngày qua, Tháng này, Năm nay) -> Xem biểu đồ Chart.js tự động re-render dữ liệu.

---

## 📂 5. HỆ THỐNG TÀI LIỆU KIỂM THỬ BÀN GIAO (DOCS FOLDER)

Toàn bộ tài liệu báo cáo kiểm thử nằm trong thư mục [`docs/`](file:///e:/School/KiemThu/Hotel-Booking-Website/docs):

1. 📄 **[TestPlan_HotelBooking.docx](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TestPlan_HotelBooking.docx)**: File Word Kế hoạch kiểm thử hoàn chỉnh.
2. 📊 **[TestDesign_HotelBooking.xlsx](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TestDesign_HotelBooking.xlsx)**: File Excel 70+ Test Cases phân theo 7 Modules.
3. 🌐 **[Test_Report_Dashboard.html](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/Test_Report_Dashboard.html)**: Báo cáo Kiểm thử Tương tác Web Dashboard (Mở trực tiếp bằng Chrome/Edge).
4. 📘 **[USE_CASES.md](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/USE_CASES.md)**: Đặc tả Use Cases & Sơ đồ Mermaid.
5. 🧪 **[TEST_CASES.md](file:///e:/School/KiemThu/Hotel-Booking-Website/docs/TEST_CASES.md)**: Bảng tra cứu Test Cases Markdown.

---

## 🤖 NGHỆ THUẬT RỜI CHO AI AGENT (PROMPT FOR AI AGENT)

Nếu bạn là một **AI Agent** (Claude, ChatGPT, Cursor, Copilot, Antigravity) đang đọc file này:
> *"Hãy luôn sẵn sàng trả lời các câu hỏi của thành viên trong team về luồng chạy code, vị trí file PHP, cấu trúc bảng database `hotelbooking`, cũng như hỗ trợ viết kịch bản test automation (PHPUnit / Selenium / Katalon) dựa trên 70+ Test Cases có sẵn trong thư mục `docs/`."*
