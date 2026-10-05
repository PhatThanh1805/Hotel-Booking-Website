# -*- coding: utf-8 -*-
"""
Script xây dựng Bảng Test Design chuẩn theo Template giáo viên (2. Template_Testdesign.xlsx)
Dành riêng cho phần của Đào Tấn Phát: Phân hệ Quản lý Phòng nghỉ (Room Management)
Cập nhật: Tối giản hóa tối đa cột Note (chỉ ghi điều kiện ngắn gọn, súc tích).
"""

import openpyxl
from openpyxl.styles import Font, PatternFill, Alignment, Border, Side
from openpyxl.utils import get_column_letter

# Danh sách 40 Test Cases chuẩn mực với cột Note TỐI GIẢN
TEST_CASES_DATA = [
    # -------------------------------------------------------------
    # 1. XEM DANH SÁCH PHÒNG & THẺ THỐNG KÊ (METRIC COUNTERS)
    # -------------------------------------------------------------
    {
        "lvl1": "Quản lý phòng nghỉ\n(Room Management)",
        "lvl2": "Xem danh sách phòng & Thống kê",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_LST_01",
        "desc": "Hiển thị đầy đủ thông tin danh sách phòng nghỉ (STT, Tên phòng, Mã phòng, Diện tích, Sức chứa, Giá tiền VNĐ, Số lượng, Badge trạng thái, Thao tác).",
        "type": "GUI",
        "note": "Danh sách phòng tồn tại",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_LST_02",
        "desc": "Kiểm tra 6 Thẻ Metric Counters ở đầu trang thống kê chính xác số lượng phòng theo thời gian thực (Đang Trống, Đang Dọn Dẹp, Đang Có Khách, Đã Đặt, Tạm Dừng, Tổng Số Phòng).",
        "type": "Function",
        "note": "Thẻ counter hiển thị",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 2. BỘ LỌC TRẠNG THÁI & TÌM KIẾM PHÒNG (FILTER & SEARCH)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Bộ lọc & Tìm kiếm phòng",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FLT_01",
        "desc": "Lọc danh sách phòng theo tab trạng thái 'Đang Trống' (status=1).",
        "type": "Function",
        "note": "Tab trạng thái 'Đang Trống'",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FLT_02",
        "desc": "Lọc danh sách phòng theo các tab trạng thái: 'Dọn Dẹp' (status=2), 'Đang Có Khách' (status=3), 'Đã Đặt' (status=4), 'Tạm Dừng' (status=0).",
        "type": "Function",
        "note": "Tab trạng thái khác",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FLT_03",
        "desc": "Tìm kiếm phòng theo tên phòng thời gian thực (Realtime Search) với từ khóa có dấu và không dấu (VD: 'Supreme', 'Luxury', 'Deluxe').",
        "type": "Function",
        "note": "Tên phòng có trong hệ thống",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_FLT_04",
        "desc": "Tìm kiếm phòng với từ khóa không tồn tại trong hệ thống (VD: 'Phòng Tổng Thống 8888').",
        "type": "Function",
        "note": "Tên phòng không tồn tại",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 3. THÊM MỚI PHÒNG NGHỈ (ADD ROOM)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Thêm mới phòng nghỉ",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_ADD_01",
        "desc": "Thêm mới loại phòng thành công với đầy đủ thông tin hợp lệ (Tên, Giá, Diện tích, Số lượng, Người lớn, Trẻ em, Mô tả, tích chọn Tiện ích & Đặc điểm).",
        "type": "Function",
        "note": "Thông tin phòng hợp lệ",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_ADD_02",
        "desc": "Thêm mới phòng thành công khi không chọn bất kỳ Tiện ích (Facilities) hoặc Đặc điểm (Features) nào.",
        "type": "Function",
        "note": "Không chọn tiện ích/đặc điểm",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_ADD_03",
        "desc": "Chặn thêm mới phòng khi để trống ô 'Tên phòng' (Trường bắt buộc - HTML5 required).",
        "type": "Function",
        "note": "Để trống tên phòng",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_ADD_04",
        "desc": "Chặn thêm mới phòng khi nhập Giá phòng bằng 0 hoặc số âm (Kiểm tra giá trị biên).",
        "type": "Boundary",
        "note": "Giá phòng <= 0",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_ADD_05",
        "desc": "Chặn thêm mới phòng khi nhập Số lượng phòng là số âm (< 0).",
        "type": "Boundary",
        "note": "Số lượng phòng < 0",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_ADD_06",
        "desc": "Chặn thêm mới phòng khi nhập Diện tích phòng nhỏ hơn hoặc bằng 0 m².",
        "type": "Boundary",
        "note": "Diện tích phòng <= 0",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_ADD_07",
        "desc": "Chặn thêm mới phòng khi nhập Số người lớn hoặc Trẻ em là số âm.",
        "type": "Boundary",
        "note": "Số lượng khách là số âm",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_ADD_08",
        "desc": "Kiểm tra nhập Tên phòng có độ dài tối đa biên 255 ký tự.",
        "type": "Boundary",
        "note": "Tên phòng 255 ký tự",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Giao diện",
        "tc_id": "TC_RM_ADD_09",
        "desc": "Hủy bỏ thao tác thêm phòng khi bấm nút đóng Modal ('X') hoặc nút 'Hủy' (Dữ liệu form được xóa sạch).",
        "type": "GUI",
        "note": "Đóng/hủy modal",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 4. CHỈNH SỬA THÔNG TIN PHÒNG (EDIT ROOM)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Chỉnh sửa thông tin phòng",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_EDT_01",
        "desc": "Mở Modal chỉnh sửa phòng, hệ thống tự động tải và điền đầy đủ dữ liệu hiện tại của phòng (Auto-fill data).",
        "type": "Function",
        "note": "Phòng phải tồn tại",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_EDT_02",
        "desc": "Cập nhật thành công thông tin phòng (Đổi tên phòng, đổi giá tiền, cập nhật diện tích, sức chứa và mô tả).",
        "type": "Function",
        "note": "Thông tin cập nhật hợp lệ",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_EDT_03",
        "desc": "Cập nhật thay đổi danh sách Tiện ích & Đặc điểm của phòng (Bỏ chọn tiện ích cũ, tích thêm tiện ích mới).",
        "type": "Function",
        "note": "Thay đổi tiện ích/đặc điểm",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_EDT_04",
        "desc": "Chặn cập nhật phòng khi cố tình xóa rỗng trường 'Tên phòng'.",
        "type": "Function",
        "note": "Xóa rỗng tên phòng",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_EDT_05",
        "desc": "Chặn cập nhật khi sửa Giá phòng thành số âm (< 0) hoặc bằng 0.",
        "type": "Boundary",
        "note": "Giá phòng sửa <= 0",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Giao diện",
        "tc_id": "TC_RM_EDT_06",
        "desc": "Hủy bỏ thao tác sửa phòng khi đóng Modal (Dữ liệu ban đầu của phòng được giữ nguyên vẹn).",
        "type": "GUI",
        "note": "Đóng/hủy modal",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 5. CHUYỂN ĐỔI TRẠNG THÁI PHÒNG (4+1 STATE TRANSITIONS)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Chuyển đổi trạng thái phòng (4+1 States)",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_01",
        "desc": "Chuyển trạng thái phòng từ '1. Phòng Đang Trống' (Available) sang '2. Đang Dọn Dẹp' (Cleaning) bằng Dropdown 1-Click.",
        "type": "Function",
        "note": "Phòng đang trống (status=1)",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_02",
        "desc": "Chuyển trạng thái phòng từ '2. Đang Dọn Dẹp' sang '1. Phòng Đang Trống' (Sau khi nhân viên buồng phòng hoàn tất vệ sinh).",
        "type": "Function",
        "note": "Phòng đang dọn dẹp (status=2)",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_03",
        "desc": "Chuyển trạng thái phòng sang '3. Đang Có Khách' (Occupied) khi khách hoàn tất thủ tục Check-in nhận phòng.",
        "type": "Function",
        "note": "Khách nhận phòng check-in",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_04",
        "desc": "Chuyển trạng thái phòng sang '4. Đã Được Đặt' (Booked) khi có đơn đặt giữ chỗ trước.",
        "type": "Function",
        "note": "Đơn đặt phòng trước",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_05",
        "desc": "Chuyển trạng thái phòng sang '0. Tạm Dừng / Bảo Trì' (Maintenance) để tiến hành sửa chữa nâng cấp trang thiết bị.",
        "type": "Function",
        "note": "Phòng cần bảo trì (status=0)",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_06",
        "desc": "Khôi phục trạng thái phòng từ '0. Tạm Dừng / Bảo Trì' về '1. Phòng Đang Trống' sau khi hoàn tất sửa chữa.",
        "type": "Function",
        "note": "Phòng bảo trì xong",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_STA_07",
        "desc": "Kiểm tra tính nhất quán dữ liệu trạng thái phòng trong CSDL MySQL sau khi thực hiện chuyển đổi trạng thái.",
        "type": "Integration",
        "note": "Kiểm tra CSDL MySQL",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 6. QUẢN LÝ HÌNH THỨC PHÒNG (ROOM IMAGES MANAGEMENT)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Quản lý hình ảnh phòng",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_IMG_01",
        "desc": "Upload hình ảnh mới cho phòng thành công với file ảnh hợp lệ định dạng JPG/PNG dung lượng < 2MB.",
        "type": "Function",
        "note": "File ảnh JPG/PNG < 2MB",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_IMG_02",
        "desc": "Chặn upload file khi chọn tệp sai định dạng (File script .php, .exe, .pdf, .docx, .sh).",
        "type": "Security",
        "note": "File sai định dạng (.php, .exe)",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_IMG_03",
        "desc": "Chặn upload file ảnh khi dung lượng file vượt quá giới hạn 2MB (Kiểm tra giá trị biên dung lượng).",
        "type": "Boundary",
        "note": "File ảnh dung lượng > 2MB",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_IMG_04",
        "desc": "Thiết lập ảnh đại diện (Set Thumbnail) cho phòng thành công.",
        "type": "Function",
        "note": "Ảnh phòng đã upload",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_IMG_05",
        "desc": "Xóa một hình ảnh của phòng thành công và xóa file vật lý tương ứng trong thư mục lưu trữ máy chủ.",
        "type": "Function",
        "note": "Ảnh phòng tồn tại",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 7. QUẢN LÝ TIỆN ÍCH & ĐẶC ĐIỂM (FEATURES & FACILITIES)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Quản lý Tiện ích & Đặc điểm",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FAC_01",
        "desc": "Thêm mới Đặc điểm nổi bật (Feature) thành công.",
        "type": "Function",
        "note": "Tên đặc điểm hợp lệ",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FAC_02",
        "desc": "Thêm mới Tiện ích (Facility) thành công với file Icon định dạng SVG hợp lệ.",
        "type": "Function",
        "note": "File icon SVG hợp lệ",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_FAC_03",
        "desc": "Chặn thêm Tiện ích khi upload file Icon không phải định dạng SVG (Chọn file .png, .jpg).",
        "type": "Function",
        "note": "File icon không phải SVG",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_FAC_04",
        "desc": "Chặn thêm Đặc điểm khi để trống ô Tên đặc điểm.",
        "type": "Function",
        "note": "Để trống tên đặc điểm",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_FAC_05",
        "desc": "Xóa Đặc điểm thành công khi đặc điểm đó chưa được liên kết với bất kỳ phòng nào.",
        "type": "Function",
        "note": "Đặc điểm chưa gán phòng",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_FAC_06",
        "desc": "Chặn xóa Tiện ích / Đặc điểm khi đang được liên kết trong phòng nghỉ (Kiểm tra ràng buộc toàn vẹn dữ liệu).",
        "type": "Function",
        "note": "Tiện ích đang liên kết phòng",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 8. XÓA PHÒNG NGHỈ (REMOVE ROOM)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Xóa phòng nghỉ",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_DEL_01",
        "desc": "Xóa mềm phòng nghỉ thành công (Cập nhật cờ removed = 1, xóa liên kết tiện ích & ảnh).",
        "type": "Function",
        "note": "Phòng chưa có đơn booking",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Giao diện",
        "tc_id": "TC_RM_DEL_02",
        "desc": "Hủy bỏ thao tác xóa phòng khi bấm nút 'Hủy' tại popup hộp thoại xác nhận.",
        "type": "GUI",
        "note": "Hộp thoại xác nhận xóa",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_DEL_03",
        "desc": "Chặn xóa phòng khi phòng đang có đơn đặt hoặc khách đang lưu trú thực tế.",
        "type": "Security",
        "note": "Phòng đang có khách lưu trú",
        "member": "Đào Tấn Phát"
    },

    # -------------------------------------------------------------
    # 9. ĐỒNG BỘ HIỂN THỊ & BẢO MẬT CLIENT (CLIENT SYNC & SECURITY)
    # -------------------------------------------------------------
    {
        "lvl1": "",
        "lvl2": "Đồng bộ hiển thị & Bảo mật Client",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_SEC_01",
        "desc": "Khách hàng truy cập trang chủ (index.php) và danh sách phòng (rooms.php), hệ thống chỉ hiển thị các phòng có trạng thái 'Đang Trống' (status=1) và chưa bị xóa (removed=0).",
        "type": "Function",
        "note": "Khách truy cập rooms.php",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_SEC_02",
        "desc": "Phòng ở trạng thái 'Đang Dọn Dẹp' (status=2) hoặc 'Tạm Dừng / Bảo Trì' (status=0) tự động ẨN hoàn toàn khỏi trang tìm kiếm đặt phòng của khách.",
        "type": "Function",
        "note": "Phòng status=2 hoặc status=0",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Không thành công",
        "tc_id": "TC_RM_SEC_03",
        "desc": "Khách hàng cố tình truy cập trực tiếp đường dẫn đặt phòng qua URL confirm_booking.php?id=X với phòng đang ở trạng thái 'Đang Dọn Dẹp' (status=2) hoặc 'Tạm Dừng' (status=0).",
        "type": "Security",
        "note": "Truy cập URL confirm_booking",
        "member": "Đào Tấn Phát"
    },
    {
        "lvl1": "",
        "lvl2": "",
        "lvl3": "Thành công",
        "tc_id": "TC_RM_SEC_04",
        "desc": "Kiểm tra giá tiền phòng hiển thị đồng nhất tuyệt đối giữa màn hình Admin và giao diện khách hàng (Không bị sai lệch tỷ giá).",
        "type": "Integration",
        "note": "Giá phòng VNĐ",
        "member": "Đào Tấn Phát"
    }
]

def build_testdesign_sheet(ws):
    font_name = "Arial"
    
    # Styles
    header_font = Font(name=font_name, size=10, bold=True, color="000000")
    header_member_font = Font(name="Times New Roman", size=12, bold=True, color="000000")
    
    cell_font = Font(name=font_name, size=9.5, color="000000")
    cell_bold_font = Font(name=font_name, size=9.5, bold=True, color="000000")
    tc_id_font = Font(name=font_name, size=9.5, bold=True, color="0B5394")
    
    # Fills matching Template exactly
    fill_header = PatternFill(start_color="00B0F0", end_color="00B0F0", fill_type="solid") # Cyan Blue
    fill_header_member = PatternFill(start_color="6D9EEB", end_color="6D9EEB", fill_type="solid") # Soft Blue
    
    fill_lvl1 = PatternFill(start_color="F4CCCC", end_color="F4CCCC", fill_type="solid") # Light Peach/Red
    fill_lvl2 = PatternFill(start_color="CFE2F3", end_color="CFE2F3", fill_type="solid") # Light Blue
    fill_success = PatternFill(start_color="FFD966", end_color="FFD966", fill_type="solid") # Soft Yellow
    fill_fail = PatternFill(start_color="CCCCCC", end_color="CCCCCC", fill_type="solid") # Soft Gray
    fill_gui = PatternFill(start_color="D9EAD3", end_color="D9EAD3", fill_type="solid") # Soft Green
    
    # Borders
    thin_side = Side(border_style="thin", color="A6B9D0")
    cell_border = Border(left=thin_side, right=thin_side, top=thin_side, bottom=thin_side)
    
    # Alignments
    align_center = Alignment(horizontal="center", vertical="center", wrap_text=True)
    align_left = Alignment(horizontal="left", vertical="center", wrap_text=True)
    
    # Headers
    headers = [
        "Requirement Level 1\nYêu cầu cấp 1",
        "Requirement Level 2\nYêu cầu cấp 2",
        "Requirement Level 3\nYêu cầu cấp 3",
        "TC_ID\nMã TC",
        "Test case description\nMô tả TC",
        "Test Type\nKiểu TC",
        "Note",
        "Thành viên"
    ]
    
    # Column widths - tinh chỉnh gọn gàng, vừa mắt
    col_widths = {
        'A': 25,
        'B': 28,
        'C': 18,
        'D': 15,
        'E': 55,
        'F': 14,
        'G': 28, # Note gọn gàng vừa vặn
        'H': 16
    }
    for col, w in col_widths.items():
        ws.column_dimensions[col].width = w
        
    ws.row_dimensions[1].height = 32
    for col_idx, h in enumerate(headers, 1):
        cell = ws.cell(row=1, column=col_idx, value=h)
        cell.font = header_member_font if col_idx == 8 else header_font
        cell.fill = fill_header_member if col_idx == 8 else fill_header
        cell.alignment = align_center
        cell.border = cell_border
        
    # Populate data
    current_row = 2
    for item in TEST_CASES_DATA:
        ws.row_dimensions[current_row].height = 26 # Gọn gàng, thoáng, không bị cao lêu nghêu
        
        # Col 1: Level 1
        c1 = ws.cell(row=current_row, column=1, value=item["lvl1"])
        c1.font = cell_bold_font if item["lvl1"] else cell_font
        c1.fill = fill_lvl1 if item["lvl1"] else PatternFill(fill_type=None)
        c1.alignment = align_center if item["lvl1"] else align_left
        c1.border = cell_border
        
        # Col 2: Level 2
        c2 = ws.cell(row=current_row, column=2, value=item["lvl2"])
        c2.font = cell_bold_font if item["lvl2"] else cell_font
        c2.fill = fill_lvl2 if item["lvl2"] else PatternFill(fill_type=None)
        c2.alignment = align_left
        c2.border = cell_border
        
        # Col 3: Level 3
        c3 = ws.cell(row=current_row, column=3, value=item["lvl3"])
        c3.font = cell_bold_font
        if item["lvl3"] == "Thành công":
            c3.fill = fill_success
        elif item["lvl3"] == "Không thành công":
            c3.fill = fill_fail
        else: # Giao diện
            c3.fill = fill_gui
        c3.alignment = align_center
        c3.border = cell_border
        
        # Col 4: TC_ID
        c4 = ws.cell(row=current_row, column=4, value=item["tc_id"])
        c4.font = tc_id_font
        c4.alignment = align_center
        c4.border = cell_border
        
        # Col 5: Mô tả TC
        c5 = ws.cell(row=current_row, column=5, value=item["desc"])
        c5.font = cell_font
        c5.alignment = align_left
        c5.border = cell_border
        
        # Col 6: Test Type
        c6 = ws.cell(row=current_row, column=6, value=item["type"])
        c6.font = cell_font
        c6.alignment = align_center
        c6.border = cell_border
        
        # Col 7: Note (TỐI GIẢN)
        c7 = ws.cell(row=current_row, column=7, value=item["note"])
        c7.font = cell_font
        c7.alignment = align_left
        c7.border = cell_border
        
        # Col 8: Thành viên
        c8 = ws.cell(row=current_row, column=8, value=item["member"])
        c8.font = cell_font
        c8.alignment = align_center
        c8.border = cell_border
        
        current_row += 1

    # Freeze panes
    ws.freeze_panes = "A2"
    ws.views.sheetView[0].showGridLines = True

def main():
    # 1. Cập nhật file standalone riêng cho Phát: Bang_Test_Design_Quan_Ly_Phong_Phat.xlsx
    path_standalone = r"e:\School\KiemThu\Bang_Test_Design_Quan_Ly_Phong_Phat.xlsx"
    wb_standalone = openpyxl.Workbook()
    ws_standalone = wb_standalone.active
    ws_standalone.title = "Testdesign"
    build_testdesign_sheet(ws_standalone)
    wb_standalone.save(path_standalone)
    print(f"[+] Da cap nhat Note toi gian: {path_standalone}")

    # 2. Cập nhật file 2. Template_Testdesign.xlsx
    path_template = r"e:\School\KiemThu\2. Template_Testdesign.xlsx"
    wb_standalone.save(path_template)
    print(f"[+] Da cap nhat Note toi gian: {path_template}")

    # 3. Cập nhật file nhóm: Bang_Test_Case_Hoan_Chinh_Hotel_Booking.xlsx
    path_group = r"e:\School\KiemThu\Bang_Test_Case_Hoan_Chinh_Hotel_Booking.xlsx"
    wb_group = openpyxl.load_workbook(path_group)
    sheet_name = "3. Test Design (Phát)"
    if sheet_name in wb_group.sheetnames:
        del wb_group[sheet_name]
    
    ws_group_phat = wb_group.create_sheet(title=sheet_name, index=3)
    build_testdesign_sheet(ws_group_phat)
    wb_group.save(path_group)
    print(f"[+] Da dong bo vao file nhom: {path_group}")

if __name__ == "__main__":
    main()
