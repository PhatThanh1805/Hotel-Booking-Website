@echo off
chcp 65001 >nul
title HOTEL BOOKING - DEV & TEST SERVER
color 0A

echo ===============================================================================
echo        🏨 HOTEL BOOKING WEBSITE - LOCAL SERVER LAUNCHER
echo        👨‍💻 PHỤ TRÁCH: ĐÀO TẤN PHÁT (MODULE: QUẢN LÝ PHÒNG NGHỈ)
echo ===============================================================================
echo.

REM 1. Kiểm tra và kích hoạt MySQL
echo [*] Kiểm tra MySQL (Port 3306)...
netstat -ano | findstr :3306 | findstr LISTENING >nul
if %errorlevel% equ 0 goto MYSQL_READY

echo [!] MySQL chưa chạy. Đang khởi động MySQL từ E:\School\KiemThu\mysql ...
start "" /B "E:\School\KiemThu\mysql\bin\mysqld.exe" --defaults-file="E:\School\KiemThu\mysql\my.ini" --console
echo [*] Đang đợi MySQL khởi động hoàn tất (khoảng 4-8 giây)...
set /a RETRIES=0

:WAIT_MYSQL
ping 127.0.0.1 -n 2 >nul
netstat -ano | findstr :3306 | findstr LISTENING >nul
if %errorlevel% equ 0 goto MYSQL_DONE
set /a RETRIES+=1
if %RETRIES% leq 15 goto WAIT_MYSQL
echo [!] CẢNH BÁO: MySQL phản hồi chậm, tiếp tục bước tiếp theo...
goto PHP_CHECK

:MYSQL_READY
echo [+] MySQL Server đang chạy sẵn sàng trên cổng 3306!
goto PHP_CHECK

:MYSQL_DONE
echo [+] MySQL đã khởi động thành công!

:PHP_CHECK
REM 2. Kiểm tra PHP
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

REM 3. Mở trình duyệt
echo.
echo [*] Mở trình duyệt Web Admin Quản lý phòng và Web Khách hàng...
start "" "http://localhost:8000/admin/rooms.php"
start "" "http://localhost:8000/rooms.php"

echo.
echo ===============================================================================
echo   👉 TRANG ADMIN QUẢN LÝ PHÒNG: http://localhost:8000/admin/rooms.php
echo   🔑 ĐĂNG NHẬP: amey / 12345
echo   👉 TRANG KHÁCH HÀNG:          http://localhost:8000/rooms.php
echo ===============================================================================
echo.
echo [*] Khởi động PHP Web Server tại: http://localhost:8000 ...
echo.

%PHP_BIN% -S localhost:8000 -t "%~dp0hotelbooking"

if %errorlevel% neq 0 (
    echo.
    echo [!] Máy chủ PHP đã dừng lại.
    pause
)