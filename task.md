# 📋 Bảng Theo Dõi Tiến Độ Nâng Cấp Website Đặt Phòng Khách Sạn (Full Tiếng Việt & UI/UX)

## 📌 Tổng Quan Dự Án
Chuyển đổi toàn bộ website sang **Tiếng Việt**, nâng cấp trải nghiệm người dùng (**UI/UX**), chuẩn hóa các nhóm chức năng cho **User (Khách hàng, Nhân viên)** và **Admin (Quản lý phòng 4 trạng thái, Đơn đặt, Phản hồi, Thống kê)**, đồng thời chuẩn bị tài liệu **Use Case** và **Kịch bản kiểm thử (Test Cases)**.

---

## 🎯 Chi Tiết Các Giai Đoạn (Phases)

- [x] **Giai đoạn 1: Chuẩn hóa Design System UI/UX & Việt hóa Triệt để Hệ thống (Header, Navigation, Footer, Body, Modals, Alerts, Database, Passwords & Booking)**
  - [x] Việt hóa & nâng cấp UI Header customer-facing (`hotelbooking/inc/header.php`)
  - [x] Việt hóa & nâng cấp UI Footer customer-facing (`hotelbooking/inc/footer.php`)
  - [x] Việt hóa triệt để Body & các trang Khách hàng (`index.php`, `rooms.php`, `ajax/rooms.php`, `room_details.php`, `facilities.php`, `contact.php`, `about.php`, `confirm_booking.php`, `pay_status.php`)
  - [x] Tích hợp nút **Bật/Tắt Hiển thị Mật khẩu (👁️ Show/Hide)** cho tất cả form nhập mật khẩu
  - [x] Thêm thông tin & ô hiển thị **Thời gian thực hiện đặt phòng** khi đặt phòng (`confirm_booking.php`, `bookings.php`)
  - [x] Nạp dữ liệu mặc định Tiếng Việt vào Database (`settings`, `features`, `facilities`, `rooms`)
  - [x] Nâng cấp CSS Design System, typography Be Vietnam Pro, glassmorphism, responsive (`hotelbooking/css/common.css`)
  - [x] Việt hóa & nâng cấp UI Admin Login & Header/Sidebar (`hotelbooking/admin/index.php`, `hotelbooking/admin/inc/header.php`)
  - [x] Việt hóa thông báo Javascript & Alerts hệ thống (`hotelbooking/admin/inc/scripts.php`, `hotelbooking/inc/footer.php`)

- [x] **Giai đoạn 2: Việt hóa & Nâng cấp Chức năng phía User (Khách hàng & Nhân viên) & Chuẩn hóa Admin Layout Body**
  - [x] Quản lý tài khoản User: Đăng ký, Đăng nhập, Hồ sơ cá nhân (`profile.php`, `ajax/login_register.php`, `ajax/profile.php`)
  - [x] Tìm kiếm & Bộ lọc đặt phòng: Theo ngày nhận/trả phòng, số người (`index.php`, `rooms.php`, `ajax/rooms.php`)
  - [x] Hoàn thiện Cổng thanh toán trực tuyến đa phương thức (VietQR, MoMo, VNPay, Tiền mặt/Thẻ) (`confirm_booking.php`, `pay_now.php`)
  - [x] Tự động cấp & **Xuất Hóa đơn điện tử dạng PDF (Tiếng Việt)** chuẩn mực ngay sau khi thanh toán thành công (`pay_status.php`, `generate_pdf.php`, `bookings.php`)
  - [x] Phân quyền & Quản lý tài khoản **Nhân viên / Khách hàng** trong DB `user_cred` & Giao diện Admin (`admin/users.php`, `admin/ajax/users.php`)
  - [x] **Sửa lỗi tỷ lệ hiển thị Admin Layout**: Cấu hình CSS chuẩn chống tràn chiều rộng khung hình (`admin/css/common.css`)
  - [x] **Việt hóa 100% phần Body Admin**: Dashboard, Thống kê đơn phòng, Doanh thu (VNĐ), Quản lý người dùng, Đơn đặt mới, Hoàn tiền & Lịch sử đặt phòng (`admin/dashboard.php`, `admin/new_bookings.php`, `admin/refund_bookings.php`, `admin/booking_records.php`)

- [x] **Giai đoạn 3: Chuẩn hóa Admin - Quản lý Phòng & 4 Trạng thái Chi tiết**
  - [x] Nâng cấp Model 4+1 trạng thái phòng chi tiết:
    - 🟢 `1. Phòng đang trống` (Available)
    - 🟡 `2. Đang dọn dẹp` (Cleaning)
    - 🔴 `3. Phòng đang sử dụng` (Occupied)
    - 🔵 `4. Phòng đã được đặt` (Booked)
    - ⚪ `0. Tạm dừng / Bảo trì` (Maintenance)
  - [x] Việt hóa & nâng cấp giao diện Admin Quản lý phòng (`hotelbooking/admin/rooms.php`, `hotelbooking/admin/ajax/rooms.php`, `hotelbooking/admin/scripts/rooms.js`)
  - [x] Thiết kế 6 Thẻ Metric Counters đếm số lượng phòng theo trạng thái realtime ở đầu trang
  - [x] Bộ lọc Tab chuyển đổi 4 trạng thái phòng & Ô tìm kiếm tên phòng thời gian thực
  - [x] Tích hợp Badges trực quan & Dropdown menu "Đổi Trạng Thái" nhanh 1-Click tại mỗi hàng phòng nghỉ

- [x] **Giai đoạn 4: Chuẩn hóa Admin - Quản lý Đơn Đặt Phòng, Lịch Công Suất Realtime & Module Khai Báo Lưu Trú**
  - [x] **Chức năng 4.1 - Quản lý Đơn đặt & Xuất Hóa đơn PDF**: Đơn mới, Nhận/Trả phòng, Đã đặt, Đơn hủy & Hoàn tiền (`new_bookings.php`, `booking_records.php`, `refund_bookings.php`, `generate_pdf.php`)
  - [x] **Chức năng 4.2 - Lịch Trạng Thái & Công Suất Phòng Theo Ngày (Room Occupancy Calendar)**:
    - [x] Xây dựng giao diện Lịch theo ngày/tháng (`admin/booking_calendar.php`) hiển thị số phòng trống & số phòng đã được đặt từng ngày
    - [x] Click vào ô ngày để xem danh sách các đơn đặt phòng, mã đơn `ORD_...` trong ngày đó
    - [x] Click trực tiếp vào ID / Tên khách hàng để mở Modal xem chi tiết hồ sơ & thông tin đơn đặt của khách đó (`view_booking_modal`)
  - [x] **Chức năng 4.3 - Module Khai Báo Lưu Trú Cho Đoàn Khách (Khai báo tạm trú Công an / 10+ người)**:
    - [x] Tạo bảng DB `booking_guests` lưu thông tin danh tính từng người lưu trú
    - [x] **Tích hợp Quét Mã QR trên Thẻ Căn Cước đa phương thức (CCCD QR Multi-method Scanner Auto-fill)**:
      - 📁 **Phương thức 1**: Tải ảnh thẻ CCCD / Ảnh mã QR từ máy tính (Tự động giải mã QR từ file ảnh)
      - 📷 **Phương thức 2**: Bật Camera / Webcam quét live mã QR thời gian thực
      - ⌨️ **Phương thức 3**: Nhập/dán chuỗi từ Máy quét barcode chuyên dụng
    - [x] Giao diện Khách hàng (`bookings.php`): Cho phép khách hàng tự khai báo trước danh sách người đi cùng trực tuyến bằng Ảnh/Camera/QR
    - [x] Giao diện Admin (`new_bookings.php`, `booking_records.php`): Lễ tân kiểm tra, thêm/sửa/quét mã QR CCCD cho đoàn khi nhận phòng
    - [x] Xuất Mẫu Báo Cáo Khai Báo Lưu Trú (PDF/Excel) chuẩn quy định thông báo tạm trú cho Công an địa phương (`generate_guest_report.php`)
  - [x] **Chức năng 4.4 - Quản lý phản hồi**: Tin nhắn liên hệ (`user_queries.php`), Đánh giá & Bình luận khách hàng (`rate_review.php`)

- [x] **Giai đoạn 5: Chuẩn hóa Admin - Thống kê & Dashboard trực quan UI/UX**
  - [x] Thiết kế lại Dashboard thống kê doanh thu, tỷ lệ lấp đầy phòng, đơn đặt phòng (`admin/dashboard.php`, `admin/ajax/dashboard.php`)
  - [x] Tích hợp Card chỉ số trực quan, biểu đồ thống kê trực quan (Analytics counter & Charts với Chart.js dual axis & Doughnut charts)

- [x] **Giai đoạn 6: Đóng gói Use Cases & Tài liệu Kịch bản Kiểm thử (Test Cases)**
  - [x] Biên soạn tài liệu Use Cases cho toàn bộ hệ thống (User & Admin) (`docs/USE_CASES.md`)
  - [x] Biên soạn Bảng Kịch bản Kiểm thử chi tiết (.xlsx & .docx) chuẩn tài liệu mẫu (`docs/TestDesign_HotelBooking.xlsx`, `docs/TestPlan_HotelBooking.docx`)
  - [x] Đóng gói Trang Báo cáo Kiểm thử Tương tác Web Dashboard (`docs/Test_Report_Dashboard.html`) & Bảng Markdown (`docs/TEST_CASES.md`)
