<?php 

  require('inc/essentials.php');
  require('inc/db_config.php');
  require('inc/mpdf/vendor/autoload.php');

  date_default_timezone_set("Asia/Ho_Chi_Minh");
  adminLogin();

  if(isset($_GET['booking_id']))
  {
    $booking_id = intval($_GET['booking_id']);

    // Get booking order & details
    $b_query = "SELECT bo.*, bd.* FROM `booking_order` bo 
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id 
      WHERE bo.booking_id = ?";
    $b_res = select($b_query, [$booking_id], 'i');

    if(mysqli_num_rows($b_res) == 0){
      header('location: new_bookings.php');
      exit;
    }

    $b_data = mysqli_fetch_assoc($b_res);
    $checkin = date("d/m/Y", strtotime($b_data['check_in']));
    $checkout = date("d/m/Y", strtotime($b_data['check_out']));
    $created_at = date("d/m/Y H:i", strtotime($b_data['datentime']));

    // Get guests
    $g_res = select("SELECT * FROM `booking_guests` WHERE `booking_id`=? ORDER BY `is_leader` DESC, `id` ASC", [$booking_id], 'i');

    $guest_rows = "";
    $i = 1;

    if(mysqli_num_rows($g_res) == 0){
      // Fallback to primary customer in booking details if no sub-guests are registered
      $guest_rows = "
        <tr>
          <td style='text-align:center;'>1</td>
          <td><b>{$b_data['user_name']}</b> <br><small style='color:#03989e;'>(Trưởng đoàn)</small></td>
          <td>-</td>
          <td>{$b_data['phonenum']}</td>
          <td>Việt Nam</td>
          <td>{$b_data['address']}</td>
          <td>{$checkin} - {$checkout}</td>
        </tr>
      ";
    } else {
      while($g = mysqli_fetch_assoc($g_res)) {
        $dob = !empty($g['dob']) ? date("d/m/Y", strtotime($g['dob'])) : '-';
        $role = ($g['is_leader'] == 1) ? "<small style='color:#03989e;font-weight:bold;'>(Trưởng đoàn)</small>" : "";
        $guest_rows .= "
          <tr>
            <td style='text-align:center;'>$i</td>
            <td><b>{$g['name']}</b> $role</td>
            <td><code>{$g['id_card']}</code></td>
            <td>{$dob} / {$g['gender']}</td>
            <td>{$g['nationality']}</td>
            <td>{$g['address']}</td>
            <td>{$checkin} - {$checkout}</td>
          </tr>
        ";
        $i++;
      }
    }

    $html = "
    <style>
      body { font-family: 'dejavusans', sans-serif; color: #222; font-size: 11px; }
      .header-top { width: 100%; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; }
      .header-title { text-align: center; font-size: 16px; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 4px; }
      .header-sub { text-align: center; font-size: 11px; color: #444; font-style: italic; margin-bottom: 15px; }
      .info-box { background-color: #f5f5f5; border: 1px solid #ddd; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
      table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
      table.data-table th { background-color: #03989e; color: #ffffff; padding: 8px 5px; font-size: 10px; text-transform: uppercase; border: 1px solid #027a7f; text-align: center; }
      table.data-table td { padding: 7px 6px; font-size: 10px; border: 1px solid #ccc; vertical-align: middle; }
      .sign-section { margin-top: 30px; width: 100%; }
      .sign-box { float: right; width: 40%; text-align: center; font-weight: bold; }
    </style>

    <div class='header-top'>
      <table style='width:100%;'>
        <tr>
          <td style='width:50%; font-weight:bold;'>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM<br><span style='font-weight:normal; font-style:italic;'>Độc lập - Tự do - Hạnh phúc</span></td>
          <td style='width:50%; text-align:right;'><b>CƠ SỞ LƯU TRÚ GET HOTELS</b><br>Mẫu báo cáo tạm trú Công an</td>
        </tr>
      </table>
    </div>

    <h2 class='header-title'>PHIẾU KHAI BÁO THÔNG TIN LƯU TRÚ TẠM TRÚ</h2>
    <div class='header-sub'>(Gửi Cơ quan Công an Quản lý Tạm trú địa phương)</div>

    <div class='info-box'>
      <table style='width:100%;'>
        <tr>
          <td><b>Mã đơn hàng:</b> <code style='font-size:12px;'>{$b_data['order_id']}</code></td>
          <td><b>Số phòng xếp:</b> <b style='color:#03989e; font-size:12px;'>{$b_data['room_no']}</b> ({$b_data['room_name']})</td>
        </tr>
        <tr>
          <td><b>Ngày nhận phòng:</b> {$checkin}</td>
          <td><b>Ngày trả phòng dự kiến:</b> {$checkout}</td>
        </tr>
        <tr>
          <td><b>Người đại diện đặt:</b> {$b_data['user_name']} ({$b_data['phonenum']})</td>
          <td><b>Ngày lập báo cáo:</b> ".date("d/m/Y H:i")."</td>
        </tr>
      </table>
    </div>

    <h4 style='margin-bottom:5px; color:#03989e;'>DANH SÁCH THÀNH VIÊN ĐOÀN LƯU TRÚ KHAI BÁO:</h4>
    <table class='data-table'>
      <thead>
        <tr>
          <th style='width:5%;'>STT</th>
          <th style='width:22%;'>Họ và Tên</th>
          <th style='width:18%;'>Số CCCD / CMND / HC</th>
          <th style='width:15%;'>Ngày sinh / GT</th>
          <th style='width:12%;'>Quốc tịch</th>
          <th style='width:18%;'>Địa chỉ thường trú</th>
          <th style='width:10%;'>Thời gian</th>
        </tr>
      </thead>
      <tbody>
        {$guest_rows}
      </tbody>
    </table>

    <div class='sign-section'>
      <div class='sign-box'>
        <p>......, Ngày ".date("d")." tháng ".date("m")." năm ".date("Y")."</p>
        <p>ĐẠI DIỆN KHÁCH SẠN KHAI BÁO</p>
        <br><br><br>
        <p style='color:#03989e;'>(Đã ký & Đóng dấu xác nhận)</p>
      </div>
    </div>
    ";

    $mpdf = new \Mpdf\Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4-L', // Landscape format for tables
      'margin_left' => 12,
      'margin_right' => 12,
      'margin_top' => 12,
      'margin_bottom' => 12
    ]);

    $mpdf->WriteHTML($html);
    $mpdf->Output('KhaiBaoTamTru_ORD_'.$b_data['order_id'].'.pdf', 'D');
  }
  else {
    header('location: new_bookings.php');
  }

?>
