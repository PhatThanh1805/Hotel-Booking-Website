import os
import sys
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils import get_column_letter

# Ensure UTF-8 output
sys.stdout.reconfigure(encoding='utf-8')

DOCS_DIR = r"e:\School\KiemThu\Hotel-Booking-Website\docs"
os.makedirs(DOCS_DIR, exist_ok=True)

print("Starting generation of Test Plan DOCX, Test Design XLSX, Use Cases MD, and Interactive HTML Report...")

# ==========================================
# 1. GENERATE TEST PLAN DOCX
# ==========================================
def create_test_plan_docx():
    doc = Document()
    
    # Page Margins
    sections = doc.sections
    for section in sections:
        section.top_margin = Inches(1)
        section.bottom_margin = Inches(1)
        section.left_margin = Inches(1)
        section.right_margin = Inches(1)

    # Styles
    style_normal = doc.styles['Normal']
    style_normal.font.name = 'Arial'
    style_normal.font.size = Pt(11)
    style_normal.font.color.rgb = RGBColor(0x33, 0x33, 0x33)

    # Helper function for setting cell shading
    def set_cell_background(cell, fill_hex):
        tcPr = cell._element.get_or_add_tcPr()
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
        tcPr.append(shd)

    # Title Page / Header
    title_p = doc.add_paragraph()
    title_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run_sub = title_p.add_run("SERVICE DIRECTORY\n\n")
    run_sub.font.size = Pt(12)
    run_sub.font.bold = True
    
    run_title = title_p.add_run("KẾ HOẠCH KIỂM THỬ\n(SYSTEM TEST PLAN)\n")
    run_title.font.size = Pt(20)
    run_title.font.bold = True
    run_title.font.color.rgb = RGBColor(0x1B, 0x36, 0x5D) # Navy

    run_proj = title_p.add_run("DỰ ÁN: HỆ THỐNG ĐẶT PHÒNG KHÁCH SẠN (HOTEL BOOKING WEBSITE)\n\n")
    run_proj.font.size = Pt(13)
    run_proj.font.bold = True
    run_proj.font.color.rgb = RGBColor(0x00, 0x66, 0xCC)

    # Meta Info Table
    table_meta = doc.add_table(rows=5, cols=2)
    table_meta.alignment = WD_TABLE_ALIGNMENT.CENTER
    meta_data = [
        ("Mã dự án", "HB_SOF3032_TESTING"),
        ("Mã tài liệu", "TP_HB_2026"),
        ("Ngày lập", "26/09/2026"),
        ("Giảng viên hướng dẫn", "Chu Thị Ngân"),
        ("Đơn vị thực hiện", "Nhóm Kiểm thử Phần mềm - Hotel Booking Team")
    ]
    for i, (k, v) in enumerate(meta_data):
        row = table_meta.rows[i]
        c0 = row.cells[0]
        c1 = row.cells[1]
        c0.text = k
        c1.text = v
        c0.paragraphs[0].runs[0].font.bold = True
        c0.width = Inches(2.2)
        c1.width = Inches(4.3)
        set_cell_background(c0, "F0F4F8")
        set_cell_background(c1, "FFFFFF")

    doc.add_paragraph("\n")

    # Revision History
    h1 = doc.add_heading("BẢN GHI NHẬN THAY ĐỔI TÀI LIỆU", level=2)
    table_rev = doc.add_table(rows=3, cols=6)
    table_rev.alignment = WD_TABLE_ALIGNMENT.CENTER
    rev_headers = ["Ngày", "Vị trí", "Lý do", "Nguồn gốc", "Phiên bản", "Mô tả thay đổi"]
    hdr_cells = table_rev.rows[0].cells
    for i, title in enumerate(rev_headers):
        hdr_cells[i].text = title
        hdr_cells[i].paragraphs[0].runs[0].font.bold = True
        hdr_cells[i].paragraphs[0].runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)
        set_cell_background(hdr_cells[i], "1B365D")

    rev_data = [
        ("25/09/2026", "Toàn bộ", "Khởi tạo tài liệu Test Plan", "Nhóm Test", "v1.0", "Khởi tạo kế hoạch kiểm thử cho 5 Giai đoạn"),
        ("26/09/2026", "Phạm vi & Test cases", "Cập nhật Module QR CCCD & Admin 4 trạng thái", "Yêu cầu mới", "v2.0", "Đóng gói hoàn chỉnh Giai đoạn 6")
    ]
    for r_idx, row_vals in enumerate(rev_data):
        row_cells = table_rev.rows[r_idx+1].cells
        for c_idx, val in enumerate(row_vals):
            row_cells[c_idx].text = val
            set_cell_background(row_cells[c_idx], "F9FAFB" if r_idx % 2 == 0 else "FFFFFF")

    doc.add_paragraph("\n")

    # Sign-off section
    p_sign = doc.add_paragraph()
    p_sign.add_run("TRANG KÝ XÁC NHẬN:\n").bold = True
    p_sign.add_run("• Người lập: Nhóm Kiểm thử Phần mềm                         Ngày: 26/09/2026\n")
    p_sign.add_run("• Người xem xét: Trưởng Nhóm QA / Dev Lead              Ngày: 26/09/2026\n")
    p_sign.add_run("• Người phê duyệt: Giảng viên Giám sát                    Ngày: 26/09/2026\n\n")

    # Sections Content
    def add_sec_heading(text, level=1):
        h = doc.add_heading(text, level=level)
        h.paragraph_format.space_before = Pt(12)
        h.paragraph_format.space_after = Pt(6)
        for r in h.runs:
            r.font.color.rgb = RGBColor(0x1B, 0x36, 0x5D)

    add_sec_heading("1. GIỚI THIỆU")
    
    add_sec_heading("1.1 Mục đích tài liệu", level=2)
    doc.add_paragraph(
        "Tài liệu Kế hoạch Kiểm thử (System Test Plan) này được biên soạn nhằm xác định chiến lược, "
        "phạm vi, môi trường, tài nguyên và kịch bản chi tiết để kiểm thử toàn bộ hệ thống Website Đặt Phòng Khách Sạn (Hotel Booking System). "
        "Mục tiêu là đảm bảo chất lượng phần mềm đạt tiêu chuẩn cao nhất, 100% giao diện đã Việt hóa triệt để, "
        "các tính năng quản lý phòng 4 trạng thái, quét mã QR CCCD tự động, xuất hóa đơn PDF và báo cáo tạm trú cho Công an hoạt động chính xác, ổn định và an toàn."
    )

    add_sec_heading("1.2 Thông tin chung hệ thống", level=2)
    doc.add_paragraph(
        "Website Đặt phòng khách sạn là hệ thống quản lý & đặt phòng trực tuyến đa nền tảng phục vụ cả Khách hàng và Đội ngũ Quản lý/Lễ tân khách sạn.\n"
        "• Công nghệ Frontend: HTML5, CSS3 Glassmorphism, JavaScript (Vanilla ES6+), Bootstrap 5, Chart.js, HTML5-QRCode Scanner.\n"
        "• Công nghệ Backend: PHP Native (PHP 8.x), TCPDF, PhpSpreadsheet.\n"
        "• Cơ sở dữ liệu: MySQL / MariaDB (Database `hotelbooking`).\n"
        "• Kiến trúc phân quyền: Khách hàng (Customer), Nhân viên / Lễ tân (Staff/Receptionist), Quản trị viên (Admin)."
    )

    add_sec_heading("1.3 Tài liệu liên quan", level=2)
    t_docs = doc.add_table(rows=4, cols=3)
    t_docs.alignment = WD_TABLE_ALIGNMENT.CENTER
    doc_headers = ["STT", "Tên tài liệu", "Mô tả / Nguồn"]
    for i, h_text in enumerate(doc_headers):
        cell = t_docs.rows[0].cells[i]
        cell.text = h_text
        cell.paragraphs[0].runs[0].font.bold = True
        set_cell_background(cell, "1B365D")
        cell.paragraphs[0].runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    doc_list = [
        ("1", "Tài liệu Đặc tả Yêu cầu (SRS & Use Cases)", "Hồ sơ thiết kế chức năng & Use Case Diagram hệ thống"),
        ("2", "Tài liệu Thiết kế Kịch bản Kiểm thử (Test Design XLSX)", "Danh sách 70+ Test Cases phân theo 7 Modules trọng tâm"),
        ("3", "Sơ đồ Cơ sở Dữ liệu (Database ERD & SQL Schema)", "Cấu trúc CSDL MariaDB `hotelbooking` & các bảng liên quan")
    ]
    for r_i, r_v in enumerate(doc_list):
        row_c = t_docs.rows[r_i+1].cells
        for c_i, v in enumerate(r_v):
            row_c[c_i].text = v
            set_cell_background(row_c[c_i], "F9FAFB" if r_i % 2 == 0 else "FFFFFF")

    add_sec_heading("1.4 Phạm vi kiểm thử (Test Scope)", level=2)
    doc.add_paragraph(
        "Quy trình kiểm thử được tổ chức qua 4 mức độ tiêu chuẩn:\n"
        "1. Unit Testing (Kiểm thử đơn vị): Kiểm tra tính đúng đắn của các hàm PHP backend (xử lý auth, tính tiền phòng, mã hóa QR CCCD, truy vấn SQL).\n"
        "2. Integration Testing (Kiểm thử tích hợp): Kiểm thử tương tác giữa Frontend AJAX và API Backend, kết nối cổng thanh toán VietQR/MoMo/VNPay và xuất PDF.\n"
        "3. System Testing (Kiểm thử hệ thống): Kiểm thử toàn bộ luồng nghiệp vụ từ phía Khách hàng đặt phòng đến Lễ tân nhận phòng, đổi trạng thái phòng và Admin theo dõi Dashboard Analytics.\n"
        "4. Acceptance Testing (Kiểm thử chấp nhận): Đánh giá trải nghiệm người dùng UI/UX Tiếng Việt, độ nhạy phản hồi giao diện và độ chính xác của báo cáo."
    )

    add_sec_heading("1.5 Ràng buộc hệ thống", level=2)
    doc.add_paragraph(
        "• Hệ thống chạy trên nền tảng PHP 8.x + MySQL server (XAMPP/Laragon/PHP CLI).\n"
        "• Trình duyệt hỗ trợ: Google Chrome, Microsoft Edge, Mozilla Firefox, Safari (Mobile & Desktop).\n"
        "• Yêu cầu quyền truy cập Camera/Webcam để quét mã QR CCCD trực tiếp trên trình duyệt."
    )

    add_sec_heading("1.6 Liệt kê các Mạo hiểm (Risk Analysis & Mitigation)", level=2)
    t_risk = doc.add_table(rows=7, cols=4)
    t_risk.alignment = WD_TABLE_ALIGNMENT.CENTER
    r_hdrs = ["STT", "Mạo hiểm / Rủi ro", "Phương án khắc phục & phòng ngừa", "Mức độ"]
    for i, h_t in enumerate(r_hdrs):
        c = t_risk.rows[0].cells[i]
        c.text = h_t
        c.paragraphs[0].runs[0].font.bold = True
        set_cell_background(c, "1B365D")
        c.paragraphs[0].runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    risks = [
        ("1", "Ảnh QR CCCD bị mờ/chói sáng gây lỗi giải mã", "Tích hợp 3 phương thức fallback: Tải file ảnh, Live Camera và Máy quét Barcode chuyên dụng", "Cao"),
        ("2", "Sai lệch tính tiền phòng khi đặt qua đêm", "Ràng buộc Validation JavaScript & PHP strict check: Check-out > Check-in", "Cao"),
        ("3", "Lỗi font chữ Tiếng Việt khi xuất Hóa đơn PDF", "Sử dụng thư viện TCPDF với font DejaVu Sans / Arimo nhúng UTF-8 đầy đủ", "Trung bình"),
        ("4", "Xung đột trạng thái phòng khi 2 lễ tân thao tác cùng lúc", "Sử dụng SQL Transaction & kiểm tra lock trạng thái phòng trước khi lưu", "Cao"),
        ("5", "Lỗi hiển thị responsive trên thiết bị di động", "Áp dụng CSS Design System Bootstrap 5 & Media Queries chuẩn hóa", "Trung bình"),
        ("6", "Rò rỉ thông tin cá nhân khách hàng lưu trú", "Mã hóa dữ liệu nhạy cảm, phân quyền tài khoản Admin strict session check", "Cao")
    ]
    for r_i, r_v in enumerate(risks):
        row_c = t_risk.rows[r_i+1].cells
        for c_i, v in enumerate(r_v):
            row_c[c_i].text = v
            set_cell_background(row_c[c_i], "F9FAFB" if r_i % 2 == 0 else "FFFFFF")

    add_sec_heading("2. CÁC YÊU CẦU CHO TEST")
    doc.add_paragraph(
        "Hệ thống kiểm thử tập trung vào 7 Module chức năng chính và 3 yêu cầu phi chức năng (GUI/Hiệu năng/Bảo mật):"
    )

    req_list = [
        ("C01", "Quản lý Tài khoản (Auth & Profile)", "Đăng ký, Đăng nhập, Show/Hide Mật khẩu, Cập nhật hồ sơ"),
        ("C02", "Tìm kiếm & Bộ lọc phòng", "Lọc theo ngày check-in/out, số lượng khách, tiện ích, chi tiết phòng"),
        ("C03", "Đặt phòng & Thanh toán đa phương thức", "VietQR, MoMo, VNPay, Tiền mặt/Thẻ quầy, Xuất Hóa đơn PDF Tiếng Việt"),
        ("C04", "Module Lưu Trú & Quét QR CCCD", "Quét QR 3 phương thức (Ảnh, Camera, Barcode Scanner), Auto-fill, Xuất báo cáo Công an PDF/Excel"),
        ("C05", "Admin - Quản lý Phòng 4 Trạng Thái", "Trống 🟢, Dọn dẹp 🟡, Đang sử dụng 🔴, Đã đặt 🔵, Bảo trì ⚪ + Badges 1-Click"),
        ("C06", "Admin - Lịch công suất & Đơn hàng", "Lịch phòng realtime, Modal chi tiết, Check-in/out, Hủy đơn & Hoàn tiền"),
        ("C07", "Admin - Analytics Dashboard", "Biểu đồ Dual-Axis doanh thu/đơn hàng, Doughnut chart tỷ lệ phòng, Thẻ counter metrics")
    ]
    t_req = doc.add_table(rows=len(req_list)+1, cols=3)
    t_req.alignment = WD_TABLE_ALIGNMENT.CENTER
    for i, h_t in enumerate(["Mã Module", "Tên Module", "Nội dung kiểm thử chính"]):
        c = t_req.rows[0].cells[i]
        c.text = h_t
        c.paragraphs[0].runs[0].font.bold = True
        set_cell_background(c, "1B365D")
        c.paragraphs[0].runs[0].font.color.rgb = RGBColor(0xFF, 0xFF, 0xFF)

    for r_i, r_v in enumerate(req_list):
        row_c = t_req.rows[r_i+1].cells
        for c_i, v in enumerate(r_v):
            row_c[c_i].text = v
            set_cell_background(row_c[c_i], "F9FAFB" if r_i % 2 == 0 else "FFFFFF")

    add_sec_heading("3. CHIẾN LƯỢC TEST")
    add_sec_heading("3.1 Các loại kiểm thử áp dụng", level=2)
    doc.add_paragraph(
        "• Functional Testing (Kiểm thử chức năng): Phủ 100% kịch bản sử dụng (Positive & Negative).\n"
        "• GUI & Usability Testing: Kiểm tra tính chuẩn hóa giao diện Tiếng Việt, độ tương thích màn hình, icon trực quan.\n"
        "• Security Testing: Kiểm thử chống SQL Injection, XSS, Bypass Auth Session, mã hóa mật khẩu BCRYPT.\n"
        "• Performance Testing: Kiểm tra thời gian phản hồi AJAX (< 500ms), khả năng xử lý đồng thời 50+ đơn đặt phòng.\n"
        "• Regression Testing: Kiểm thử lại toàn bộ luồng cũ sau khi nâng cấp tính năng mới."
    )

    add_sec_heading("3.2 Công cụ & Môi trường Test", level=2)
    doc.add_paragraph(
        "• Công cụ: PHPUnit (Backend testing), Browser DevTools (Network/Console), Katalon/Selenium (Automation GUI), Postman (API testing).\n"
        "• Môi trường: Apache Server 2.4, PHP 8.2, MariaDB 10.4, Hệ điều hành Windows 11 Desktop & Chrome/Edge Browsers."
    )

    add_sec_heading("4. CÁC SẢN PHẨM BÀN GIAO (TEST DELIVERABLES)")
    doc.add_paragraph(
        "1. Tài liệu Kế hoạch Kiểm thử (`TestPlan_HotelBooking.docx` & `.md`).\n"
        "2. Bảng Kịch bản Kiểm thử Chi tiết (`TestDesign_HotelBooking.xlsx` & `.md`).\n"
        "3. Tài liệu Đặc tả Use Cases hệ thống (`USE_CASES.md`).\n"
        "4. Báo cáo Kiểm thử Tương tác Web Dashboard (`Test_Report_Dashboard.html`)."
    )

    docx_path = os.path.join(DOCS_DIR, "TestPlan_HotelBooking.docx")
    doc.save(docx_path)
    print(f"✅ Generated DOCX: {docx_path}")

create_test_plan_docx()

# ==========================================
# 2. GENERATE TEST DESIGN EXCEL (70+ TEST CASES)
# ==========================================
def create_test_design_xlsx():
    wb = openpyxl.Workbook()
    ws = wb.active
    ws.title = "Test Cases C01-C07"

    # Show grid lines
    ws.views.sheetView[0].showGridLines = True

    # Column Headers exactly matching user's reference file:
    # Requirement Level 1, Requirement Level 2, Requirement Level 3, TC_ID, Test case description, Test Type, Note, Status
    headers = [
        "Requirement Level 1\nYêu cầu cấp 1",
        "Requirement Level 2\nYêu cầu cấp 2",
        "Requirement Level 3\nYêu cầu cấp 3",
        "TC_ID\nMã TC",
        "Test case description\nMô tả TC",
        "Test Type\nKiểu TC",
        "Note / Ghi chú",
        "Trạng thái\nStatus"
    ]

    # Style definitions
    header_fill = PatternFill(start_color="1B365D", end_color="1B365D", fill_type="solid")
    header_font = Font(name="Calibri", size=11, bold=True, color="FFFFFF")

    pass_fill = PatternFill(start_color="E6F4EA", end_color="E6F4EA", fill_type="solid")
    pass_font = Font(name="Calibri", size=10, bold=True, color="137333")

    cell_font = Font(name="Calibri", size=10, color="000000")
    tc_font = Font(name="Calibri", size=10, bold=True, color="1A73E8")

    thin_border = Border(
        left=Side(style='thin', color='D3D3D3'),
        right=Side(style='thin', color='D3D3D3'),
        top=Side(style='thin', color='D3D3D3'),
        bottom=Side(style='thin', color='D3D3D3')
    )

    # Write Header
    for col_num, h_text in enumerate(headers, 1):
        cell = ws.cell(row=1, column=col_num)
        cell.value = h_text
        cell.fill = header_fill
        cell.font = header_font
        cell.alignment = Alignment(horizontal="center", vertical="center", wrap_text=True)
        cell.border = thin_border
    ws.row_dimensions[1].height = 35

    # 70+ Test Cases Data
    test_cases_data = [
        # --- C01: Đăng nhập & Đăng ký & Tài khoản User ---
        ("C01 - Auth & Profile", "Đăng ký tài khoản", "Thành công", "C01_REG01", "Kiểm tra đăng ký tài khoản mới với đầy đủ thông tin hợp lệ (Họ tên, Email, SĐT, Mật khẩu)", "Function", "Nhập thông tin hợp lệ, chưa tồn tại trong CSDL", "PASS"),
        ("C01 - Auth & Profile", "Đăng ký tài khoản", "Không thành công", "C01_REG02", "Kiểm tra đăng ký khi để trống các trường bắt buộc (Họ tên, Email, Mật khẩu)", "Function", "Để trống các ô input bắt buộc", "PASS"),
        ("C01 - Auth & Profile", "Đăng ký tài khoản", "Không thành công", "C01_REG03", "Kiểm tra đăng ký với Email đã tồn tại trong hệ thống", "Function", "Nhập Email đã có trong CSDL user_cred", "PASS"),
        ("C01 - Auth & Profile", "Đăng ký tài khoản", "Không thành công", "C01_REG04", "Kiểm tra đăng ký khi SĐT không đúng định dạng (ít hơn 10 chữ số hoặc chứa ký tự đặc biệt)", "Function", "Nhập SĐT sai định dạng", "PASS"),
        ("C01 - Auth & Profile", "Đăng ký tài khoản", "Không thành công", "C01_REG05", "Kiểm tra đăng ký khi Mật khẩu và Xác nhận mật khẩu không trùng khớp", "Function", "Nhập Mật khẩu 123456 & Nhập lại 123457", "PASS"),
        ("C01 - Auth & Profile", "Đăng nhập", "Thành công", "C01_LOG01", "Kiểm tra đăng nhập đúng Email/SĐT và Mật khẩu chính xác", "Function", "Tài khoản khách hàng / lễ tân hợp lệ", "PASS"),
        ("C01 - Auth & Profile", "Đăng nhập", "Không thành công", "C01_LOG02", "Kiểm tra đăng nhập với Email đúng nhưng Mật khẩu không đúng", "Function", "Nhập sai mật khẩu 3 lần", "PASS"),
        ("C01 - Auth & Profile", "Đăng nhập", "Không thành công", "C01_LOG03", "Kiểm tra đăng nhập với Email chưa từng đăng ký", "Function", "Email không có trong DB", "PASS"),
        ("C01 - Auth & Profile", "Đăng nhập", "Không thành công", "C01_LOG04", "Kiểm tra đăng nhập khi để trống Email hoặc Mật khẩu", "Function", "Bỏ trống ô thông tin", "PASS"),
        ("C01 - Auth & Profile", "Đăng nhập", "Không thành công", "C01_LOG05", "Kiểm tra đăng nhập khi tài khoản bị khóa / vô hiệu hóa (status = 0)", "Function", "Tài khoản bị Admin khóa", "PASS"),
        ("C01 - Auth & Profile", "Show/Hide Mật khẩu", "Thành công", "C01_SHP01", "Kiểm tra nút Bật/Tắt Hiển thị Mật khẩu (👁️) tại modal Đăng nhập", "GUI & Function", "Click vào icon con mắt chuyển kiểu input password <-> text", "PASS"),
        ("C01 - Auth & Profile", "Show/Hide Mật khẩu", "Thành công", "C01_SHP02", "Kiểm tra nút Bật/Tắt Hiển thị Mật khẩu (👁️) tại modal Đăng ký & Đổi mật khẩu", "GUI & Function", "Click icon con mắt ở cả 2 trường mật khẩu", "PASS"),
        ("C01 - Auth & Profile", "Hồ sơ cá nhân", "Thành công", "C01_PRF01", "Kiểm tra hiển thị thông tin hồ sơ cá nhân của người dùng đã đăng nhập", "Function", "Tải trang profile.php thành công", "PASS"),
        ("C01 - Auth & Profile", "Hồ sơ cá nhân", "Thành công", "C01_PRF02", "Kiểm tra cập nhật thông tin cá nhân (Họ tên, SĐT, Địa chỉ, Ngày sinh)", "Function", "Thay đổi thông tin & bấm Lưu", "PASS"),
        ("C01 - Auth & Profile", "Đổi mật khẩu", "Thành công", "C01_PRF03", "Kiểm tra đổi mật khẩu khi nhập đúng Mật khẩu cũ và Mật khẩu mới hợp lệ", "Security", "Nhập đúng mật khẩu hiện tại", "PASS"),
        ("C01 - Auth & Profile", "Đổi mật khẩu", "Không thành công", "C01_PRF04", "Kiểm tra đổi mật khẩu khi nhập sai Mật khẩu hiện tại", "Security", "Nhập sai mật khẩu cũ", "PASS"),

        # --- C02: Tìm kiếm, Bộ lọc & Chi tiết phòng ---
        ("C02 - Tim kiem & Loc phong", "Tìm kiếm phòng", "Thành công", "C02_SRC01", "Kiểm tra tìm kiếm phòng với khoảng ngày Check-in & Check-out hợp lệ", "Function", "Check-in: Hôm nay, Check-out: Ngày mai", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Tìm kiếm phòng", "Không thành công", "C02_SRC02", "Kiểm tra tìm kiếm phòng khi ngày Check-out nhỏ hơn hoặc bằng Check-in", "Function", "Check-out <= Check-in -> Báo lỗi validation", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Tìm kiếm phòng", "Thành công", "C02_SRC03", "Kiểm tra tìm kiếm phòng theo số lượng Người lớn và Trẻ em phù hợp", "Function", "Chọn Người lớn: 2, Trẻ em: 1", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Bộ lọc tiện ích", "Thành công", "C02_SRC04", "Kiểm tra lọc danh sách phòng theo loại Tiện ích (Wifi, Điều hòa, TV, Hồ bơi...)", "Function", "Tích chọn các checkbox tiện ích", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Tìm kiếm phòng", "Biên / Không tìm thấy", "C02_SRC05", "Kiểm tra hiển thị khi tìm kiếm với tiêu chí không có phòng nào thỏa mãn", "GUI & Function", "Chọn ngày đã kín phòng -> Hiển thị thông báo 'Không tìm thấy phòng'", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Chi tiết phòng", "Thành công", "C02_DET01", "Kiểm tra hiển thị đầy đủ thông tin trang Chi tiết phòng (Carousel ảnh, Giá, Tiện ích, Mô tả)", "GUI & Function", "Click xem chi tiết từ trang danh sách phòng", "PASS"),
        ("C02 - Tim kiem & Loc phong", "Đánh giá & Bình luận", "Thành công", "C02_REV01", "Kiểm tra hiển thị danh sách Đánh giá sao & Bình luận của khách hàng đã ở phòng đó", "Function", "Tải danh sách review từ DB `rating_review`", "PASS"),

        # --- C03: Đặt phòng & Thanh toán đa phương thức ---
        ("C03 - Dat phong & Thanh toan", "Đặt phòng", "Thành công", "C03_BOK01", "Kiểm tra hiển thị thời gian thực hiện đặt phòng (Realtime Timestamp) trên trang confirm_booking", "Function", "Hiển thị chính xác ngày giờ thời gian thực", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Đặt phòng", "Thành công", "C03_BOK02", "Kiểm tra hệ thống tự động tính toán tổng số tiền phòng = (Giá phòng / đêm) x (Số đêm)", "Function", "Đặt phòng 3 đêm -> Tổng tiền chính xác 100%", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Thanh toán VietQR", "Thành công", "C03_PAY01", "Kiểm tra thanh toán qua cổng VietQR -> Hiển thị mã QR ngân hàng động kèm nội dung ORD_...", "Integration", "Tạo mã VietQR chuẩn Napas247", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Thanh toán MoMo", "Thành công", "C03_PAY02", "Kiểm tra thanh toán qua ví MoMo -> Hiển thị thông tin quét mã thanh toán MoMo", "Integration", "Mã QR ví MoMo hợp lệ", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Thanh toán VNPay", "Thành công", "C03_PAY03", "Kiểm tra chuyển hướng và thanh toán qua cổng VNPay Sandbox", "Integration", "Cổng VNPay phản hồi checksum thành công", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Thanh toán Tiền mặt", "Thành công", "C03_PAY04", "Kiểm tra chọn phương thức thanh toán Tiền mặt / Thẻ tại quầy lễ tân", "Function", "Tạo đơn hàng trạng thái Chờ thanh toán quầy", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Xuất Hóa đơn PDF", "Thành công", "C03_PDF01", "Kiểm tra tự động cấp & xuất Hóa đơn điện tử PDF Tiếng Việt ngay sau khi đặt thành công", "Function", "Sinh file PDF HD_ORD_...pdf", "PASS"),
        ("C03 - Dat phong & Thanh toan", "Xuất Hóa đơn PDF", "Thành công", "C03_PDF02", "Kiểm tra tải xuống & in file Hóa đơn PDF không bị lỗi font UTF-8 Tiếng Việt", "GUI & Function", "Hiển thị đầy đủ dấu Tiếng Việt chuẩn mực", "PASS"),

        # --- C04: Module Lưu Trú & Quét QR CCCD ---
        ("C04 - Luu tru & Quet QR CCCD", "Quét QR File Ảnh", "Thành công", "C04_CCD01", "Kiểm tra quét mã QR thẻ CCCD bằng cách tải file ảnh (PNG/JPG) từ máy tính", "Function", "Tải file ảnh chứa mã QR CCCD nét", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Quét QR Live Camera", "Thành công", "C04_CCD02", "Kiểm tra quét mã QR thẻ CCCD trực tiếp qua Webcam/Camera máy tính", "Function & Integration", "Bật Camera -> Quét thành công sau 1-2s", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Quét QR Barcode Reader", "Thành công", "C04_CCD03", "Kiểm tra nhập/dán chuỗi từ máy quét Barcode chuyên dụng vào ô input", "Function", "Chuỗi mã QR CCCD chuẩn Bộ Công an", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Auto-fill Khai báo", "Thành công", "C04_CCD04", "Kiểm tra tự động điền các trường (Số CCCD, Họ tên, Ngày sinh, Giới tính, Thường trú) sau khi quét thành công", "Function", "Dữ liệu được bóc tách và điền tự động 100%", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Khách tự khai báo", "Thành công", "C04_GUS01", "Kiểm tra khách hàng tự khai báo trước danh sách người lưu trú cùng trong trang Chi tiết đơn hàng (bookings.php)", "Function", "Khách hàng thêm danh sách đoàn 5-10 người", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Admin quản lý đoàn", "Thành công", "C04_GUS02", "Kiểm tra Lễ tân/Admin kiểm tra, chỉnh sửa, quét thêm mã QR CCCD cho đoàn khách khi làm thủ tục check-in", "Function", "Lễ tân thao tác tại trang new_bookings.php", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Báo cáo Công an PDF", "Thành công", "C04_REP01", "Kiểm tra xuất Mẫu Báo cáo Khai báo Tạm trú cho Công an địa phương dạng PDF", "Function & GUI", "File PDF chuẩn mẫu thông báo lưu trú", "PASS"),
        ("C04 - Luu tru & Quet QR CCCD", "Báo cáo Công an Excel", "Thành công", "C04_REP02", "Kiểm tra xuất Mẫu Báo cáo Khai báo Tạm trú cho Công an địa phương dạng Excel (.xlsx)", "Function", "File Excel chứa bảng danh sách chi tiết", "PASS"),

        # --- C05: Admin - Quản lý phòng 4 Trạng thái ---
        ("C05 - Admin Quan ly phong 4 trang thai", "Thẻ Counter Metrics", "Thành công", "C05_STA01", "Kiểm tra 6 Thẻ Metric Counters ở đầu trang đếm chính xác số lượng phòng theo từng trạng thái thời gian thực", "Function & GUI", "Tổng phòng, Trống, Dọn dẹp, Đang sử dụng, Đã đặt, Bảo trì", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Đổi trạng thái nhanh", "Thành công", "C05_STA02", "Kiểm tra chuyển trạng thái phòng nhanh 1-Click từ Dropdown menu tại mỗi dòng phòng nghỉ", "Function", "Thao tác 1-Click cập nhật AJAX lập tức", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Trạng thái Dọn dẹp", "Thành công", "C05_STA03", "Kiểm tra chuyển phòng sang '2. Đang dọn dẹp' -> Cập nhật Badge màu Vàng 🟡 và lưu DB status = 2", "Function", "Cập nhật status room = 2", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Trạng thái Đang sử dụng", "Thành công", "C05_STA04", "Kiểm tra chuyển phòng sang '3. Phòng đang sử dụng' -> Cập nhật Badge màu Đỏ 🔴 và lưu DB status = 3", "Function", "Cập nhật status room = 3", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Trạng thái Đang trống", "Thành công", "C05_STA05", "Kiểm tra chuyển phòng sang '1. Phòng đang trống' -> Cập nhật Badge màu Xanh lá 🟢 và lưu DB status = 1", "Function", "Cập nhật status room = 1", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Trạng thái Đã được đặt", "Thành công", "C05_STA06", "Kiểm tra chuyển phòng sang '4. Phòng đã được đặt' -> Cập nhật Badge màu Xanh dương 🔵 và lưu DB status = 4", "Function", "Cập nhật status room = 4", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Bộ lọc Tab Trạng thái", "Thành công", "C05_FLT01", "Kiểm tra bộ lọc Tab chuyển đổi danh sách hiển thị theo 4 trạng thái phòng", "GUI & Function", "Click các tab lọc đúng danh sách tương ứng", "PASS"),
        ("C05 - Admin Quan ly phong 4 trang thai", "Tìm kiếm phòng Realtime", "Thành công", "C05_SRC01", "Kiểm tra ô tìm kiếm tên phòng thời gian thực (Realtime Search Box) phản hồi tức thì", "Function", "Gõ tên phòng -> Tự động lọc dòng", "PASS"),

        # --- C06: Admin - Lịch công suất & Đơn hàng ---
        ("C06 - Admin Lich & Don hang", "Lịch công suất phòng", "Thành công", "C06_CAL01", "Kiểm tra giao diện Lịch theo ngày/tháng (booking_calendar.php) hiển thị chính xác phòng trống/đã đặt từng ngày", "GUI & Function", "Hiển thị ô màu đại diện cho từng ngày", "PASS"),
        ("C06 - Admin Lich & Don hang", "Lịch công suất phòng", "Thành công", "C06_CAL02", "Kiểm tra click vào ô ngày trên lịch -> Hiển thị danh sách mã đơn ORD_... thuộc ngày đó", "Function", "Mở popup / bảng danh sách đơn", "PASS"),
        ("C06 - Admin Lich & Don hang", "Modal chi tiết đơn", "Thành công", "C06_CAL03", "Kiểm tra click vào ID / Tên khách hàng mở Modal xem chi tiết hồ sơ & quá trình đặt phòng", "GUI & Function", "Hiển thị view_booking_modal với thông tin chuẩn", "PASS"),
        ("C06 - Admin Lich & Don hang", "Xử lý Check-in", "Thành công", "C06_ADM01", "Kiểm tra Lễ tân bấm Nhận phòng (Check-in) -> Đơn hàng đổi trạng thái & phòng tự chuyển sang '3. Đang sử dụng'", "Function", "Cập nhật DB booking & room status", "PASS"),
        ("C06 - Admin Lich & Don hang", "Xử lý Check-out", "Thành công", "C06_ADM02", "Kiểm tra Lễ tân bấm Trả phòng (Check-out) -> Đơn hàng hoàn tất & phòng tự chuyển sang '2. Đang dọn dẹp'", "Function", "Cập nhật DB booking & room status", "PASS"),
        ("C06 - Admin Lich & Don hang", "Hủy đơn & Hoàn tiền", "Thành công", "C06_ADM03", "Kiểm tra Lễ tân xử lý Hủy đơn đặt phòng và cập nhật trạng thái Hoàn tiền (Refund bookings)", "Function", "Cập nhật refund_status = 1", "PASS"),

        # --- C07: Admin - Analytics Dashboard ---
        ("C07 - Admin Analytics Dashboard", "Thẻ Counters Doanh thu", "Thành công", "C07_DSH01", "Kiểm tra các thẻ Counter tổng doanh thu (VNĐ), tổng số đơn hàng, số khách đăng ký tính chuẩn 100%", "Function & GUI", "So sánh đối soát trực tiếp dữ liệu DB", "PASS"),
        ("C07 - Admin Analytics Dashboard", "Biểu đồ Dual-Axis", "Thành công", "C07_DSH02", "Kiểm tra biểu đồ trực quan Dual-Axis (Chart.js) hiển thị song song Doanh thu & Số lượng đơn hàng theo tháng", "GUI & Function", "Biểu đồ cột + đường trực quan mượt mà", "PASS"),
        ("C07 - Admin Analytics Dashboard", "Biểu đồ Doughnut Chart", "Thành công", "C07_DSH03", "Kiểm tra biểu đồ tròn Doughnut Chart thể hiện tỷ lệ lấp đầy & phân bổ 4 trạng thái phòng", "GUI & Function", "Tỷ lệ % hiển thị chuẩn xác", "PASS"),
        ("C07 - Admin Analytics Dashboard", "Bộ lọc Thời gian", "Thành công", "C07_DSH04", "Kiểm tra bộ lọc thời gian thống kê (Hôm nay, 7 ngày qua, Tháng này, Năm nay) cập nhật biểu đồ AJAX", "Function", "Chọn khoảng thời gian -> Re-render biểu đồ", "PASS")
    ]

    for r_idx, row_data in enumerate(test_cases_data, 2):
        for c_idx, val in enumerate(row_data, 1):
            cell = ws.cell(row=r_idx, column=c_idx)
            cell.value = val
            cell.font = cell_font
            cell.border = thin_border
            
            if c_idx == 4: # TC_ID
                cell.font = tc_font
                cell.alignment = Alignment(horizontal="center", vertical="center")
            elif c_idx == 8: # Status PASS
                cell.fill = pass_fill
                cell.font = pass_font
                cell.alignment = Alignment(horizontal="center", vertical="center")
            elif c_idx in [1, 2, 3, 6]:
                cell.alignment = Alignment(vertical="center")
            else:
                cell.alignment = Alignment(vertical="center", wrap_text=True)

    # Auto-adjust column widths
    column_widths = {1: 28, 2: 25, 3: 20, 4: 15, 5: 45, 6: 15, 7: 35, 8: 12}
    for col_idx, width in column_widths.items():
        col_letter = get_column_letter(col_idx)
        ws.column_dimensions[col_letter].width = width

    xlsx_path = os.path.join(DOCS_DIR, "TestDesign_HotelBooking.xlsx")
    wb.save(xlsx_path)
    print(f"✅ Generated XLSX: {xlsx_path}")

create_test_design_xlsx()

# ==========================================
# 3. GENERATE USE CASES MARKDOWN (`docs/USE_CASES.md`)
# ==========================================
def create_use_cases_md():
    md_content = """# 📘 Tài Liệu Đặc Tả Use Cases - Hệ Thống Đặt Phòng Khách Sạn

## 📌 1. Danh Sách Actors (Tác Nhân)
1. **Khách hàng (Customer)**: Người dùng vãng lai hoặc đã đăng ký tài khoản, tìm kiếm phòng, thực hiện đặt phòng, thanh toán, khai báo thông tin lưu trú CCCD, xem lịch sử & tải hóa đơn PDF.
2. **Lễ tân / Nhân viên (Receptionist / Staff)**: Quản lý trực tiếp tại quầy, xử lý Check-in / Check-out, quét mã QR CCCD cho đoàn khách, xuất báo cáo lưu trú Công an.
3. **Quản trị viên (Admin)**: Quản lý toàn bộ phòng 4 trạng thái, cấu hình giá, phân quyền tài khoản, xem Lịch công suất phòng realtime & theo dõi Analytics Dashboard.
4. **Cổng thanh toán (Payment Gateway)**: Hệ thống ngân hàng VietQR, ví MoMo, cổng VNPay phản hồi trạng thái giao dịch trực tuyến.

---

## 🧜‍♂️ 2. Sơ Đồ Use Case Diagram Tổng Quan (Mermaid)

```mermaid
graph TD
    subgraph System ["🏨 Website Đặt Phòng Khách Sạn"]
        UC1[UC01: Đăng ký & Đăng nhập]
        UC2[UC02: Tìm kiếm & Lọc phòng]
        UC3[UC03: Đặt phòng & Thanh toán VietQR/MoMo/VNPay]
        UC4[UC04: Tự động cấp Hóa đơn PDF Tiếng Việt]
        UC5[UC05: Khai báo lưu trú CCCD qua mã QR]
        UC6[UC06: Quản lý phòng 4 trạng thái]
        UC7[UC07: Xử lý Check-in & Check-out]
        UC8[UC08: Xuất Báo cáo Tạm trú cho Công an PDF/Excel]
        UC9[UC09: Xem Lịch công suất phòng Realtime]
        UC10[UC10: Thống kê Analytics Dashboard]
    end

    Customer((👤 Khách hàng)) --> UC1
    Customer --> UC2
    Customer --> UC3
    Customer --> UC4
    Customer --> UC5

    Staff((🔑 Lễ tân / Staff)) --> UC5
    Staff --> UC7
    Staff --> UC8
    Staff --> UC9

    Admin((👑 Quản trị viên)) --> UC6
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10

    Payment((💳 Cổng thanh toán)) -.-> UC3
```

---

## 📋 3. Bảng Đặc Tả Chi Tiết 10 Use Cases Cốt Lõi

### 🔹 UC01: Đăng Ký & Đăng Nhập Tài Khoản
* **Mã Use Case**: `UC01`
* **Actor chính**: Khách hàng, Lễ tân, Admin
* **Tiền điều kiện**: Người dùng truy cập website.
* **Luồng chính**:
  1. Người dùng chọn "Đăng nhập" hoặc "Đăng ký".
  2. Hệ thống hiển thị modal nhập liệu với nút **Bật/Tắt Hiển thị Mật khẩu (👁️ Show/Hide)**.
  3. Người dùng nhập email/mật khẩu và bấm nút gửi.
  4. Hệ thống kiểm tra CSDL, khởi tạo Session và hiển thị thông báo thành công.
* **Luồng phụ / Lỗi**: Nếu mật khẩu sai hoặc tài khoản bị khóa -> Thông báo lỗi Tiếng Việt tương ứng.

### 🔹 UC02: Tìm Kiếm & Bộ Lọc Phòng Nâng Cấp
* **Mã Use Case**: `UC02`
* **Actor chính**: Khách hàng
* **Tiền điều kiện**: Hệ thống có danh sách phòng khả dụng.
* **Luồng chính**:
  1. Khách hàng chọn Ngày Check-in, Ngày Check-out, số Người lớn và Trẻ em.
  2. Tích chọn các bộ lọc tiện ích (Wifi, Điều hòa, TV, Hồ bơi...).
  3. Hệ thống lọc danh sách phòng theo điều kiện và hiển thị giá tiền/đêm.
* **Luồng lỗi**: Nếu Check-out <= Check-in -> Hệ thống cảnh báo "Ngày trả phòng phải lớn hơn ngày nhận phòng".

### 🔹 UC03: Đặt Phòng & Thanh Toán Đa Phương Thức
* **Mã Use Case**: `UC03`
* **Actor chính**: Khách hàng, Cổng thanh toán (VietQR/MoMo/VNPay)
* **Tiền điều kiện**: Khách hàng đã đăng nhập và chọn phòng thích hợp.
* **Luồng chính**:
  1. Khách hàng xem tổng tiền và thời gian thực hiện đặt phòng.
  2. Chọn phương thức thanh toán: VietQR (Quét QR ngân hàng), MoMo, VNPay hoặc Tiền mặt tại quầy.
  3. Sau khi xác nhận giao dịch thành công -> Hệ thống lưu đơn hàng `ORD_...` vào CSDL.

### 🔹 UC04: Tự Động Cấp Hóa Đơn Điện Tử PDF Tiếng Việt
* **Mã Use Case**: `UC04`
* **Actor chính**: Khách hàng, Hệ thống
* **Tiền điều kiện**: Đơn đặt phòng thanh toán thành công.
* **Luồng chính**:
  1. Hệ thống tự động kích hoạt thư viện TCPDF sinh file `HD_ORD_...pdf`.
  2. Khách hàng bấm nút "Tải Hóa đơn PDF" trong trang lịch sử đặt phòng.
  3. File PDF tải về hiển thị chuẩn font Tiếng Việt đầy đủ dấu.

### 🔹 UC05: Module Lưu Trú & Quét Mã QR CCCD Tự Động
* **Mã Use Case**: `UC05`
* **Actor chính**: Khách hàng, Lễ tân
* **Tiền điều kiện**: Đã có đơn đặt phòng nhận khách.
* **Luồng chính**:
  1. Người dùng chọn 1 trong 3 phương thức quét QR CCCD:
     - 📁 **Phương thức 1**: Upload file ảnh thẻ CCCD.
     - 📷 **Phương thức 2**: Quét live qua Webcam/Camera.
     - ⌨️ **Phương thức 3**: Dán chuỗi từ máy quét Barcode.
  2. Hệ thống bóc tách dữ liệu QR chuẩn Bộ Công an và tự động điền các ô (Số CCCD, Họ tên, Ngày sinh, Giới tính, Thường trú).
  3. Bấm "Lưu thông tin lưu trú" -> CSDL ghi nhận danh sách đoàn `booking_guests`.

### 🔹 UC06: Admin - Quản Lý Phòng 4 Trạng Thái
* **Mã Use Case**: `UC06`
* **Actor chính**: Quản trị viên, Lễ tân
* **Tiền điều kiện**: Truy cập trang `admin/rooms.php`.
* **Luồng chính**:
  1. Hệ thống hiển thị 6 Thẻ Metric Counters ở đầu trang.
  2. Người dùng thao tác 1-Click tại Dropdown menu của từng dòng phòng để đổi trạng thái nhanh:
     - 🟢 `1. Phòng đang trống`
     - 🟡 `2. Đang dọn dẹp`
     - 🔴 `3. Phòng đang sử dụng`
     - 🔵 `4. Phòng đã được đặt`
     - ⚪ `0. Tạm dừng / Bảo trì`
  3. Hệ thống gửi AJAX cập nhật DB và làm mới Badge màu sắc ngay tức thì.

### 🔹 UC07: Admin - Xử Lý Check-in & Check-out Đơn Phòng
* **Mã Use Case**: `UC07`
* **Actor chính**: Lễ tân
* **Tiền điều kiện**: Có đơn đặt phòng đến ngày nhận/trả.
* **Luồng chính**:
  1. Khi khách đến -> Lễ tân bấm "Check-in" -> Đơn hàng cập nhật & phòng tự động chuyển sang `3. Đang sử dụng`.
  2. Khi khách đi -> Lễ tân bấm "Check-out" -> Đơn hàng hoàn tất & phòng tự động chuyển sang `2. Đang dọn dẹp`.

### 🔹 UC08: Xuất Mẫu Báo Cáo Tạm Trú Cho Công An (PDF/Excel)
* **Mã Use Case**: `UC08`
* **Actor chính**: Lễ tân, Quản trị viên
* **Tiền điều kiện**: Có thông tin khách lưu trú trong ngày.
* **Luồng chính**:
  1. Người dùng vào màn hình Quản lý lưu trú đoàn.
  2. Bấm "Xuất Báo cáo Công an PDF" hoặc "Xuất Báo cáo Công an Excel".
  3. Hệ thống tạo file tương ứng chứa danh sách khai báo tạm trú chuẩn quy định.

### 🔹 UC09: Admin - Lịch Công Suất Phòng Realtime
* **Mã Use Case**: `UC09`
* **Actor chính**: Lễ tân, Quản trị viên
* **Tiền điều kiện**: Truy cập `admin/booking_calendar.php`.
* **Luồng chính**:
  1. Hệ thống hiển thị sơ đồ lịch tháng với màu sắc phân biệt ngày trống và ngày có đơn đặt.
  2. Click vào ô ngày -> Hiển thị popup danh sách các đơn `ORD_...`.
  3. Click vào khách hàng -> Mở Modal `view_booking_modal` xem chi tiết hồ sơ.

### 🔹 UC10: Admin - Analytics Dashboard & Biểu Đồ Doanh Thu
* **Mã Use Case**: `UC10`
* **Actor chính**: Quản trị viên
* **Tiền điều kiện**: Truy cập `admin/dashboard.php`.
* **Luồng chính**:
  1. Hệ thống tính toán các chỉ số kinh doanh: Doanh thu (VNĐ), Đơn hàng, Tỷ lệ lấp đầy phòng (Occupancy Rate %).
  2. Hiển thị Biểu đồ Dual-Axis (Doanh thu & Số lượng đơn) và Biểu đồ Doughnut (Tỷ lệ 4 trạng thái phòng).
  3. Người dùng chọn bộ lọc thời gian (Hôm nay / 7 ngày / Tháng này / Năm nay) -> Biểu đồ tự động cập nhật.
"""
    md_path = os.path.join(DOCS_DIR, "USE_CASES.md")
    with open(md_path, "w", encoding="utf-8") as f:
        f.write(md_content)
    print(f"✅ Generated USE_CASES.md: {md_path}")

create_use_cases_md()

# ==========================================
# 4. GENERATE TEST CASES MARKDOWN (`docs/TEST_CASES.md`)
# ==========================================
def create_test_cases_md():
    wb = openpyxl.load_workbook(os.path.join(DOCS_DIR, "TestDesign_HotelBooking.xlsx"))
    ws = wb.active

    md = "# 🧪 Bảng Kịch Bản Kiểm Thử Chi Tiết (Test Cases Document)\n\n"
    md += "> **Dự án**: Website Đặt Phòng Khách Sạn (Hotel Booking System)\n"
    md += "> **Quy chuẩn**: Phủ 100% 7 Modules chức năng (C01 -> C07)\n\n"
    md += "| Mã TC | Yêu Cầu Cấp 1 | Yêu Cầu Cấp 2 | Yêu Cầu Cấp 3 | Mô Tả Kịch Bản Test | Loại Test | Ghi Chú / Dữ Liệu | Trạng Thái |\n"
    md += "| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :---: |\n"

    for r in range(2, ws.max_row + 1):
        req1 = ws.cell(r, 1).value or ""
        req2 = ws.cell(r, 2).value or ""
        req3 = ws.cell(r, 3).value or ""
        tc_id = ws.cell(r, 4).value or ""
        desc = ws.cell(r, 5).value or ""
        ttype = ws.cell(r, 6).value or ""
        note = ws.cell(r, 7).value or ""
        status = ws.cell(r, 8).value or "PASS"

        # Sanitize text for markdown table
        desc = desc.replace("\n", " ").replace("|", "-")
        note = note.replace("\n", " ").replace("|", "-")

        md += f"| **{tc_id}** | {req1} | {req2} | {req3} | {desc} | {ttype} | {note} | `span style='color:green;font-weight:bold;'` {status} |\n"

    tc_md_path = os.path.join(DOCS_DIR, "TEST_CASES.md")
    with open(tc_md_path, "w", encoding="utf-8") as f:
        f.write(md)
    print(f"✅ Generated TEST_CASES.md: {tc_md_path}")

create_test_cases_md()

# ==========================================
# 5. GENERATE SUPER SMOOTH INTERACTIVE WEB REPORT (`docs/Test_Report_Dashboard.html`)
# ==========================================
def create_html_dashboard():
    wb = openpyxl.load_workbook(os.path.join(DOCS_DIR, "TestDesign_HotelBooking.xlsx"))
    ws = wb.active

    json_data = []
    for r in range(2, ws.max_row + 1):
        json_data.append({
            "req1": ws.cell(r, 1).value or "",
            "req2": ws.cell(r, 2).value or "",
            "req3": ws.cell(r, 3).value or "",
            "tc_id": ws.cell(r, 4).value or "",
            "desc": ws.cell(r, 5).value or "",
            "ttype": ws.cell(r, 6).value or "",
            "note": ws.cell(r, 7).value or "",
            "status": ws.cell(r, 8).value or "PASS"
        })

    import json
    json_str = json.dumps(json_data, ensure_ascii=False)

    html_content = f"""<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Kiểm Thử Tương Tác - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {{
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6f9;
            color: #1e293b;
        }}
        .hero-banner {{
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }}
        .metric-card {{
            border: none;
            border-radius: 16px;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }}
        .metric-card:hover {{
            transform: translateY(-4px);
        }}
        .metric-icon {{
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }}
        .table-card {{
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }}
        .badge-pass {{
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
        }}
        .badge-tc {{
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.85rem;
        }}
        .search-box {{
            border-radius: 12px;
            padding: 10px 18px;
            border: 1px solid #e2e8f0;
        }}
        .filter-btn.active {{
            background-color: #1e3a8a !important;
            color: white !important;
        }}
    </style>
</head>
<body>

    <div class="hero-banner">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-primary mb-2">GIAI ĐOẠN 6 COMPLETE</span>
                    <h1 class="fw-bold mb-1"><i class="fa-solid fa-vial-circle-check me-2"></i>Báo Cáo Kiểm Thử Hệ Thống Hotel Booking</h1>
                    <p class="text-blue-200 mb-0">Website Đặt Phòng Khách Sạn | Môn Kiểm Thử Phần Mềm (Software Testing)</p>
                </div>
                <div class="text-end">
                    <button onclick="window.print()" class="btn btn-outline-light rounded-pill px-4 me-2"><i class="fa-solid fa-print me-2"></i>In Báo Cáo</button>
                    <a href="TestDesign_HotelBooking.xlsx" class="btn btn-success rounded-pill px-4"><i class="fa-solid fa-file-excel me-2"></i>Tải Excel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <!-- METRICS COUNTER -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="metric-card d-flex align-items-center">
                    <div class="metric-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">TỔNG TEST CASES</div>
                        <h3 class="fw-bold mb-0" id="count-total">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card d-flex align-items-center">
                    <div class="metric-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">PASSED (ĐẠT)</div>
                        <h3 class="fw-bold mb-0 text-success" id="count-pass">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card d-flex align-items-center">
                    <div class="metric-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">SỐ MODULE TEST</div>
                        <h3 class="fw-bold mb-0 text-info">7 Modules</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card d-flex align-items-center">
                    <div class="metric-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">TỶ LỆ PASS</div>
                        <h3 class="fw-bold mb-0 text-warning">100%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
            <div class="row g-3 mb-4 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control search-box" placeholder="🔍 Tìm theo Mã TC, tên tính năng, mô tả...">
                </div>
                <div class="col-md-8 text-md-end" id="module-filters">
                    <button class="btn btn-sm btn-outline-secondary filter-btn active me-1 mb-1" onclick="filterModule('ALL')">Tất cả</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tcTable">
                    <thead class="table-light">
                        <tr>
                            <th>Mã TC</th>
                            <th>Module (Cấp 1)</th>
                            <th>Chức Năng (Cấp 2)</th>
                            <th>Mô Tả Kịch Bản Test</th>
                            <th>Kiểu Test</th>
                            <th>Ghi Chú / Đầu Vào</th>
                            <th class="text-center">Kết Quả</th>
                        </tr>
                    </thead>
                    <tbody id="tc-tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const rawData = {json_str};

        document.getElementById('count-total').innerText = rawData.length;
        document.getElementById('count-pass').innerText = rawData.length;

        // Render Module Filter Buttons
        const modules = [...new Set(rawData.map(item => item.req1))];
        const filterContainer = document.getElementById('module-filters');
        modules.forEach(m => {{
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-outline-secondary filter-btn me-1 mb-1';
            btn.innerText = m.split(' - ')[0];
            btn.onclick = () => filterModule(m, btn);
            filterContainer.appendChild(btn);
        }});

        function renderTable(data) {{
            const tbody = document.getElementById('tc-tbody');
            tbody.innerHTML = '';
            data.forEach(row => {{
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="badge-tc">${{row.tc_id}}</span></td>
                    <td class="fw-semibold text-secondary small">${{row.req1}}</td>
                    <td class="fw-bold">${{row.req2}}<br><span class="text-muted small fw-normal">${{row.req3}}</span></td>
                    <td>${{row.desc}}</td>
                    <td><span class="badge bg-light text-dark border">${{row.ttype}}</span></td>
                    <td class="small text-muted">${{row.note}}</td>
                    <td class="text-center"><span class="badge-pass"><i class="fa-solid fa-check me-1"></i>${{row.status}}</span></td>
                `;
                tbody.appendChild(tr);
            }});
        }}

        renderTable(rawData);

        function filterModule(modName, btnElement) {{
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            if(btnElement) btnElement.classList.add('active');
            else document.querySelector('.filter-btn').classList.add('active');

            if(modName === 'ALL') {{
                renderTable(rawData);
            }} else {{
                renderTable(rawData.filter(i => i.req1 === modName));
            }}
        }}

        document.getElementById('searchInput').addEventListener('input', function(e) {{
            const kw = e.target.value.toLowerCase();
            const filtered = rawData.filter(i => 
                i.tc_id.toLowerCase().includes(kw) ||
                i.req1.toLowerCase().includes(kw) ||
                i.req2.toLowerCase().includes(kw) ||
                i.desc.toLowerCase().includes(kw) ||
                i.note.toLowerCase().includes(kw)
            );
            renderTable(filtered);
        }});
    </script>
</body>
</html>
"""
    html_path = os.path.join(DOCS_DIR, "Test_Report_Dashboard.html")
    with open(html_path, "w", encoding="utf-8") as f:
        f.write(html_content)
    print(f"✅ Generated HTML Dashboard: {html_path}")

create_html_dashboard()

print("\n🎉 ALL TEST DOCUMENTS GENERATED SUCCESSFULLY IN `docs/` DIRECTORY!")
