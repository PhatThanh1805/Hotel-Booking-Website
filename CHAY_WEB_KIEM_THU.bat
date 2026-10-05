@echo off
chcp 65001 >nul
title HOTEL BOOKING - HỆ THỐNG MÁY CHỦ KIỂM THỬ LOCAL
color 0A

echo ===============================================================================
echo        🏨 HỆ THỐNG KIỂM THỬ ĐỒ ÁN WEBSITE ĐẶT PHÒNG KHÁCH SẠN
echo        👨‍💻 PHÂN HỆ PHỤ TRÁCH: QUẢN LÝ PHÒNG NGHỈ (ROOM MANAGEMENT)
echo        👤 SINH VIÊN: ĐÀO TẤN PHÁT
echo ===============================================================================
echo.

REM 1. Kiểm tra và kích hoạt MySQL Server (Port 3306)
echo [*] [1/3] Đang kiểm tra cơ sở dữ liệu MySQL (Port 3306)...
netstat -ano | findstr :3306 | findstr LISTENING >nul
if %errorlevel% equ 0 goto MYSQL_READY

echo [!] MySQL chưa chạy. Đang tự động kích hoạt MySQL 8.0 từ E:\School\KiemThu\mysql ...
start "" /B "E:\School\KiemThu\mysql\bin\mysqld.exe" --defaults-file="E:\School\KiemThu\mysql\my.ini" --console
echo [*] Đang đợi MySQL khởi động hoàn tất (khoảng 4-8 giây)...
set /a RETRIES=0

:WAIT_MYSQL
ping 127.0.0.1 -n 2 >nul
netstat -ano | findstr :3306 | findstr LISTENING >nul
if %errorlevel% equ 0 goto MYSQL_DONE
set /a RETRIES+=1
if %RETRIES% leq 15 goto WAIT_MYSQL
echo [!] CẢNH BÁO: MySQL phản hồi chậm hoặc đang khởi động, tiếp tục sang bước PHP...
goto PHP_CHECK

:MYSQL_READY
echo [+] MySQL Server đang hoạt động sẵn sàng trên cổng 3306!
goto PHP_CHECK

:MYSQL_DONE
echo [+] Đã kích hoạt MySQL Server thành công trên cổng 3306!

:PHP_CHECK
REM 2. Kiểm tra PHP Binary
echo.
echo [*] [2/3] Đang kiểm tra môi trường PHP...
set PHP_BIN=
where php >nul 2>nul
if %errorlevel% equ 0 (
    set PHP_BIN=php
    goto PHP_READY
)
if exist "C:\Users\%USERNAME%\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" (
    set "PHP_BIN=C:\Users\%USERNAME%\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
    goto PHP_READY
)
if exist "C:\xampp\php\php.exe" (
    set "PHP_BIN=C:\xampp\php\php.exe"
    goto PHP_READY
)

echo [X] LỖI: Không tìm thấy PHP trong PATH hoặc C:\xampp\php.
echo Vui lòng cài đặt PHP hoặc XAMPP trước khi chạy.
echo.
pause
exit /b 1

:PHP_READY
echo [+] PHP Engine: Sẵn sàng!

REM 3. Mở trình duyệt Web Admin và Khách hàng
echo.
echo [*] [3/3] Đang mở trình duyệt kiểm thử...
start "" "http://localhost:8000/admin/rooms.php"
start "" "http://localhost:8000/rooms.php"

echo.
echo ===============================================================================
echo   🌐 THÔNG TIN TRUY CẬP VÀ DEMO TRƯỚC GIÁO VIÊN:
echo -------------------------------------------------------------------------------
echo   👉 TRANG QUẢN TRỊ ADMIN (PHÒNG NGHỈ): http://localhost:8000/admin/rooms.php
echo   🔑 TÀI KHOẢN ADMIN: amey       ^| MẬT KHẨU: 12345
echo   🔑 HOẶC ADMIN PHỤ:  neal       ^| MẬT KHẨU: 12345
echo.
echo   👉 TRANG KHÁCH HÀNG (CLIENT BOOKING): http://localhost:8000/rooms.php
echo.
echo   📌 MẸO DEMO TEST CASE THEO BẢNG TEST DESIGN (ĐÀO TẤN PHÁT):
echo      1. Thêm phòng: Bấm 'Thêm Phòng Mới', thử bỏ trống tên phòng ==^> Thấy viền đỏ.
echo      2. Đổi trạng thái: Bấm 'Đổi Trạng Thái' ==^> Chọn 2. Đang Dọn Dẹp ==^> Thấy badge vàng.
echo      3. Kiểm tra Client: F5 trang rooms.php khách hàng ==^> Phòng đang dọn dẹp biến mất.
echo      4. Khôi phục: Bấm 'Đổi Trạng Thái' ==^> 1. Phòng Đang Trống ==^> Phòng xuất hiện lại!
echo ===============================================================================
echo.
echo [*] Khởi chạy máy chủ PHP Web Server tại: http://localhost:8000 ...
echo [!] Nhấn Ctrl + C để dừng máy chủ.
echo.

%PHP_BIN% -S localhost:8000 -t "%~dp0Hotel-Booking-Website\hotelbooking"

if %errorlevel% neq 0 (
    echo.
    echo [!] Máy chủ PHP đã dừng lại.
    pause
)