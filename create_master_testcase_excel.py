# -*- coding: utf-8 -*-
"""
Script tao file Excel Test Case chuan muc, toi gian, dep mat cho do an Hotel Booking Website.
Bao gom 6 Sheets:
1. Tong quan & Dashboard
2. Module 1: Tai khoan (Hoang)
3. Module 2: Tim kiem & Dat phong (Truong)
4. Module 3: Quan ly Phong (Phat)
5. Module 4: Quan ly Don dat (Minh)
6. Module 5: Phan hoi & Thong ke (Hieu)
"""

import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils import get_column_letter

def build_testcase_excel():
    output_path = r'e:\School\KiemThu\Bang_Test_Case_Hoan_Chinh_Hotel_Booking.xlsx'
    wb = openpyxl.Workbook()
    wb.remove(wb.active) # Bo sheet mac dinh

    font_family = "Segoe UI"
    
    # Fonts
    title_font = Font(name=font_family, size=15, bold=True, color="FFFFFF")
    subtitle_font = Font(name=font_family, size=10, italic=True, color="E2E8F0")
    header_font = Font(name=font_family, size=10, bold=True, color="FFFFFF")
    section_font = Font(name=font_family, size=11, bold=True, color="1E3A8A")
    bold_cell_font = Font(name=font_family, size=9.5, bold=True, color="0F172A")
    regular_font = Font(name=font_family, size=9.5, color="1E293B")
    
    # Status fonts & fills
    pass_font = Font(name=font_family, size=9.5, bold=True, color="166534")
    pass_fill = PatternFill(start_color="DCFCE7", end_color="DCFCE7", fill_type="solid")
    
    fail_font = Font(name=font_family, size=9.5, bold=True, color="991B1B")
    fail_fill = PatternFill(start_color="FEE2E2", end_color="FEE2E2", fill_type="solid")

    pos_font = Font(name=font_family, size=9, bold=True, color="166534")
    pos_fill = PatternFill(start_color="E0F2FE", end_color="E0F2FE", fill_type="solid")

    neg_font = Font(name=font_family, size=9, bold=True, color="92400E")
    neg_fill = PatternFill(start_color="FEF3C7", end_color="FEF3C7", fill_type="solid")

    bnd_font = Font(name=font_family, size=9, bold=True, color="6B21A8")
    bnd_fill = PatternFill(start_color="F3E8FF", end_color="F3E8FF", fill_type="solid")

    # Header Fills
    title_fill = PatternFill(start_color="1E3A8A", end_color="1E3A8A", fill_type="solid") # Dark Navy
    header_fill = PatternFill(start_color="2563EB", end_color="2563EB", fill_type="solid") # Royal Blue
    kpi_hdr_fill = PatternFill(start_color="1E40AF", end_color="1E40AF", fill_type="solid")
    alt_row_fill = PatternFill(start_color="F8FAFC", end_color="F8FAFC", fill_type="solid")
    white_fill = PatternFill(start_color="FFFFFF", end_color="FFFFFF", fill_type="solid")
    section_fill = PatternFill(start_color="E2E8F0", end_color="E2E8F0", fill_type="solid")

    # Borders
    thin_border = Border(
        left=Side(style='thin', color='CBD5E1'),
        right=Side(style='thin', color='CBD5E1'),
        top=Side(style='thin', color='CBD5E1'),
        bottom=Side(style='thin', color='CBD5E1')
    )
    double_bottom_border = Border(
        left=Side(style='thin', color='CBD5E1'),
        right=Side(style='thin', color='CBD5E1'),
        top=Side(style='thin', color='CBD5E1'),
        bottom=Side(style='double', color='1E3A8A')
    )

    # Alignments
    align_center = Alignment(horizontal='center', vertical='center', wrap_text=True)
    align_left = Alignment(horizontal='left', vertical='center', wrap_text=True)
    align_right = Alignment(horizontal='right', vertical='center', wrap_text=True)

    headers = [
        "STT", 
        "Mã TC", 
        "Phân hệ / Chức năng", 
        "Kịch bản kiểm thử (Test Scenario)", 
        "Loại kiểm thử", 
        "Các bước thực hiện (Test Steps)", 
        "Dữ liệu đầu vào (Test Data)", 
        "Kết quả mong đợi (Expected Result)", 
        "Kết quả thực tế (Actual Result)", 
        "Trạng thái"
    ]

    col_widths = {
        'A': 6,   # STT
        'B': 14,  # Mã TC
        'C': 20,  # Chức năng
        'D': 34,  # Kịch bản
        'E': 14,  # Loại test
        'F': 36,  # Các bước thực hiện
        'G': 24,  # Dữ liệu
        'H': 34,  # Kết quả mong đợi
        'I': 34,  # Kết quả thực tế
        'J': 11   # Trạng thái
    }

    def format_tc_sheet(ws, title_text, subtitle_text, test_cases, transitions=None):
        ws.views.sheetView[0].showGridLines = True

        # Title row
        ws.merge_cells("A1:J1")
        ws["A1"] = title_text
        ws["A1"].font = title_font
        ws["A1"].fill = title_fill
        ws["A1"].alignment = align_center
        ws.row_dimensions[1].height = 36

        # Subtitle row
        ws.merge_cells("A2:J2")
        ws["A2"] = subtitle_text
        ws["A2"].font = subtitle_font
        ws["A2"].fill = title_fill
        ws["A2"].alignment = align_center
        ws.row_dimensions[2].height = 20

        # KPI row (Tổng ca, Pass, Fail, Tỷ lệ)
        ws.row_dimensions[3].height = 24
        total_tc = len(test_cases)
        pass_tc = sum(1 for tc in test_cases if tc[8] == "PASS")
        fail_tc = total_tc - pass_tc
        pass_rate = f"{(pass_tc / total_tc * 100):.1f}%" if total_tc > 0 else "0%"

        ws.cell(3, 1, "Thống kê:").font = bold_cell_font
        ws.cell(3, 2, f"Tổng: {total_tc} TCs").font = bold_cell_font
        ws.cell(3, 3, f"Đạt: {pass_tc} (Pass)").font = Font(name=font_family, size=9.5, bold=True, color="166534")
        ws.cell(3, 4, f"Lỗi: {fail_tc} (Fail)").font = Font(name=font_family, size=9.5, bold=True, color="B91C1C")
        ws.cell(3, 5, f"Tỷ lệ Pass: {pass_rate}").font = bold_cell_font
        for c in range(1, 11):
            ws.cell(3, c).fill = alt_row_fill
            ws.cell(3, c).border = thin_border

        # Header Table
        ws.row_dimensions[4].height = 28
        for col_idx, text in enumerate(headers, 1):
            cell = ws.cell(row=4, column=col_idx, value=text)
            cell.font = header_font
            cell.fill = header_fill
            cell.alignment = align_center
            cell.border = thin_border

        # Render rows
        current_row = 5
        for idx, tc in enumerate(test_cases, 1):
            # tc: (tc_id, feature, scenario, test_type, steps, data, expected, actual, status)
            ws.row_dimensions[current_row].height = 42
            row_fill = alt_row_fill if idx % 2 == 0 else white_fill

            # 1. STT
            c1 = ws.cell(current_row, 1, idx)
            c1.alignment = align_center
            c1.font = regular_font

            # 2. TC ID
            c2 = ws.cell(current_row, 2, tc[0])
            c2.alignment = align_center
            c2.font = bold_cell_font

            # 3. Phân hệ
            c3 = ws.cell(current_row, 3, tc[1])
            c3.alignment = align_left
            c3.font = regular_font

            # 4. Kịch bản
            c4 = ws.cell(current_row, 4, tc[2])
            c4.alignment = align_left
            c4.font = regular_font

            # 5. Loại kiểm thử
            c5 = ws.cell(current_row, 5, tc[3])
            c5.alignment = align_center
            if tc[3] == "Positive":
                c5.fill = pos_fill
                c5.font = pos_font
            elif tc[3] == "Negative":
                c5.fill = neg_fill
                c5.font = neg_font
            else:
                c5.fill = bnd_fill
                c5.font = bnd_font

            # 6. Các bước
            c6 = ws.cell(current_row, 6, tc[4])
            c6.alignment = align_left
            c6.font = regular_font

            # 7. Dữ liệu
            c7 = ws.cell(current_row, 7, tc[5])
            c7.alignment = align_left
            c7.font = regular_font

            # 8. Kết quả mong đợi
            c8 = ws.cell(current_row, 8, tc[6])
            c8.alignment = align_left
            c8.font = regular_font

            # 9. Kết quả thực tế
            c9 = ws.cell(current_row, 9, tc[7])
            c9.alignment = align_left
            c9.font = regular_font

            # 10. Trạng thái
            c10 = ws.cell(current_row, 10, tc[8])
            c10.alignment = align_center
            if tc[8] == "PASS":
                c10.fill = pass_fill
                c10.font = pass_font
            else:
                c10.fill = fail_fill
                c10.font = fail_font

            # Gán border và fill mặc định
            for col_i in range(1, 11):
                cell_i = ws.cell(current_row, col_i)
                cell_i.border = thin_border
                if col_i not in (5, 10):
                    cell_i.fill = row_fill

            current_row += 1

        # Nếu có transitions table
        if transitions:
            current_row += 1
            ws.merge_cells(f"A{current_row}:J{current_row}")
            ws[f"A{current_row}"] = "MA TRẬN CHUYỂN ĐỔI TRẠNG THÁI (STATE TRANSITION MATRIX)"
            ws[f"A{current_row}"].font = section_font
            ws[f"A{current_row}"].fill = section_fill
            ws[f"A{current_row}"].alignment = align_left
            ws.row_dimensions[current_row].height = 26
            current_row += 1

            trans_headers = ["STT", "Trạng thái bắt đầu", "Hành động / Thao tác kích hoạt", "Trạng thái tiếp theo", "Tính hợp lệ", "Cơ chế Backend & Kết quả kiểm thử", "", "", "", ""]
            ws.merge_cells(f"F{current_row}:J{current_row}")
            for ci, th in enumerate(trans_headers[:6], 1):
                tc_cell = ws.cell(current_row, ci, th)
                tc_cell.font = header_font
                tc_cell.fill = kpi_hdr_fill
                tc_cell.alignment = align_center
                tc_cell.border = thin_border
            for ci in range(7, 11):
                ws.cell(current_row, ci).border = thin_border
                ws.cell(current_row, ci).fill = kpi_hdr_fill
            ws.row_dimensions[current_row].height = 24
            current_row += 1

            for t_idx, tr in enumerate(transitions, 1):
                ws.row_dimensions[current_row].height = 28
                t_fill = alt_row_fill if t_idx % 2 == 0 else white_fill
                ws.merge_cells(f"F{current_row}:J{current_row}")

                # tr: (from_state, action, to_state, is_valid, behavior)
                c_stt = ws.cell(current_row, 1, t_idx)
                c_stt.alignment = align_center
                c_stt.font = regular_font

                c_from = ws.cell(current_row, 2, tr[0])
                c_from.alignment = align_center
                c_from.font = bold_cell_font

                c_act = ws.cell(current_row, 3, tr[1])
                c_act.alignment = align_left
                c_act.font = regular_font

                c_to = ws.cell(current_row, 4, tr[2])
                c_to.alignment = align_center
                c_to.font = bold_cell_font

                c_val = ws.cell(current_row, 5, tr[3])
                c_val.alignment = align_center
                if tr[3] == "HỢP LỆ":
                    c_val.font = pass_font
                    c_val.fill = pass_fill
                else:
                    c_val.font = fail_font
                    c_val.fill = fail_fill

                c_beh = ws.cell(current_row, 6, tr[4])
                c_beh.alignment = align_left
                c_beh.font = regular_font

                for ci in range(1, 11):
                    cell_ci = ws.cell(current_row, ci)
                    cell_ci.border = thin_border
                    if ci not in (5,):
                        cell_ci.fill = t_fill

                current_row += 1

        # Căn chỉnh width cột
        for col_letter, width in col_widths.items():
            ws.column_dimensions[col_letter].width = width

    # =========================================================================
    # 1. SHEET TỔNG QUAN & DASHBOARD
    # =========================================================================
    ws_dash = wb.create_sheet(title="Tổng quan & Kết quả")
    ws_dash.views.sheetView[0].showGridLines = True

    # Title
    ws_dash.merge_cells("A1:G1")
    ws_dash["A1"] = "BÁO CÁO TỔNG QUAN KIỂM THỬ - HỆ THỐNG HOTEL BOOKING WEBSITE"
    ws_dash["A1"].font = title_font
    ws_dash["A1"].fill = title_fill
    ws_dash["A1"].alignment = align_center
    ws_dash.row_dimensions[1].height = 40

    ws_dash.merge_cells("A2:G2")
    ws_dash["A2"] = "Dự án: Website Đặt Phòng Khách Sạn Trực Tuyến | Nhóm thực hiện: 5 Thành viên | Môn học: Kiểm thử phần mềm"
    ws_dash["A2"].font = subtitle_font
    ws_dash["A2"].fill = title_fill
    ws_dash["A2"].alignment = align_center
    ws_dash.row_dimensions[2].height = 22

    # Section 1: Thống kê số lượng
    ws_dash.merge_cells("A4:G4")
    ws_dash["A4"] = "I. TIẾN ĐỘ & KẾT QUẢ THỰC HIỆN KIỂM THỬ THEO PHÂN HỆ"
    ws_dash["A4"].font = section_font
    ws_dash["A4"].fill = section_fill
    ws_dash.row_dimensions[4].height = 26

    dash_headers = ["STT", "Phân hệ kiểm thử (Module)", "Thành viên phụ trách", "Tổng số Test Cases", "Số ca Đạt (Pass)", "Số ca Lỗi (Fail)", "Tỷ lệ Pass"]
    ws_dash.row_dimensions[5].height = 26
    for ci, th in enumerate(dash_headers, 1):
        cell = ws_dash.cell(5, ci, th)
        cell.font = header_font
        cell.fill = header_fill
        cell.alignment = align_center
        cell.border = thin_border

    modules_summary = [
        ("Module 1: Quản lý Tài khoản (Account Management)", "Hoàng", 18, 17, 1, "=E6/D6"),
        ("Module 2: Tìm kiếm & Đặt phòng (Frontend Booking)", "Trường", 20, 19, 1, "=E7/D7"),
        ("Module 3: Quản lý Phòng (Admin Room Management)", "Phát", 20, 19, 1, "=E8/D8"),
        ("Module 4: Quản lý Đơn đặt phòng (Booking Management)", "Minh", 20, 19, 1, "=E9/D9"),
        ("Module 5: Phản hồi & Thống kê (Feedback & Statistics)", "Hiếu", 20, 19, 1, "=E10/D10")
    ]

    for r_i, mod in enumerate(modules_summary, 6):
        ws_dash.row_dimensions[r_i].height = 24
        fill = alt_row_fill if r_i % 2 == 0 else white_fill
        
        c1 = ws_dash.cell(r_i, 1, r_i - 5)
        c1.alignment = align_center
        
        c2 = ws_dash.cell(r_i, 2, mod[0])
        c2.alignment = align_left
        c2.font = bold_cell_font
        
        c3 = ws_dash.cell(r_i, 3, mod[1])
        c3.alignment = align_center
        c3.font = bold_cell_font
        
        c4 = ws_dash.cell(r_i, 4, mod[2])
        c4.alignment = align_center
        
        c5 = ws_dash.cell(r_i, 5, mod[3])
        c5.alignment = align_center
        c5.font = pass_font
        c5.fill = pass_fill
        
        c6 = ws_dash.cell(r_i, 6, mod[4])
        c6.alignment = align_center
        c6.font = fail_font
        c6.fill = fail_fill
        
        c7 = ws_dash.cell(r_i, 7, mod[5])
        c7.alignment = align_center
        c7.number_format = '0.0%'
        c7.font = bold_cell_font

        for ci in range(1, 8):
            cell = ws_dash.cell(r_i, ci)
            cell.border = thin_border
            if ci not in (5, 6):
                cell.fill = fill

    # Tổng cộng
    total_row = 11
    ws_dash.row_dimensions[total_row].height = 26
    ws_dash.cell(total_row, 1, "").border = thin_border
    ws_dash.cell(total_row, 2, "TỔNG TOÀN HỆ THỐNG").font = Font(name=font_family, size=10, bold=True, color="1E3A8A")
    ws_dash.cell(total_row, 2).alignment = align_left
    ws_dash.cell(total_row, 3, "5 Modules").alignment = align_center
    ws_dash.cell(total_row, 4, "=SUM(D6:D10)").font = bold_cell_font
    ws_dash.cell(total_row, 4).alignment = align_center
    ws_dash.cell(total_row, 5, "=SUM(E6:E10)").font = pass_font
    ws_dash.cell(total_row, 5).alignment = align_center
    ws_dash.cell(total_row, 6, "=SUM(F6:F10)").font = fail_font
    ws_dash.cell(total_row, 6).alignment = align_center
    ws_dash.cell(total_row, 7, "=E11/D11").font = bold_cell_font
    ws_dash.cell(total_row, 7).alignment = align_center
    ws_dash.cell(total_row, 7).number_format = '0.0%'

    for ci in range(1, 8):
        cell = ws_dash.cell(total_row, ci)
        cell.border = double_bottom_border
        cell.fill = PatternFill(start_color="FEF08A", end_color="FEF08A", fill_type="solid")

    # Section 2: Danh sách 5 Bug phát hiện thực tế (Giải quyết dứt điểm nhận xét của Monica)
    ws_dash.merge_cells("A13:G13")
    ws_dash["A13"] = "II. DANH SÁCH DEFECT / BUG PHÁT HIỆN THỰC TẾ (GIẢI TRÌNH CÁC CA FAIL)"
    ws_dash["A13"].font = section_font
    ws_dash["A13"].fill = section_fill
    ws_dash.row_dimensions[13].height = 26

    bug_headers = ["Mã Bug", "Module", "Mã Test Case", "Mô tả lỗi phát hiện khi kiểm thử", "Mức độ nghiêm trọng", "Trạng thái Bug", "Hướng khắc phục đề xuất"]
    ws_dash.row_dimensions[14].height = 26
    for ci, bh in enumerate(bug_headers, 1):
        cell = ws_dash.cell(14, ci, bh)
        cell.font = header_font
        cell.fill = kpi_hdr_fill
        cell.alignment = align_center
        cell.border = thin_border

    bugs_data = [
        ("BUG_01", "Module Tài khoản", "TC_ACC_10", "Dịch vụ SendGrid email_confirmation trả về mail_failed làm sập luồng đăng ký, khách không nhận được mã", "High (Cao)", "Open (Chờ fix)", "Bổ sung cơ chế hàng đợi gửi mail hoặc cho phép khách yêu cầu gửi lại mã kích hoạt"),
        ("BUG_02", "Module Tìm kiếm & Đặt", "TC_RES_08", "Bộ lọc số lượng khách (Guests) cho phép gõ số âm (-2) mà không tự động reset về 1 hoặc chặn submit", "Medium (Vừa)", "Open (Chờ fix)", "Thêm thuộc tính min='1' trên thẻ HTML input và validate regex ở JS frontend"),
        ("BUG_03", "Module Quản lý Phòng", "TC_ADM_RM_20", "Hệ thống cho phép thêm 2 Tiện ích (Facility) có tên giống hệt nhau vào CSDL", "Medium (Vừa)", "Open (Chờ fix)", "Thêm ràng buộc UNIQUE key trên cột 'name' của bảng features/facilities trong MySQL"),
        ("BUG_04", "Module Đơn đặt phòng", "TC_ADM_BK_18", "File PDF hóa đơn bị vỡ khung / tràn chữ khi tên khách hoặc địa chỉ dài hơn 150 ký tự", "Low (Thấp)", "Open (Chờ fix)", "Cấu hình tự động co chữ (fit-to-page) hoặc ngắt dòng word-wrap trong thư viện mPDF"),
        ("BUG_05", "Module Thống kê", "TC_STAT_20", "Lỗi PHP Warning: Division by zero khi tính Giá trị đơn TB (avg_amt) trong chu kỳ có 0 đơn", "Low (Thấp)", "Open (Chờ fix)", "Thêm câu lệnh kiểm tra if ($total_bookings > 0) trước khi thực hiện phép chia tính trung bình")
    ]

    for r_i, bug in enumerate(bugs_data, 15):
        ws_dash.row_dimensions[r_i].height = 28
        fill = alt_row_fill if r_i % 2 == 0 else white_fill
        
        c1 = ws_dash.cell(r_i, 1, bug[0])
        c1.alignment = align_center
        c1.font = bold_cell_font
        
        c2 = ws_dash.cell(r_i, 2, bug[1])
        c2.alignment = align_left
        
        c3 = ws_dash.cell(r_i, 3, bug[2])
        c3.alignment = align_center
        c3.font = bold_cell_font
        
        c4 = ws_dash.cell(r_i, 4, bug[3])
        c4.alignment = align_left
        c4.font = regular_font
        
        c5 = ws_dash.cell(r_i, 5, bug[4])
        c5.alignment = align_center
        c5.font = bold_cell_font
        
        c6 = ws_dash.cell(r_i, 6, bug[5])
        c6.alignment = align_center
        c6.fill = fail_fill
        c6.font = fail_font
        
        c7 = ws_dash.cell(r_i, 7, bug[6])
        c7.alignment = align_left
        c7.font = regular_font

        for ci in range(1, 8):
            cell = ws_dash.cell(r_i, ci)
            cell.border = thin_border
            if ci != 6:
                cell.fill = fill

    ws_dash.column_dimensions['A'].width = 12
    ws_dash.column_dimensions['B'].width = 32
    ws_dash.column_dimensions['C'].width = 22
    ws_dash.column_dimensions['D'].width = 20
    ws_dash.column_dimensions['E'].width = 18
    ws_dash.column_dimensions['F'].width = 18
    ws_dash.column_dimensions['G'].width = 36


    # =========================================================================
    # 2. SHEET MODULE 1: TÀI KHOẢN (HOÀNG)
    # =========================================================================
    hoang_tcs = [
        ("TC_ACC_01", "Đăng ký tài khoản", "Đăng ký thành công với đầy đủ thông tin hợp lệ", "Positive", 
         "1. Bấm nút 'Đăng ký' trên header\n2. Nhập đầy đủ Họ tên, Email, SĐT, Địa chỉ, Ngày sinh, Mật khẩu khớp\n3. Chọn ảnh đại diện JPG < 2MB\n4. Tích 'Đồng ý điều khoản' và bấm 'Đăng ký'", 
         "Họ tên: Nguyễn Văn An, Email: an.nv@gmail.com, SĐT: 0912345678, MK: AnPass@123, Profile: avatar.jpg", 
         "Hệ thống trả mã 1, hiện thông báo 'Đăng ký thành công! Vui lòng kiểm tra email kích hoạt'", 
         "Hệ thống tạo bản ghi trong user_cred, gửi email xác thực thành công", "PASS"),

        ("TC_ACC_02", "Đăng ký tài khoản", "Chặn đăng ký khi để trống các trường bắt buộc", "Negative", 
         "1. Mở modal Đăng ký\n2. Để trống ô 'Họ và tên' và 'Email'\n3. Điền các ô còn lại -> Bấm 'Đăng ký'", 
         "Họ tên: '', Email: '', SĐT: 0912345678", 
         "HTML5 validation hiển thị nhắc nhở 'Please fill out this field', chặn gửi form lên server", 
         "Form không submit, hiển thị viền đỏ cảnh báo trường bắt buộc", "PASS"),

        ("TC_ACC_03", "Đăng ký tài khoản", "Chặn đăng ký với Email đã tồn tại trong hệ thống", "Negative", 
         "1. Mở modal Đăng ký\n2. Nhập Email đã có trong CSDL (user_cred)\n3. Điền đủ thông tin khác -> Bấm 'Đăng ký'", 
         "Email: an.nv@gmail.com (Đã tồn tại trong DB)", 
         "Hệ thống gọi ajax login_register.php, trả mã 'email_already', báo lỗi 'Email này đã được sử dụng!'", 
         "Hiển thị toast lỗi màu đỏ 'Email này đã được sử dụng!', không lưu thêm bản ghi", "PASS"),

        ("TC_ACC_04", "Đăng ký tài khoản", "Chặn đăng ký với Số điện thoại đã được đăng ký", "Negative", 
         "1. Mở modal Đăng ký\n2. Nhập Email mới nhưng SĐT đã có trong CSDL\n3. Bấm 'Đăng ký'", 
         "Email: khachmoi@gmail.com, SĐT: 0912345678 (trùng SĐT cũ)", 
         "Hệ thống trả mã 'phone_already', báo lỗi 'Số điện thoại này đã được sử dụng!'", 
         "Hiển thị cảnh báo số điện thoại đã tồn tại, chặn lưu DB", "PASS"),

        ("TC_ACC_05", "Đăng ký tài khoản", "Chặn đăng ký khi Mật khẩu và Xác nhận mật khẩu không khớp", "Negative", 
         "1. Nhập thông tin đăng ký\n2. Ô Mật khẩu nhập 'Pass123@'\n3. Ô Nhập lại mật khẩu nhập 'Pass456@'\n4. Bấm 'Đăng ký'", 
         "pass: 'Pass123@', cpass: 'Pass456@'", 
         "Hệ thống trả mã 'pass_mismatch', thông báo 'Mật khẩu xác nhận không khớp!'", 
         "Báo lỗi 'Mật khẩu xác nhận không khớp!', form giữ nguyên dữ liệu đã nhập", "PASS"),

        ("TC_ACC_06", "Đăng ký tài khoản", "Kiểm tra độ dài mật khẩu tối thiểu (ít hơn 6 ký tự)", "Boundary", 
         "1. Nhập thông tin hợp lệ\n2. Ô Mật khẩu chỉ nhập 4 ký tự\n3. Bấm 'Đăng ký'", 
         "pass: '1234', cpass: '1234'", 
         "Báo lỗi mật khẩu yếu hoặc yêu cầu độ dài tối thiểu từ 6 ký tự trở lên", 
         "Hiển thị cảnh báo mật khẩu không đủ độ dài quy định", "PASS"),

        ("TC_ACC_07", "Đăng ký tài khoản", "Chặn đăng ký khi nhập định dạng Email không hợp lệ", "Negative", 
         "1. Nhập thông tin đăng ký\n2. Ô Email nhập thiếu ký tự @ hoặc sai cấu trúc\n3. Bấm 'Đăng ký'", 
         "Email: 'nguyenvanan.gmail.com'", 
         "Trình duyệt kích hoạt email validation, yêu cầu bao gồm '@' trong địa chỉ email", 
         "Trình duyệt chặn submit và nhắc nhở định dạng email không hợp lệ", "PASS"),

        ("TC_ACC_08", "Đăng ký tài khoản", "Chặn upload ảnh đại diện sai định dạng (.exe, .php, .pdf)", "Negative", 
         "1. Điền thông tin đăng ký\n2. Chọn tệp profile là file thực thi mã độc hoặc file văn bản\n3. Bấm 'Đăng ký'", 
         "File: 'script.php' hoặc 'document.pdf'", 
         "Hàm uploadUserImage trả về 'inv_img', báo lỗi 'Chỉ chấp nhận ảnh JPG, WEBP hoặc PNG!'", 
         "Hệ thống từ chối tải tệp, báo lỗi 'inv_img', chặn lưu vào thư mục users/", "PASS"),

        ("TC_ACC_09", "Đăng ký tài khoản", "Chặn upload ảnh đại diện vượt quá 2MB", "Boundary", 
         "1. Chọn tệp ảnh định dạng JPG nhưng kích thước 5.2 MB\n2. Bấm 'Đăng ký'", 
         "File size: 5.2 MB (> 2MB)", 
         "Hàm uploadUserImage trả về 'inv_size', thông báo 'Kích thước ảnh phải nhỏ hơn 2MB!'", 
         "Báo lỗi ảnh vượt quá kích thước cho phép, form không lưu bản ghi", "PASS"),

        ("TC_ACC_10", "Đăng ký tài khoản", "Kiểm tra cơ chế xử lý khi dịch vụ gửi email SendGrid bị lỗi", "Negative", 
         "1. Điền thông tin hợp lệ\n2. Giả lập SendGrid API Key sai hoặc mạng mất kết nối\n3. Bấm 'Đăng ký'", 
         "SendGrid response: Exception / 0 (fail)", 
         "Hệ thống thông báo 'Gửi email xác thực thất bại, vui lòng thử lại sau', không làm sập ứng dụng", 
         "Hệ thống trả mã 'mail_failed', nhưng người dùng không rõ tài khoản đã lưu hay chưa (DEFECT)", "FAIL"),

        ("TC_ACC_11", "Đăng nhập", "Đăng nhập thành công với Email và Mật khẩu chính xác", "Positive", 
         "1. Bấm nút 'Đăng nhập' trên header\n2. Nhập Email và Mật khẩu đã kích hoạt\n3. Bấm 'Đăng nhập'", 
         "Email: an.nv@gmail.com, Password: AnPass@123", 
         "Hệ thống trả về '1', tạo $_SESSION['login']=true, đóng modal, header đổi thành menu hồ sơ khách", 
         "Đăng nhập thành công, xuất hiện lời chào và nút Quản lý đơn phòng trên header", "PASS"),

        ("TC_ACC_12", "Đăng nhập", "Chặn đăng nhập khi nhập sai Mật khẩu", "Negative", 
         "1. Mở modal Đăng nhập\n2. Nhập đúng Email nhưng gõ sai Mật khẩu\n3. Bấm 'Đăng nhập'", 
         "Email: an.nv@gmail.com, Password: SaiMatKhau999", 
         "Hàm password_verify trả false, trả mã 'invalid_pass', báo 'Mật khẩu không chính xác!'", 
         "Hiển thị thông báo 'Mật khẩu không chính xác!', không tạo session đăng nhập", "PASS"),

        ("TC_ACC_13", "Đăng nhập", "Chặn đăng nhập khi Email chưa từng đăng ký", "Negative", 
         "1. Mở modal Đăng nhập\n2. Nhập email chưa tồn tại trong CSDL\n3. Bấm 'Đăng nhập'", 
         "Email: khongtontai@gmail.com, Password: AnyPassword123", 
         "Hệ thống kiểm tra num_rows == 0, trả mã 'inv_email_mob', báo 'Tài khoản không tồn tại!'", 
         "Báo lỗi 'Tài khoản không tồn tại hoặc sai thông tin!', từ chối đăng nhập", "PASS"),

        ("TC_ACC_14", "Đăng nhập", "Chặn đăng nhập khi tài khoản chưa kích hoạt qua email", "Negative", 
         "1. Nhập tài khoản vừa đăng ký nhưng chưa click link xác nhận email\n2. Bấm 'Đăng nhập'", 
         "user_cred.is_verified = 0", 
         "Hệ thống trả mã 'not_verified', thông báo 'Email của bạn chưa được kích hoạt!'", 
         "Hiển thị thông báo yêu cầu người dùng kích hoạt tài khoản qua email", "PASS"),

        ("TC_ACC_15", "Đăng nhập", "Chặn đăng nhập khi tài khoản bị Admin khóa", "Negative", 
         "1. Nhập tài khoản đã bị Admin chuyển status = 0 trong trang quản trị\n2. Bấm 'Đăng nhập'", 
         "user_cred.status = 0 (Inactive)", 
         "Hệ thống trả mã 'inactive', thông báo 'Tài khoản của bạn đã bị khóa bởi quản trị viên!'", 
         "Hiển thị thông báo tài khoản bị khóa, chặn truy cập vào hệ thống", "PASS"),

        ("TC_ACC_16", "Đăng nhập", "Chặn đăng nhập khi để trống cả Email và Mật khẩu", "Negative", 
         "1. Mở modal Đăng nhập\n2. Để trống cả 2 ô -> Bấm 'Đăng nhập'", 
         "Email: '', Password: ''", 
         "Validation trình duyệt chặn submit, focus vào ô Email", 
         "Trình duyệt kích hoạt cảnh báo bắt buộc điền thông tin", "PASS"),

        ("TC_ACC_17", "Quên mật khẩu", "Gửi link đặt lại mật khẩu thành công tới email hợp lệ", "Positive", 
         "1. Bấm 'Quên mật khẩu?' tại modal đăng nhập\n2. Nhập email đã đăng ký\n3. Bấm 'Gửi link xác nhận'", 
         "Email: an.nv@gmail.com", 
         "Sinh token ngẫu nhiên, gửi email chứa link reset password, báo 'Link đặt lại mật khẩu đã gửi!'", 
         "Hệ thống báo gửi thành công, người dùng nhận được email chứa link reset", "PASS"),

        ("TC_ACC_18", "Quên mật khẩu", "Chặn yêu cầu đặt lại mật khẩu khi nhập email không tồn tại", "Negative", 
         "1. Mở modal Quên mật khẩu\n2. Nhập email ngẫu nhiên chưa đăng ký\n3. Bấm 'Gửi link'", 
         "Email: emailao12345@gmail.com", 
         "Hệ thống trả mã 'inv_email', báo 'Email không tồn tại trong hệ thống!'", 
         "Hiển thị thông báo email không tồn tại, bảo mật thông tin tài khoản", "PASS")
    ]

    ws_hoang = wb.create_sheet(title="1. Tài khoản (Hoàng)")
    format_tc_sheet(
        ws_hoang, 
        "BẢNG TEST CASE - MODULE 1: QUẢN LÝ TÀI KHOẢN (ACCOUNT MANAGEMENT)", 
        "Phụ trách: Hoàng | Phạm vi: Đăng ký, Đăng nhập, Quên mật khẩu, Xác thực Email & Kỹ thuật EP / BVA", 
        hoang_tcs
    )


    # =========================================================================
    # 3. SHEET MODULE 2: TÌM KIẾM & ĐẶT PHÒNG (TRƯỜNG)
    # =========================================================================
    truong_tcs = [
        ("TC_RES_01", "Tìm kiếm phòng", "Tìm phòng với ngày Check-in và Check-out hợp lệ (Luồng chuẩn)", "Positive", 
         "1. Truy cập trang rooms.php\n2. Chọn Check-in: Ngày mai, Check-out: 3 ngày sau\n3. Bấm 'Kiểm tra phòng'", 
         "Check-in: 05/10/2026, Check-out: 08/10/2026 (3 đêm)", 
         "Gọi ajax rooms.php, hiển thị danh sách tất cả các phòng còn trống trong khoảng ngày đã chọn", 
         "Danh sách phòng tải mượt mà qua AJAX, hiển thị đúng giá và ảnh phòng", "PASS"),

        ("TC_RES_02", "Tìm kiếm phòng", "Chặn tìm phòng khi chọn ngày Check-in trong quá khứ", "Negative", 
         "1. Tại bộ lọc ngày\n2. Chọn Check-in là ngày hôm qua\n3. Chọn Check-out là ngày mai\n4. Quan sát phản hồi", 
         "Check-in: Hôm qua, Check-out: Ngày mai", 
         "Hệ thống báo lỗi validation 'Ngày nhận phòng không thể ở trong quá khứ' hoặc set min attribute", 
         "DatePicker chặn chọn ngày quá khứ (thuộc tính min = hôm nay)", "PASS"),

        ("TC_RES_03", "Tìm kiếm phòng", "Chặn tìm phòng khi ngày Check-out nhỏ hơn Check-in", "Negative", 
         "1. Chọn Check-in: 15/10/2026\n2. Chọn Check-out: 12/10/2026\n3. Bấm 'Kiểm tra'", 
         "Check-in: 15/10, Check-out: 12/10", 
         "Báo lỗi validation 'Ngày trả phòng phải sau ngày nhận phòng!'", 
         "Hệ thống chặn tìm kiếm và hiển thị thông báo ngày trả phòng không hợp lệ", "PASS"),

        ("TC_RES_04", "Tìm kiếm phòng", "Chặn tìm phòng khi Check-out bằng Check-in (0 đêm lưu trú)", "Boundary", 
         "1. Chọn Check-in: 20/10/2026\n2. Chọn Check-out: 20/10/2026\n3. Bấm 'Kiểm tra'", 
         "Check-in = Check-out (0 đêm)", 
         "Báo lỗi 'Thời gian lưu trú tối thiểu là 1 đêm!'", 
         "Hệ thống hiển thị cảnh báo thời gian đặt tối thiểu là 1 đêm", "PASS"),

        ("TC_RES_05", "Tìm kiếm phòng", "Hiển thị trạng thái hết phòng (Empty State) khi không có phòng trống", "Positive", 
         "1. Chọn khoảng ngày cao điểm đã có khách đặt kín tất cả các phòng\n2. Bấm tìm kiếm", 
         "Dữ liệu DB: Tất cả các phòng đều booked trong khoảng ngày", 
         "Hiển thị card thông báo thân thiện 'Không tìm thấy phòng trống phù hợp trong khoảng thời gian này!'", 
         "Hiển thị thông báo hết phòng, không phát sinh lỗi vỡ layout", "PASS"),

        ("TC_RES_06", "Bộ lọc phòng", "Lọc phòng theo số lượng Khách hợp lệ (Người lớn & Trẻ em)", "Positive", 
         "1. Tại bộ lọc 'Khách (Guests)'\n2. Chọn Người lớn: 2, Trẻ em: 1\n3. Bấm lọc", 
         "Adults: 2, Children: 1 (Tổng 3 khách)", 
         "Hệ thống lọc và chỉ hiển thị các phòng có sức chứa adult >= 2 AND children >= 1", 
         "Danh sách phòng hiển thị chính xác các phòng đáp ứng đủ số lượng khách", "PASS"),

        ("TC_RES_07", "Bộ lọc phòng", "Lọc phòng với giá trị khách biên trên (10 khách)", "Boundary", 
         "1. Chọn số lượng Người lớn: 10\n2. Bấm lọc", 
         "Adults: 10", 
         "Hiển thị danh sách các phòng VIP/Family hoặc báo không có phòng nào đủ sức chứa 10 người", 
         "Hệ thống xử lý mượt mà, trả về kết quả rỗng không gây crash ứng dụng", "PASS"),

        ("TC_RES_08", "Bộ lọc phòng", "Chặn nhập số âm vào ô lọc số lượng khách", "Negative", 
         "1. Tại ô input Người lớn, nhập số âm -2\n2. Bấm lọc phòng", 
         "Adults: -2", 
         "Hệ thống tự động đưa giá trị về 1 hoặc thông báo số lượng khách phải lớn hơn 0", 
         "Hệ thống frontend cho phép gõ số âm, gửi request adults=-2 lên server (DEFECT)", "FAIL"),

        ("TC_RES_09", "Bộ lọc phòng", "Lọc phòng theo khoảng giá Min - Max hợp lệ", "Positive", 
         "1. Nhập khoảng giá từ 500.000 VNĐ đến 1.500.000 VNĐ\n2. Bấm áp dụng", 
         "Price Min: 500000, Price Max: 1500000", 
         "Danh sách cập nhật, toàn bộ phòng hiển thị đều có giá nằm trong đoạn [500k, 1500k]", 
         "Kết quả lọc đúng 100% theo khoảng giá yêu cầu", "PASS"),

        ("TC_RES_10", "Bộ lọc phòng", "Chặn lọc khi Giá Min lớn hơn Giá Max", "Negative", 
         "1. Nhập Giá Min: 2.000.000 VNĐ\n2. Nhập Giá Max: 500.000 VNĐ\n3. Bấm lọc", 
         "Min: 2000000, Max: 500000", 
         "Thông báo lỗi 'Giá tối thiểu không thể lớn hơn giá tối đa!'", 
         "Hệ thống hiển thị cảnh báo khoảng giá không hợp lệ", "PASS"),

        ("TC_RES_11", "Bộ lọc phòng", "Lọc kết hợp nhiều Tiện ích (Logic AND: Wifi + Hồ bơi + Bữa sáng)", "Positive", 
         "1. Tích chọn checkbox: Wifi, Bể bơi, Ăn sáng\n2. Quan sát kết quả hiển thị", 
         "Facilities: [Wifi, Pool, Breakfast]", 
         "Chỉ các phòng có ĐỒNG THỜI cả 3 tiện ích trên mới được hiển thị trong danh sách", 
         "Bộ lọc hoạt động chính xác theo điều kiện logic giao nhau", "PASS"),

        ("TC_RES_12", "Chi tiết phòng", "Xem chi tiết phòng hợp lệ, hiển thị carousel ảnh và tiện ích", "Positive", 
         "1. Bấm nút 'Xem chi tiết' tại phòng bất kỳ\n2. Quan sát trang room_details.php", 
         "Room ID: 1", 
         "Hiển thị đầy đủ carousel hình ảnh phòng, mô tả chi tiết, tiện ích, đặc điểm, đánh giá và form đặt phòng", 
         "Trang tải đầy đủ thông tin chuẩn UI Glassmorphism hiện đại", "PASS"),

        ("TC_RES_13", "Chi tiết phòng", "Điều hướng an toàn khi truy cập URL với ID phòng không tồn tại", "Negative", 
         "1. Gõ trực tiếp trên thanh địa chỉ URL: room_details.php?id=99999\n2. Bấm Enter", 
         "ID: 99999 (Không tồn tại trong DB)", 
         "Hệ thống bắt ngoại lệ, tự động chuyển hướng về trang rooms.php hoặc trang lỗi 404", 
         "Hệ thống redirect về trang danh sách phòng, không lộ thông tin lỗi PHP", "PASS"),

        ("TC_RES_14", "Đặt phòng", "Kiểm tra tính toán Tổng tiền đặt phòng chính xác theo số đêm", "Positive", 
         "1. Chọn đặt phòng giá 800.000 VNĐ/đêm\n2. Chọn thời gian lưu trú 3 đêm\n3. Quan sát tổng tiền", 
         "Giá: 800.000 VNĐ, Số đêm: 3", 
         "Hệ thống tính đúng Tổng tiền = 800.000 * 3 = 2.400.000 VNĐ", 
         "Tổng số tiền hiển thị chuẩn xác 2.400.000 VNĐ trên modal xác nhận", "PASS"),

        ("TC_RES_15", "Đặt phòng", "Chặn đặt phòng khi chưa đăng nhập tài khoản", "Negative", 
         "1. Người dùng là khách vãng lai (chưa đăng nhập)\n2. Bấm nút 'Đặt phòng ngay' tại phòng bất kỳ", 
         "Session: Chưa đăng nhập", 
         "Chặn chuyển trang đặt phòng, tự động bật popup Modal Đăng nhập kèm nhắc nhở", 
         "Modal đăng nhập tự động bật lên yêu cầu khách đăng nhập trước khi đặt", "PASS"),

        ("TC_RES_16", "Đặt phòng", "Chặn gửi form đặt phòng khi để trống Họ tên hoặc SĐT", "Negative", 
         "1. Đã đăng nhập -> Bấm Đặt phòng\n2. Xóa trống ô Họ tên người nhận phòng\n3. Bấm 'Xác nhận đặt'", 
         "Họ tên: ''", 
         "Báo lỗi validation bắt buộc nhập đầy đủ thông tin liên hệ nhận phòng", 
         "Form ngăn chặn submit và yêu cầu điền đầy đủ họ tên", "PASS"),

        ("TC_RES_17", "Thanh toán", "Tạo mã VietQR hợp lệ với đúng số tiền và nội dung đơn đặt", "Positive", 
         "1. Chọn phương thức thanh toán VietQR\n2. Bấm 'Tiến hành thanh toán'", 
         "Số tiền: 2.400.000 VNĐ, Mã đơn: ORD_20261005", 
         "Hiển thị modal chứa mã VietQR chuẩn NAPAS, quét app ngân hàng đọc đúng số tiền và nội dung chuyển khoản", 
         "Mã QR hiển thị sắc nét, đúng số tài khoản khách sạn và số tiền giao dịch", "PASS"),

        ("TC_RES_18", "Thanh toán", "Thanh toán thành công qua cổng giả lập VNPay Sandbox", "Positive", 
         "1. Chọn thanh toán VNPay -> Chuyển sang sandbox\n2. Nhập thông tin thẻ test thành công\n3. Bấm xác nhận", 
         "Thẻ test NCB, OTP: 123456", 
         "Redirect về website, cập nhật booking_status='booked', tạo mã giao dịch và thông báo đặt phòng thành công", 
         "Website ghi nhận đơn thành công, lưu thông tin vào booking_order", "PASS"),

        ("TC_RES_19", "Thanh toán", "Xử lý khi khách bấm Hủy thanh toán tại cổng thanh toán", "Negative", 
         "1. Tại trang VNPay Sandbox -> Bấm nút 'Hủy giao dịch'\n2. Quan sát điều hướng", 
         "Thao tác: Cancel transaction", 
         "Hệ thống redirect về trang bookings.php với thông báo 'Giao dịch bị hủy hoặc thanh toán thất bại'", 
         "Hiển thị trạng thái thanh toán thất bại, không tạo đơn active", "PASS"),

        ("TC_RES_20", "Hóa đơn PDF", "Khách hàng tải về file PDF hóa đơn đặt phòng chuẩn font Tiếng Việt", "Positive", 
         "1. Đăng nhập -> Vào menu 'Lịch sử đặt phòng'\n2. Bấm nút 'Tải Hóa Đơn PDF' tại đơn đã thanh toán", 
         "Booking ID: 101", 
         "Trình duyệt tải về file hóa đơn PDF đầy đủ thông tin phòng, giá tiền, ngày ở, font Tiếng Việt rõ đẹp không lỗi", 
         "File PDF tải về thành công, font mPDF hiển thị chuẩn đẹp", "PASS")
    ]

    ws_truong = wb.create_sheet(title="2. Tìm kiếm & Đặt (Trường)")
    format_tc_sheet(
        ws_truong, 
        "BẢNG TEST CASE - MODULE 2: TÌM KIẾM, BỘ LỌC & ĐẶT PHÒNG FRONTEND", 
        "Phụ trách: Trường | Phạm vi: Tìm kiếm theo ngày, Bộ lọc giá/khách, Chi tiết phòng, Đặt phòng, Thanh toán QR & PDF", 
        truong_tcs
    )


    # =========================================================================
    # 4. SHEET MODULE 3: QUẢN LÝ PHÒNG (PHÁT)
    # =========================================================================
    phat_tcs = [
        ("TC_ADM_RM_01", "Thêm phòng mới", "Thêm phòng mới thành công với đầy đủ dữ liệu hợp lệ", "Positive", 
         "1. Đăng nhập Admin -> Vào 'Rooms' (admin/rooms.php)\n2. Bấm nút 'Thêm phòng'\n3. Điền Tên, Giá, Diện tích, Số lượng, Khách, Mô tả\n4. Tích chọn Features & Facilities -> Bấm 'Lưu lại'", 
         "Tên: 'Deluxe Sea View', Giá: 1500000, Diện tích: 45, SL: 5, Người lớn: 2, Trẻ em: 1", 
         "Hệ thống gọi ajax rooms.php, thêm vào bảng rooms, lưu room_facilities & room_features, báo thành công", 
         "Hiển thị thông báo 'Phòng mới đã được thêm thành công!', danh sách cập nhật ngay lập tức", "PASS"),

        ("TC_ADM_RM_02", "Thêm phòng mới", "Chặn thêm phòng khi để trống Tên phòng", "Negative", 
         "1. Mở modal Thêm phòng\n2. Để trống ô 'Tên phòng', điền đầy đủ các ô khác\n3. Bấm 'Lưu lại'", 
         "Tên phòng: '' (chuỗi rỗng)", 
         "HTML5 validation hiển thị cảnh báo bắt buộc nhập tên phòng, chặn gửi form lên server", 
         "Form chặn submit, viền ô Tên phòng chuyển đỏ cảnh báo", "PASS"),

        ("TC_ADM_RM_03", "Thêm phòng mới", "Chặn thêm phòng khi Giá phòng nhỏ hơn hoặc bằng 0", "Boundary", 
         "1. Nhập thông tin phòng\n2. Ô Giá phòng nhập 0 hoặc -200000\n3. Bấm 'Lưu lại'", 
         "Giá: 0 VNĐ hoặc -200.000 VNĐ", 
         "Báo lỗi 'Giá phòng phải là số dương lớn hơn 0!', không lưu vào CSDL", 
         "Hệ thống báo lỗi giá phòng không hợp lệ, chặn lưu CSDL", "PASS"),

        ("TC_ADM_RM_04", "Thêm phòng mới", "Chặn thêm phòng khi Số lượng phòng là số âm (< 0)", "Boundary", 
         "1. Nhập thông tin phòng\n2. Ô Số lượng (Quantity) nhập -3\n3. Bấm 'Lưu lại'", 
         "Quantity: -3", 
         "Báo lỗi 'Số lượng phòng không hợp lệ!', chặn submit form", 
         "Hệ thống chặn submit và báo lỗi số lượng phòng phải >= 1", "PASS"),

        ("TC_ADM_RM_05", "Thêm phòng mới", "Thêm phòng thành công khi không chọn Tiện ích hoặc Đặc điểm", "Positive", 
         "1. Nhập đầy đủ thông tin cơ bản của phòng\n2. Không tích bất kỳ checkbox tiện ích/đặc điểm nào\n3. Bấm 'Lưu lại'", 
         "Features: [], Facilities: []", 
         "Thêm phòng thành công, danh sách tiện ích của phòng hiển thị trạng thái trống", 
         "Phòng được tạo thành công, cột tiện ích hiển thị 'Chưa có tiện ích'", "PASS"),

        ("TC_ADM_RM_06", "Chỉnh sửa phòng", "Cập nhật thành công thông tin phòng (Tên, Giá, Tiện ích)", "Positive", 
         "1. Tại danh sách phòng -> Bấm nút 'Sửa' (Edit)\n2. Đổi Tên phòng, đổi Giá, tích thêm tiện ích mới\n3. Bấm 'Cập nhật'", 
         "ID: 1, Tên mới: 'Executive VIP Suite', Giá mới: 2500000", 
         "Gọi ajax edit_room, cập nhật bảng rooms và bảng liên kết tiện ích, báo 'Cập nhật thành công!'", 
         "Dữ liệu cập nhật ngay trên giao diện mà không cần reload trang", "PASS"),

        ("TC_ADM_RM_07", "Chỉnh sửa phòng", "Chặn cập nhật khi xóa rỗng Tên phòng", "Negative", 
         "1. Mở modal Edit phòng\n2. Xóa trắng ô 'Tên phòng'\n3. Bấm 'Cập nhật'", 
         "Tên phòng: ''", 
         "Form validation chặn submit, yêu cầu không được để trống tên phòng", 
         "Trình duyệt kích hoạt cảnh báo, chặn gửi request lên server", "PASS"),

        ("TC_ADM_RM_08", "Xóa phòng", "Xóa mềm phòng chưa có đơn active (cập nhật removed = 1)", "Positive", 
         "1. Tại phòng chưa có đơn đặt -> Bấm nút 'Xóa'\n2. Xác nhận 'Đồng ý' tại hộp thoại", 
         "Room ID: 5 (Không có booking active)", 
         "Cập nhật rooms.removed = 1, phòng ẩn khỏi danh sách Admin và trang khách đặt", 
         "Phòng được xóa mềm thành công, biến mất khỏi bảng hiển thị", "PASS"),

        ("TC_ADM_RM_09", "Xóa phòng", "Chặn xóa phòng đang có khách ở hoặc đã có đơn đặt active", "Negative", 
         "1. Tại phòng đang có đơn đặt phòng active -> Bấm 'Xóa'\n2. Xác nhận xóa", 
         "Room ID: 2 (Có đơn booking_status='booked')", 
         "Hệ thống kiểm tra ràng buộc, từ chối xóa và báo 'Không thể xóa phòng đang có lịch đặt!'", 
         "Hiển thị cảnh báo phòng đang được sử dụng, chặn thao tác xóa", "PASS"),

        ("TC_ADM_RM_10", "Xóa phòng", "Hủy thao tác xóa phòng khi bấm nút Hủy tại popup xác nhận", "Positive", 
         "1. Bấm nút 'Xóa' tại phòng bất kỳ\n2. Khi popup xác nhận hiện ra -> Bấm nút 'Hủy bỏ'", 
         "Thao tác: Cancel confirmation", 
         "Đóng popup xác nhận, phòng giữ nguyên vẹn trong danh sách", 
         "Popup đóng lại, không có thay đổi nào trong cơ sở dữ liệu", "PASS"),

        ("TC_ADM_RM_11", "Quản lý ảnh", "Upload ảnh phòng hợp lệ định dạng JPG/PNG dung lượng < 2MB", "Positive", 
         "1. Bấm nút 'Quản lý ảnh' tại phòng bất kỳ\n2. Chọn file ảnh JPG dung lượng 1.2MB\n3. Bấm 'Thêm ảnh'", 
         "File: room_view.jpg (1.2 MB)", 
         "Upload thành công, lưu file vào thư mục images/rooms/ và thêm bản ghi vào room_images", 
         "Ảnh mới xuất hiện ngay lập tức trong danh sách ảnh của phòng", "PASS"),

        ("TC_ADM_RM_12", "Quản lý ảnh", "Chặn upload file ảnh sai định dạng (.exe, .php, .pdf, .docx)", "Negative", 
         "1. Mở modal ảnh phòng\n2. Chọn tệp script shell.php hoặc file pdf\n3. Bấm 'Thêm ảnh'", 
         "File: 'backdoor.php' hoặc 'manual.pdf'", 
         "Hàm uploadImage trả mã 'inv_img', báo lỗi 'Chỉ chấp nhận file định dạng JPG, WEBP, PNG!'", 
         "Hệ thống chặn upload và thông báo định dạng tệp không hợp lệ", "PASS"),

        ("TC_ADM_RM_13", "Quản lý ảnh", "Chặn upload ảnh vượt quá dung lượng tối đa 2MB", "Boundary", 
         "1. Chọn file ảnh PNG kích thước 4.8MB\n2. Bấm 'Thêm ảnh'", 
         "File size: 4.8 MB (> 2MB)", 
         "Hàm uploadImage trả mã 'inv_size', báo lỗi 'Kích thước ảnh không được vượt quá 2MB!'", 
         "Báo lỗi ảnh vượt quá dung lượng quy định, chặn lưu ổ cứng", "PASS"),

        ("TC_ADM_RM_14", "Quản lý ảnh", "Thiết lập ảnh đại diện (Thumbnail) cho phòng thành công", "Positive", 
         "1. Tại danh sách ảnh phòng -> Bấm icon Check tại ảnh mong muốn làm ảnh bìa", 
         "Image ID: 4", 
         "Cập nhật thumb=1 cho ảnh chọn, đồng thời set thumb=0 cho tất cả các ảnh khác của phòng đó", 
         "Badge 'Thumbnail' chuyển sang ảnh mới chọn thành công", "PASS"),

        ("TC_ADM_RM_15", "Quản lý ảnh", "Xóa ảnh phòng thành công và xóa file vật lý trên ổ cứng", "Positive", 
         "1. Bấm icon Thùng rác tại ảnh muốn xóa\n2. Xác nhận xóa ảnh", 
         "Image ID: 6", 
         "Xóa bản ghi trong room_images và xóa tệp tin vật lý khỏi images/rooms/", 
         "Ảnh biến mất khỏi modal, kiểm tra thư mục ổ cứng đã xóa sạch file", "PASS"),

        ("TC_ADM_RM_16", "Tiện ích & Đặc điểm", "Thêm mới Đặc điểm nổi bật (Feature) thành công", "Positive", 
         "1. Vào menu 'Features & Facilities'\n2. Tại form Đặc điểm -> Nhập tên đặc điểm\n3. Bấm 'Thêm mới'", 
         "Tên: 'Ban công hướng biển'", 
         "Lưu bản ghi vào bảng features, hiển thị ngay trên bảng quản lý", 
         "Đặc điểm mới xuất hiện trong bảng và khả dụng khi tạo phòng", "PASS"),

        ("TC_ADM_RM_17", "Tiện ích & Đặc điểm", "Thêm mới Tiện ích (Facility) với file icon SVG hợp lệ", "Positive", 
         "1. Tại form Tiện ích -> Nhập Tên, Mô tả và chọn file icon định dạng SVG\n2. Bấm 'Thêm mới'", 
         "Tên: 'Hồ bơi vô cực', Icon: 'pool.svg'", 
         "Lưu file SVG vào images/facilities/ và thêm bản ghi vào bảng facilities", 
         "Tiện ích mới hiển thị đầy đủ icon SVG và mô tả chuẩn xác", "PASS"),

        ("TC_ADM_RM_18", "Tiện ích & Đặc điểm", "Chặn thêm Tiện ích khi upload icon không phải định dạng SVG", "Negative", 
         "1. Nhập thông tin Tiện ích\n2. Chọn file icon định dạng PNG hoặc JPG\n3. Bấm 'Thêm mới'", 
         "Icon: 'icon.png'", 
         "Hệ thống kiểm tra mime type, trả lỗi 'Chỉ chấp nhận file định dạng SVG cho icon tiện ích!'", 
         "Báo lỗi định dạng icon không hợp lệ, chặn upload", "PASS"),

        ("TC_ADM_RM_19", "Tiện ích & Đặc điểm", "Chặn xóa Tiện ích khi đang được gán cho một phòng", "Negative", 
         "1. Bấm nút 'Xóa' tại tiện ích đang được liên kết trong bảng room_facilities\n2. Quan sát thông báo", 
         "Facility ID: 1 (Đang gán cho Room 1, Room 2)", 
         "Hệ thống kiểm tra foreign key constraint, trả mã 'room_added', báo 'Không thể xóa vì tiện ích đang được gán cho phòng!'", 
         "Chặn xóa thành công, bảo vệ tính toàn vẹn dữ liệu hệ thống", "PASS"),

        ("TC_ADM_RM_20", "Tiện ích & Đặc điểm", "Kiểm tra ràng buộc chống trùng lặp tên Tiện ích khi thêm mới", "Negative", 
         "1. Nhập tên Tiện ích đã có sẵn trong danh sách ('Wifi')\n2. Bấm 'Thêm mới'", 
         "Tên tiện ích: 'Wifi' (Đã tồn tại)", 
         "Hệ thống phát hiện trùng tên, thông báo 'Tiện ích này đã tồn tại trong hệ thống!'", 
         "Hệ thống cho phép thêm 2 Tiện ích trùng tên 'Wifi' vào CSDL (DEFECT)", "FAIL")
    ]

    phat_transitions = [
        ("Phòng Trống (status=1)", "Khách hoàn tất thanh toán đặt phòng", "Đã Đặt (Booked)", "HỢP LỆ", "Hệ thống ghi nhận đơn, phòng vẫn khả dụng trong kho nếu số lượng > 0"),
        ("Phòng Đã Đặt", "Admin gán số phòng và nhận phòng (arrival=1)", "Đang Sử Dụng (Occupied)", "HỢP LỆ", "Cập nhật arrival=1, phòng chính thức có khách đang lưu trú thực tế"),
        ("Đang Sử Dụng", "Khách Check-out trả phòng", "Dọn Dẹp (Cleaning - status=2)", "HỢP LỆ", "Chuyển trạng thái phòng sang Dọn dẹp, nhân viên buồng phòng tiến hành vệ sinh"),
        ("Dọn Dẹp (status=2)", "Nhân viên dọn xong, Admin bấm 'Sẵn sàng đón khách'", "Phòng Trống (status=1)", "HỢP LỆ", "Cập nhật status=1, phòng sẵn sàng xuất hiện trên trang tìm kiếm cho khách mới"),
        ("Dọn Dẹp (status=2)", "Khách tìm kiếm và cố tình đặt phòng đang dọn", "KHÔNG THỂ CHUYỂN", "KHÔNG HỢP LỆ", "BẢO VỆ NGHIỆP VỤ: Backend loại trừ các phòng có status=2 khỏi danh sách phòng trống"),
        ("Đang Có Khách Ở", "Admin cố tình bấm nút 'Xóa phòng'", "KHÔNG THỂ CHUYỂN", "KHÔNG HỢP LỆ", "RÀNG BUỘC TOÀN VẸN: Hệ thống chặn xóa phòng đang có đơn lưu trú active")
    ]

    ws_phat = wb.create_sheet(title="3. Quản lý Phòng (Phát)")
    format_tc_sheet(
        ws_phat, 
        "BẢNG TEST CASE - MODULE 3: QUẢN LÝ PHÒNG (ROOM MANAGEMENT - BACKEND ADMIN)", 
        "Phụ trách: Phát | Phạm vi: Thêm/Sửa/Xóa phòng, Quản lý ảnh, Quản lý Tiện ích & Ma trận chuyển đổi trạng thái phòng", 
        phat_tcs,
        phat_transitions
    )


    # =========================================================================
    # 5. SHEET MODULE 4: QUẢN LÝ ĐƠN ĐẶT PHÒNG (MINH)
    # =========================================================================
    minh_tcs = [
        ("TC_ADM_BK_01", "Xem danh sách đơn", "Xem toàn bộ đơn đặt phòng mới chờ xử lý tại trang New Bookings", "Positive", 
         "1. Đăng nhập Admin -> Menu 'Bookings' -> Chọn 'New Bookings'\n2. Quan sát bảng dữ liệu hiển thị", 
         "Filter: booking_status='booked' AND arrival=0", 
         "Hiển thị đầy đủ danh sách đơn đặt phòng mới chưa nhận phòng: Mã đơn, Khách, Phòng, Ngày, Tiền, Action", 
         "Danh sách đơn tải đầy đủ qua AJAX, có các nút 'Assign Room' và 'Cancel Booking'", "PASS"),

        ("TC_ADM_BK_02", "Tìm kiếm đơn", "Tìm kiếm đơn đặt theo Mã đơn (Order ID) hoặc Tên khách hàng", "Positive", 
         "1. Tại ô tìm kiếm New Bookings -> Nhập mã 'ORD_102'\n2. Quan sát kết quả lọc", 
         "Từ khóa: 'ORD_102' hoặc 'Nguyễn Tuấn Minh'", 
         "Hệ thống lọc realtime qua Ajax, chỉ hiển thị các đơn khớp với từ khóa tìm kiếm", 
         "Bảng dữ liệu lọc chuẩn xác theo Order ID và tên khách hàng", "PASS"),

        ("TC_ADM_BK_03", "Lịch sử đơn phòng", "Xem lịch sử đơn đặt phòng và phân trang tại Booking Records", "Positive", 
         "1. Vào menu 'Booking Records' (admin/booking_records.php)\n2. Bấm chuyển trang 2, trang 3", 
         "URL: admin/booking_records.php, Limit: 10 đơn/trang", 
         "Hiển thị tất cả lịch sử đơn (đã nhận phòng, đã hủy), phân trang mượt mà không lỗi", 
         "Bảng hiển thị đầy đủ lịch sử đơn, phân trang chuẩn xác", "PASS"),

        ("TC_ADM_BK_04", "Lịch sử đơn phòng", "Tìm kiếm hồ sơ đơn theo Số điện thoại khách hàng", "Positive", 
         "1. Tại Booking Records -> Nhập SĐT vào ô tìm kiếm\n2. Quan sát danh sách", 
         "SĐT: '0987654321'", 
         "Hiển thị toàn bộ các đơn đặt phòng thuộc về số điện thoại đã nhập", 
         "Bảng lọc đúng các đơn của khách, nếu không thấy báo 'No Data Found!'", "PASS"),

        ("TC_ADM_BK_05", "Gán số phòng", "Mở Modal gán số phòng khi bấm nút 'Assign Room'", "Positive", 
         "1. Tại New Bookings -> Bấm nút 'Assign Room' tại đơn đặt bất kỳ\n2. Quan sát modal", 
         "Booking ID: 101", 
         "Hiển thị Modal gán phòng chứa form nhập 'Room Number' và hiển thị tên khách tương ứng", 
         "Modal popup mở ra chuẩn xác, input focus sẵn sàng nhập liệu", "PASS"),

        ("TC_ADM_BK_06", "Gán số phòng", "Gán số phòng thành công với số phòng hợp lệ và cập nhật arrival = 1", "Positive", 
         "1. Mở Modal Assign Room\n2. Nhập số phòng 'P.302'\n3. Bấm nút 'Assign'", 
         "Booking ID: 101, room_no: 'P.302'", 
         "Lưu room_no vào booking_details, set arrival=1, báo 'Room Number Alloted! Booking Finalized!', đơn chuyển sang Records", 
         "Hệ thống báo thành công, đơn hoàn tất và chuyển khỏi danh sách New Bookings", "PASS"),

        ("TC_ADM_BK_07", "Gán số phòng", "Chặn submit gán phòng khi để trống ô nhập số phòng", "Negative", 
         "1. Mở Modal Assign Room\n2. Để trống ô 'Room Number'\n3. Bấm nút 'Assign'", 
         "room_no: '' (trống)", 
         "Validation chặn submit, yêu cầu nhập số phòng cụ thể trước khi hoàn tất", 
         "Trình duyệt kích hoạt cảnh báo required, chặn gửi request", "PASS"),

        ("TC_ADM_BK_08", "Gán số phòng", "Hủy bỏ thao tác gán phòng khi đóng Modal (dữ liệu giữ nguyên)", "Positive", 
         "1. Mở Modal Assign Room\n2. Bấm icon 'X' hoặc nút 'Hủy' đóng modal mà không submit", 
         "Thao tác: Dismiss Modal", 
         "Đóng modal, đơn đặt phòng giữ nguyên trạng thái arrival=0 trong New Bookings", 
         "Modal đóng an toàn, không phát sinh thay đổi dữ liệu trong CSDL", "PASS"),

        ("TC_ADM_BK_09", "Gán số phòng", "Kiểm tra tính toàn vẹn dữ liệu room_no trong bảng booking_details", "Positive", 
         "1. Gán số phòng cho đơn -> Truy vấn CSDL bảng booking_details\n2. Kiểm tra cột room_no", 
         "room_no: 'VIP-501'", 
         "Giá trị 'VIP-501' được lưu chuẩn xác vào database, hiển thị đúng trên hóa đơn PDF", 
         "Dữ liệu đồng bộ nhất quán giữa Database, Web Admin và file PDF", "PASS"),

        ("TC_ADM_BK_10", "Hủy đơn đặt phòng", "Admin hủy đơn đặt mới thành công và chuyển sang chờ hoàn tiền", "Positive", 
         "1. Tại New Bookings -> Bấm nút 'Cancel Booking' tại đơn mới\n2. Xác nhận popup", 
         "Booking ID: 103 (Status: booked, Arrival: 0)", 
         "Cập nhật booking_status='cancelled', refund=0, đơn chuyển sang trang Refund Bookings", 
         "Đơn hủy thành công, biến mất khỏi New Bookings và xuất hiện tại Refund Bookings", "PASS"),

        ("TC_ADM_BK_11", "Hoàn tiền đơn hủy", "Xử lý duyệt hoàn tiền cho đơn đã hủy tại trang Refund Bookings", "Positive", 
         "1. Vào menu 'Refund Bookings'\n2. Bấm 'Refund' tại đơn đã hủy\n3. Xác nhận", 
         "Booking ID: 103, refund = 0 -> 1", 
         "Cập nhật booking_order.refund = 1, báo hoàn tiền thành công, xóa khỏi danh sách chờ hoàn", 
         "Đơn được duyệt hoàn tiền thành công, trạng thái lưu trữ chính xác", "PASS"),

        ("TC_ADM_BK_12", "Hủy đơn đặt phòng", "Chặn thao tác hủy đơn đối với đơn khách đã nhận phòng (arrival = 1)", "Negative", 
         "1. Tại đơn đã nhận phòng (arrival=1)\n2. Cố tình gửi request hủy đơn qua API/Ajax", 
         "Booking ID: 101 (Arrival = 1)", 
         "Hệ thống từ chối hủy, báo lỗi vì khách đã thực tế nhận phòng và đang lưu trú", 
         "Nút Hủy bị ẩn trên giao diện; API backend chặn từ chối xử lý", "PASS"),

        ("TC_ADM_BK_13", "Gán số phòng", "Chặn thao tác gán số phòng đối với đơn đã bị hủy (cancelled)", "Negative", 
         "1. Cố tình gửi request ajax assign_room cho đơn có booking_status='cancelled'", 
         "Booking ID: 103 (Status: cancelled)", 
         "Backend kiểm tra trạng thái đơn, từ chối gán phòng cho đơn đã hủy", 
         "Hệ thống bảo vệ an toàn nghiệp vụ, chặn thao tác gán phòng", "PASS"),

        ("TC_ADM_BK_14", "Xuất hóa đơn PDF", "Xuất file PDF hóa đơn thanh toán thành công từ generate_pdf.php", "Positive", 
         "1. Tại Booking Records -> Bấm nút 'Download PDF' tại đơn có arrival=1\n2. Quan sát file", 
         "URL: admin/generate_pdf.php?gen_pdf&id=101", 
         "Tải về file PDF hóa đơn chính thức: Tên KS, Order ID, Khách, Phòng, Số phòng, Ngày ở, Tổng tiền", 
         "File PDF sinh ra hoàn hảo, đầy đủ chi tiết giao dịch khách sạn", "PASS"),

        ("TC_ADM_BK_15", "Xuất hóa đơn PDF", "Xuất hóa đơn PDF đối với đơn đã bị hủy (ghi rõ CANCELLED)", "Positive", 
         "1. Bấm 'Download PDF' tại đơn có booking_status='cancelled'\n2. Mở file PDF xem", 
         "Booking ID: 103 (Cancelled)", 
         "File PDF ghi rõ watermark/badge 'CANCELLED', hiển thị chi tiết số tiền và trạng thái hoàn tiền", 
         "File PDF thể hiện chuẩn trạng thái đơn hủy và tiền hoàn", "PASS"),

        ("TC_ADM_BK_16", "Bảo mật URL", "Chặn truy cập direct URL generate_pdf.php khi thiếu id hoặc id âm", "Negative", 
         "1. Gõ URL: admin/generate_pdf.php?gen_pdf&id=-1\n2. Bấm Enter", 
         "id: -1 hoặc thiếu id", 
         "Chuyển hướng về trang booking_records.php, chặn phát sinh lỗi exception PHP", 
         "Hệ thống redirect an toàn, không hiển thị trang trắng hoặc lỗi code", "PASS"),

        ("TC_ADM_BK_17", "Phân quyền", "Chặn truy cập tính năng xuất hóa đơn Admin khi chưa đăng nhập", "Negative", 
         "1. Mở tab ẩn danh (chưa đăng nhập Admin)\n2. Dán link: admin/generate_pdf.php?gen_pdf&id=101", 
         "Session: Chưa đăng nhập Admin", 
         "Hàm adminLogin() chặn truy cập, tự động chuyển hướng về admin/index.php", 
         "Hệ thống bảo vệ an toàn, chuyển hướng về form đăng nhập quản trị", "PASS"),

        ("TC_ADM_BK_18", "Xuất hóa đơn PDF", "Kiểm tra định dạng file PDF khi tên khách hàng hoặc địa chỉ quá dài", "Boundary", 
         "1. Đơn đặt có tên khách 100 ký tự và địa chỉ 150 ký tự\n2. Bấm 'Download PDF'", 
         "Tên: Chuỗi 100 ký tự, Địa chỉ: Chuỗi 150 ký tự", 
         "Thư viện mPDF tự động xuống dòng và co dãn bảng, không bị tràn lề hay che khuất chữ ký", 
         "File PDF bị tràn lề, bảng dữ liệu bị đẩy vỡ trang che khuất chữ ký (DEFECT)", "FAIL"),

        ("TC_ADM_BK_19", "Trạng thái đơn", "Kiểm tra xử lý đơn khi thanh toán thất bại tại cổng (payment failed)", "Positive", 
         "1. Khách giao dịch thất bại tại cổng thanh toán\n2. Kiểm tra CSDL bảng booking_order", 
         "booking_status = 'payment failed'", 
         "Đơn lưu trạng thái 'payment failed', không xuất hiện trong New Bookings của Admin", 
         "Hệ thống xử lý chuẩn xác, không tạo đơn rác cho lễ tân xử lý", "PASS"),

        ("TC_ADM_BK_20", "Giải phóng phòng", "Đồng bộ giải phóng trạng thái phòng khi kết thúc lưu trú", "Positive", 
         "1. Khách hoàn thành thời gian lưu trú (Check-out)\n2. Kiểm tra đồng bộ hệ thống phòng", 
         "Đơn hoàn thành checkout", 
         "Hệ thống cho phép lễ tân cập nhật trạng thái phòng sang Dọn dẹp/Trống để đón lượt khách mới", 
         "Quy trình tuần hoàn buồng phòng hoạt động trơn tru và logic", "PASS")
    ]

    minh_transitions = [
        ("S1: Đơn mới (booked, arrival=0)", "Admin bấm 'Assign Room' & nhập số phòng hợp lệ", "S2: Đã nhận phòng (arrival=1)", "HỢP LỆ", "Lưu số phòng vào booking_details, chuyển đơn sang Booking Records"),
        ("S1: Đơn mới (booked, arrival=0)", "Admin bấm 'Cancel Booking' tại New Bookings", "S3: Đã hủy (cancelled, refund=0)", "HỢP LỆ", "Cập nhật status='cancelled', chuyển sang danh sách chờ duyệt hoàn tiền"),
        ("S2: Đã nhận phòng (arrival=1)", "Admin bấm 'Download PDF' xuất hóa đơn lưu trữ", "S2: Đã nhận phòng (Lưu trữ hồ sơ)", "HỢP LỆ", "Xuất hóa đơn PDF chính thức hoàn chỉnh cho khách hàng"),
        ("Đang thanh toán", "Cổng thanh toán báo lỗi / Hết hạn thanh toán", "S4: Lỗi thanh toán (payment failed)", "HỢP LỆ", "Ghi nhận trạng thái thanh toán thất bại, không cấp phòng"),
        ("S3: Đã hủy (cancelled)", "Cố tình gọi request gán số phòng cho đơn đã hủy", "KHÔNG THỂ CHUYỂN", "KHÔNG HỢP LỆ", "BẢO VỆ NGHIỆP VỤ: Backend chặn gán phòng cho đơn đã hủy"),
        ("S2: Đã nhận phòng (arrival=1)", "Cố tình bấm nút hủy đơn đặt phòng", "KHÔNG THỂ CHUYỂN", "KHÔNG HỢP LỆ", "BẢO VỆ NGHIỆP VỤ: Khách đã nhận phòng thực tế, hệ thống khóa tính năng hủy đơn")
    ]

    ws_minh = wb.create_sheet(title="4. Quản lý Đơn (Minh)")
    format_tc_sheet(
        ws_minh, 
        "BẢNG TEST CASE - MODULE 4: QUẢN LÝ ĐƠN ĐẶT PHÒNG (BOOKING MANAGEMENT)", 
        "Phụ trách: Minh | Phạm vi: Lọc đơn New Bookings, Gán số phòng Assign Room, Hủy đơn/Hoàn tiền, Xuất PDF & Ma trận trạng thái", 
        minh_tcs,
        minh_transitions
    )


    # =========================================================================
    # 6. SHEET MODULE 5: PHẢN HỒI & THỐNG KÊ (HIẾU) - VIẾT MỚI TOANH CỰC CHUẨN!
    # =========================================================================
    hieu_tcs = [
        ("TC_STAT_01", "Biểu mẫu liên hệ", "Khách hàng gửi liên hệ thành công với đầy đủ thông tin hợp lệ", "Positive", 
         "1. Khách truy cập trang contact.php\n2. Nhập đầy đủ Họ tên, Email, Tiêu đề, Lời nhắn\n3. Bấm nút 'Gửi liên hệ'", 
         "Họ tên: 'Lê Minh Hiếu', Email: 'hieu.lm@gmail.com', Tiêu đề: 'Hỏi dịch vụ đưa đón', Lời nhắn: 'Khách sạn có xe đưa đón sân bay không?'", 
         "Hệ thống lưu vào bảng user_queries (seen=0), hiển thị thông báo 'Gửi tin nhắn liên hệ thành công!'", 
         "Gửi thành công, tin nhắn được lưu vào CSDL và xóa trắng form sau khi gửi", "PASS"),

        ("TC_STAT_02", "Biểu mẫu liên hệ", "Chặn gửi liên hệ khi để trống các trường bắt buộc (Họ tên, Lời nhắn)", "Negative", 
         "1. Mở form liên hệ contact.php\n2. Để trống ô 'Họ tên' hoặc ô 'Lời nhắn'\n3. Bấm 'Gửi liên hệ'", 
         "Họ tên: '', Lời nhắn: ''", 
         "HTML5 validation kích hoạt nhắc nhở bắt buộc điền thông tin, chặn gửi form lên server", 
         "Form chặn submit và báo lỗi viền đỏ tại các ô bắt buộc", "PASS"),

        ("TC_STAT_03", "Biểu mẫu liên hệ", "Chặn gửi liên hệ khi nhập Email sai định dạng", "Negative", 
         "1. Nhập Họ tên, Tiêu đề, Lời nhắn\n2. Ô Email nhập 'hieulegmail.com' (thiếu @)\n3. Bấm 'Gửi'", 
         "Email: 'hieulegmail.com'", 
         "Trình duyệt kích hoạt cảnh báo email không hợp lệ, chặn gửi dữ liệu", 
         "Trình duyệt cảnh báo yêu cầu nhập đúng định dạng email", "PASS"),

        ("TC_STAT_04", "Biểu mẫu liên hệ", "Kiểm tra giới hạn độ dài Lời nhắn liên hệ tối đa 500 ký tự", "Boundary", 
         "1. Dán nội dung lời nhắn dài 500 ký tự\n2. Bấm 'Gửi liên hệ'", 
         "Lời nhắn: Văn bản 500 ký tự", 
         "Hệ thống tiếp nhận đầy đủ toàn bộ 500 ký tự mà không bị cắt cụt dữ liệu", 
         "Dữ liệu được lưu trọn vẹn vào CSDL user_queries", "PASS"),

        ("TC_STAT_05", "Quản lý liên hệ", "Admin xem danh sách tin nhắn và hiển thị badge tin chưa đọc", "Positive", 
         "1. Đăng nhập Admin -> Vào trang 'Tin nhắn liên hệ' (admin/user_queries.php)\n2. Quan sát bảng", 
         "URL: admin/user_queries.php, user_queries.seen = 0", 
         "Hiển thị đầy đủ danh sách tin nhắn gửi từ khách hàng, hiển thị badge số lượng tin nhắn chưa đọc", 
         "Bảng hiển thị đầy đủ tin nhắn, badge đếm tin mới chưa đọc hoạt động chính xác", "PASS"),

        ("TC_STAT_06", "Quản lý liên hệ", "Admin đánh dấu đã đọc một tin nhắn liên hệ thành công", "Positive", 
         "1. Tại dòng tin nhắn chưa đọc -> Bấm nút 'Đánh dấu đã đọc' (Mark as read)\n2. Quan sát badge", 
         "Query ID: 5, seen: 0 -> 1", 
         "Cập nhật user_queries.seen = 1, nút chuyển sang trạng thái đã đọc, badge tin chưa đọc giảm đi 1", 
         "Tin nhắn cập nhật trạng thái đã xem thành công, số lượng tin chưa đọc giảm 1", "PASS"),

        ("TC_STAT_07", "Quản lý liên hệ", "Admin đánh dấu đã đọc toàn bộ tin nhắn liên hệ (Mark all as read)", "Positive", 
         "1. Tại trang user_queries.php -> Bấm nút 'Đánh dấu tất cả đã đọc'\n2. Xác nhận", 
         "Tất cả bản ghi user_queries có seen = 0", 
         "Cập nhật toàn bộ seen = 1, badge tin chưa đọc biến mất hoặc về 0", 
         "Toàn bộ tin nhắn được đánh dấu đã đọc trong một thao tác duy nhất", "PASS"),

        ("TC_STAT_08", "Quản lý liên hệ", "Admin xóa một tin nhắn liên hệ thành công", "Positive", 
         "1. Bấm nút 'Xóa' (Delete) tại dòng tin nhắn bất kỳ\n2. Xác nhận tại popup", 
         "Query ID: 3", 
         "Xóa bản ghi khỏi bảng user_queries, tin nhắn biến mất khỏi danh sách", 
         "Tin nhắn được xóa thành công khỏi hệ thống", "PASS"),

        ("TC_STAT_09", "Quản lý liên hệ", "Admin xóa toàn bộ tin nhắn liên hệ đã đọc (seen=1)", "Positive", 
         "1. Bấm nút 'Xóa tất cả tin đã đọc'\n2. Xác nhận xóa", 
         "DELETE FROM user_queries WHERE seen = 1", 
         "Xóa sạch tất cả các tin nhắn đã đọc, giữ lại các tin nhắn mới chưa đọc", 
         "Hệ thống dọn dẹp tin nhắn đã xử lý thành công, giữ an toàn tin mới", "PASS"),

        ("TC_STAT_10", "Đánh giá & Review", "Khách đã trả phòng gửi đánh giá số sao (1-5 sao) và nhận xét thành công", "Positive", 
         "1. Khách hàng đã check-out đăng nhập -> Vào bookings.php\n2. Bấm 'Đánh giá & Xếp hạng'\n3. Chọn 5 sao, nhập nhận xét\n4. Bấm 'Gửi'", 
         "Booking ID: 101, Rating: 5, Review: 'Phòng ốc rất sạch sẽ và view biển đẹp!'", 
         "Lưu vào bảng rating_review, hiển thị thông báo 'Cảm ơn bạn đã gửi đánh giá!'", 
         "Đánh giá được lưu thành công, xuất hiện tại trang chi tiết phòng", "PASS"),

        ("TC_STAT_11", "Đánh giá & Review", "Chặn khách hàng chưa đặt phòng hoặc chưa check-out gửi đánh giá", "Negative", 
         "1. Khách chưa đặt phòng hoặc đơn đang ở trạng thái 'booked' chưa nhận phòng\n2. Tìm nút Đánh giá", 
         "Đơn đặt chưa hoàn tất lưu trú (arrival = 0)", 
         "Giao diện ẩn nút Đánh giá; nếu cố gọi API review_room.php thì backend chặn từ chối", 
         "Nút Đánh giá không xuất hiện đối với đơn chưa check-out, bảo đảm tính xác thực", "PASS"),

        ("TC_STAT_12", "Đánh giá & Review", "Chặn gửi đánh giá khi chưa chọn số sao (0 sao)", "Negative", 
         "1. Mở modal Đánh giá phòng\n2. Để nguyên 0 sao, nhập bình luận\n3. Bấm 'Gửi đánh giá'", 
         "Rating: 0", 
         "Thông báo lỗi 'Vui lòng chọn số sao đánh giá (từ 1 đến 5 sao)!'", 
         "Hệ thống chặn gửi đánh giá và yêu cầu người dùng chọn số sao", "PASS"),

        ("TC_STAT_13", "Đánh giá & Review", "Chặn gửi đánh giá khi để trống nội dung nhận xét", "Negative", 
         "1. Mở modal Đánh giá\n2. Chọn 5 sao nhưng để trống ô bình luận\n3. Bấm 'Gửi'", 
         "Review: '' (rỗng)", 
         "Thông báo lỗi 'Vui lòng nhập nội dung nhận xét của bạn!'", 
         "Form chặn submit và nhắc nhở nhập nội dung đánh giá", "PASS"),

        ("TC_STAT_14", "Đánh giá & Review", "Kiểm tra điểm số sao trung bình của phòng cập nhật tự động", "Positive", 
         "1. Gửi đánh giá 5 sao cho phòng vừa có điểm trung bình 4.0\n2. Vào lại trang rooms.php", 
         "Rating mới: 5 sao", 
         "Hệ thống tính lại AVG(rating) trong bảng rating_review và hiển thị điểm trung bình mới chính xác", 
         "Số sao trung bình của phòng được cập nhật tự động và chính xác", "PASS"),

        ("TC_STAT_15", "Đánh giá & Review", "Admin xem danh sách đánh giá và xóa đánh giá vi phạm tiêu chuẩn", "Positive", 
         "1. Vào trang Quản lý đánh giá (admin/rate_review.php)\n2. Bấm 'Xóa' tại đánh giá chứa từ ngữ xấu", 
         "Review ID: 8", 
         "Xóa bản ghi khỏi bảng rating_review, điểm trung bình phòng tự động tính toán lại", 
         "Đánh giá bị xóa thành công, hệ thống tính lại điểm số sao chính xác", "PASS"),

        ("TC_STAT_16", "Thống kê Dashboard", "Xem bảng thống kê tổng quan (Doanh thu, Đơn mới, Khách) theo chu kỳ 7 ngày", "Positive", 
         "1. Đăng nhập Admin -> Vào 'Dashboard' (admin/dashboard.php)\n2. Chọn lọc thời gian: '7 ngày qua'", 
         "Period: 0 (7 days)", 
         "Gọi ajax dashboard.php, hiển thị số liệu chính xác: Tổng đơn, Doanh thu 7 ngày, Đơn active, Đơn hủy", 
         "Các thẻ card KPI và biểu đồ Chart.js tải số liệu 7 ngày mượt mà", "PASS"),

        ("TC_STAT_17", "Thống kê Dashboard", "Lọc thống kê linh hoạt theo các chu kỳ: 30 ngày, 90 ngày, 1 năm và Toàn bộ", "Positive", 
         "1. Tại dropdown thời gian trên Dashboard\n2. Lần lượt chọn 30 ngày, 90 ngày, 1 năm, Toàn bộ thời gian\n3. Quan sát", 
         "Period: 1, 2, 3, 4", 
         "Dữ liệu doanh thu và số lượng đơn cập nhật tương ứng theo từng khoảng thời gian đã chọn", 
         "Số liệu thay đổi đồng bộ theo chu kỳ lọc mà không cần reload toàn bộ trang", "PASS"),

        ("TC_STAT_18", "Thống kê Dashboard", "Kiểm tra tính chính xác của công thức tính Tỷ lệ hủy phòng (Cancel Rate)", "Positive", 
         "1. Lấy số liệu: Đơn thành công = 80, Đơn hủy = 20\n2. So sánh với tỷ lệ trên Dashboard", 
         "Công thức: [20 / (80 + 20)] * 100% = 20.0%", 
         "Thẻ KPI Cancel Rate hiển thị chính xác con số 20.0%", 
         "Tỷ lệ hủy phòng hiển thị chuẩn xác theo đúng công thức nghiệp vụ", "PASS"),

        ("TC_STAT_19", "Thống kê Dashboard", "Kiểm tra thống kê số phòng trống thời gian thực khớp CSDL", "Positive", 
         "1. Đếm số phòng trống trên thẻ thống kê Dashboard\n2. Đối chiếu câu lệnh SELECT COUNT trong CSDL", 
         "Query: status=1 AND removed=0", 
         "Số lượng phòng trống hiển thị trên Dashboard khớp 100% với số lượng thực tế trong DB", 
         "Số liệu phòng trống hiển thị hoàn toàn trùng khớp với CSDL", "PASS"),

        ("TC_STAT_20", "Thống kê Dashboard", "Kiểm tra xử lý phép tính Giá trị đơn trung bình khi chu kỳ có 0 đơn đặt", "Boundary", 
         "1. Chọn khoảng thời gian mới chưa phát sinh đơn đặt phòng nào (0 đơn)\n2. Quan sát ô 'Giá trị đơn TB'", 
         "Total Bookings: 0, Total Revenue: 0", 
         "Hệ thống kiểm tra điều kiện an toàn, hiển thị 0 VNĐ mà không phát sinh cảnh báo chia cho 0", 
         "Phát sinh PHP Warning: Division by zero trong admin/ajax/dashboard.php dòng 52 (DEFECT)", "FAIL")
    ]

    ws_hieu = wb.create_sheet(title="5. Phản hồi & Thống kê (Hiếu)")
    format_tc_sheet(
        ws_hieu, 
        "BẢNG TEST CASE - MODULE 5: PHẢN HỒI & THỐNG KÊ (FEEDBACK & STATISTICS)", 
        "Phụ trách: Hiếu | Phạm vi: Form liên hệ Contact Us, Đánh giá Rating & Review, Dashboard Analytics & Báo cáo", 
        hieu_tcs
    )

    # Lưu workbook
    wb.save(output_path)
    # Lưu thêm 1 bản vào thư mục docs/ của dự án
    backup_docs_path = r'e:\School\KiemThu\Hotel-Booking-Website\docs\Bang_Test_Case_Hoan_Chinh_Hotel_Booking.xlsx'
    wb.save(backup_docs_path)
    print(f"Da tao thanh cong file Excel tai:\n1. {output_path}\n2. {backup_docs_path}")

if __name__ == '__main__':
    build_testcase_excel()
