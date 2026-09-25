<?php 

  require('inc/essentials.php');
  require('inc/db_config.php');
  require('inc/mpdf/vendor/autoload.php');

  date_default_timezone_set("Asia/Ho_Chi_Minh");

  adminLogin();

  if(isset($_GET['gen_pdf']) && isset($_GET['id']))
  {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*, uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE (bo.booking_status='booked' 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con,$query);
    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
      header('location: dashboard.php');
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("d/m/Y H:i", strtotime($data['datentime']));
    $checkin = date("d/m/Y", strtotime($data['check_in']));
    $checkout = date("d/m/Y", strtotime($data['check_out']));

    // Tính số đêm
    $checkin_d = new DateTime($data['check_in']);
    $checkout_d = new DateTime($data['check_out']);
    $days = $checkin_d->diff($checkout_d)->days;
    if($days <= 0) $days = 1;

    $price_formatted = number_format($data['price'] * 1000, 0, ',', '.');
    $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');
    $trans_amt_formatted = number_format($data['trans_amt'] * 1000, 0, ',', '.');

    $status_title = "ĐÃ THANH TOÁN THÀNH CÔNG";
    $status_color = "#198754";

    if($data['booking_status']=='cancelled'){
      $status_title = "ĐÃ HỦY ĐƠN ĐẶT PHÒNG";
      $status_color = "#dc3545";
    }
    else if($data['booking_status']=='payment failed'){
      $status_title = "THANH TOÁN THẤT BẠI";
      $status_color = "#6c757d";
    }

    $trans_id_display = !empty($data['trans_id']) ? $data['trans_id'] : 'TẠI QUẦY KHÁCH SẠN';

    $table_data = "
    <style>
      body { font-family: 'dejavusans', sans-serif; color: #333; }
      .header-title { text-align: center; color: #03989e; margin-bottom: 5px; text-transform: uppercase; }
      .subtitle { text-align: center; font-size: 13px; color: #666; margin-bottom: 25px; }
      .badge-status { display: inline-block; padding: 6px 14px; background-color: $status_color; color: #fff; font-weight: bold; border-radius: 4px; font-size: 12px; }
      .section-title { font-size: 14px; font-weight: bold; color: #03989e; border-bottom: 2px solid #03989e; padding-bottom: 5px; margin-top: 20px; margin-bottom: 12px; }
      table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
      table.info-table td { padding: 8px 10px; font-size: 12px; border: 1px solid #e0e0e0; }
      table.info-table td.bg-lbl { background-color: #f8f9fa; font-weight: bold; width: 30%; color: #495057; }
      .total-box { background-color: #e6f7f7; border: 1px solid #b2e5e7; padding: 12px; text-align: right; border-radius: 4px; margin-top: 15px; }
      .total-amount { font-size: 18px; font-weight: bold; color: #03989e; }
      .footer-note { margin-top: 30px; text-align: center; font-size: 11px; color: #888; border-top: 1px dashed #ccc; padding-top: 15px; }
      .stamp { text-align: right; margin-top: 20px; font-weight: bold; color: #198754; font-size: 13px; }
    </style>

    <h2 class='header-title'>HÓA ĐƠN ĐIỆN TỬ - XÁC NHẬN ĐẶT PHÒNG</h2>
    <div class='subtitle'>HỆ THỐNG ĐẶT PHÒNG KHÁCH SẠN GET HOTELS</div>

    <table style='margin-bottom: 20px;'>
      <tr>
        <td style='font-size: 12px;'>
          <b>Mã đơn hàng:</b> {$data['order_id']}<br>
          <b>Mã giao dịch:</b> {$trans_id_display}<br>
          <b>Ngày tạo đơn:</b> {$date}
        </td>
        <td style='text-align: right;'>
          <span class='badge-status'>{$status_title}</span>
        </td>
      </tr>
    </table>

    <div class='section-title'>THÔNG TIN KHÁCH HÀNG</div>
    <table class='info-table'>
      <tr>
        <td class='bg-lbl'>Họ và tên khách hàng</td>
        <td>{$data['user_name']}</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Số điện thoại</td>
        <td>{$data['phonenum']}</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Địa chỉ Email</td>
        <td>{$data['email']}</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Địa chỉ liên hệ</td>
        <td>{$data['address']}</td>
      </tr>
    </table>

    <div class='section-title'>CHI TIẾT ĐẶT PHÒNG & THANH TOÁN</div>
    <table class='info-table'>
      <tr>
        <td class='bg-lbl'>Tên loại phòng</td>
        <td><b>{$data['room_name']}</b></td>
      </tr>
      <tr>
        <td class='bg-lbl'>Đơn giá phòng</td>
        <td>{$price_formatted} VNĐ / đêm</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Ngày nhận phòng</td>
        <td>{$checkin}</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Ngày trả phòng</td>
        <td>{$checkout}</td>
      </tr>
      <tr>
        <td class='bg-lbl'>Số đêm lưu trú</td>
        <td>{$days} đêm</td>
      </tr>
    </table>

    <div class='total-box'>
      <span>TỔNG TIỀN THANH TOÁN: </span>
      <span class='total-amount'>{$total_formatted} VNĐ</span>
    </div>

    <div class='stamp'>
      [XÁC NHẬN BỞI QUẢN TRỊ VIÊN KHÁCH SẠN]
    </div>

    <div class='footer-note'>
      Cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của Get Hotels!<br>
      Mọi thắc mắc vui lòng liên hệ hotline bộ phận hỗ trợ khách hàng 24/7.
    </div>
    ";

    $mpdf = new \Mpdf\Mpdf([
      'mode' => 'utf-8',
      'format' => 'A4',
      'margin_left' => 15,
      'margin_right' => 15,
      'margin_top' => 15,
      'margin_bottom' => 15
    ]);
    $mpdf->WriteHTML($table_data);
    $mpdf->Output('HoaDon_Admin_'.$data['order_id'].'.pdf','D');

  }
  else{
    header('location: dashboard.php');
  }
  
?>