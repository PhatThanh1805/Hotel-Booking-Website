<?php 

  require_once('admin/inc/db_config.php');
  require_once('admin/inc/essentials.php');

  date_default_timezone_set("Asia/Ho_Chi_Minh");

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  // Nếu không có thông tin phòng trong session, chuyển hướng về trang phòng
  if(!isset($_SESSION['room'])){
    redirect('rooms.php');
  }

  // Xử lý khi khách bấm Xác nhận thanh toán (hoàn tất đơn hàng)
  if(isset($_POST['complete_payment']))
  {
    $frm_data = filteration($_POST);

    $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,999999);
    $CUST_ID = $_SESSION['uId'];
    $TXN_AMOUNT = $_SESSION['room']['payment'];
    $TXN_ID = 'TXN_'.random_int(10000000,99999999);
    $payment_method = isset($frm_data['payment_method']) ? $frm_data['payment_method'] : 'Chuyển khoản VietQR/MoMo';

    // Tạo đơn đặt phòng với trạng thái 'booked' và 'TXN_SUCCESS'
    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`, `trans_id`, `trans_amt`, `booking_status`, `trans_status`, `trans_resp_msg`) VALUES (?,?,?,?,?,?,?,'booked','TXN_SUCCESS',?)";

    insert($query1, [
      $CUST_ID, 
      $_SESSION['room']['id'], 
      $frm_data['checkin'], 
      $frm_data['checkout'], 
      $ORDER_ID, 
      $TXN_ID, 
      $TXN_AMOUNT,
      "Thanh toán thành công qua $payment_method"
    ], 'isssssis');
    
    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";

    insert($query2, [
      $booking_id, 
      $_SESSION['room']['name'], 
      $_SESSION['room']['price'], 
      $TXN_AMOUNT, 
      $frm_data['name'], 
      $frm_data['phonenum'], 
      $frm_data['address']
    ], 'issssss');

    // Xóa session đặt phòng tạm
    unset($_SESSION['room']);

    redirect('pay_status.php?order='.$ORDER_ID);
    exit;
  }

  // Lấy dữ liệu gửi từ confirm_booking.php
  if(isset($_POST['pay_now']))
  {
    $booking_data = filteration($_POST);
    $total_pay_formatted = number_format($_SESSION['room']['payment'] * 1000, 0, ',', '.');
    $price_formatted = number_format($_SESSION['room']['price'] * 1000, 0, ',', '.');
  }
  else {
    redirect('rooms.php');
  }

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - CỔNG THANH TOÁN TRỰC TUYẾN</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container my-5">
    <div class="row justify-content-center">

      <div class="col-12 text-center mb-4">
        <h2 class="fw-bold text-dark">CỔNG THANH TOÁN TRỰC TUYẾN GET HOTELS</h2>
        <p class="text-secondary">Vui lòng chọn phương thức thanh toán để hoàn tất đơn đặt phòng</p>
      </div>

      <!-- Tóm tắt đơn hàng -->
      <div class="col-lg-5 col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 pop">
          <div class="card-body">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
              <i class="bi bi-receipt text-teal me-2"></i>TÓM TẮT ĐƠN HÀNG
            </h5>

            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-secondary fw-medium">Loại phòng:</span>
              <span class="fw-bold text-dark"><?php echo $_SESSION['room']['name'] ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-secondary fw-medium">Giá phòng/đêm:</span>
              <span class="fw-semibold"><?php echo $price_formatted ?> VNĐ</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-secondary fw-medium">Khách hàng:</span>
              <span class="fw-semibold"><?php echo $booking_data['name'] ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-secondary fw-medium">Số điện thoại:</span>
              <span class="fw-semibold"><?php echo $booking_data['phonenum'] ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text-secondary fw-medium">Ngày nhận phòng:</span>
              <span class="fw-semibold text-primary"><?php echo date("d/m/Y", strtotime($booking_data['checkin'])) ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="text-secondary fw-medium">Ngày trả phòng:</span>
              <span class="fw-semibold text-danger"><?php echo date("d/m/Y", strtotime($booking_data['checkout'])) ?></span>
            </div>

            <hr class="my-3">

            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light">
              <span class="fw-bold text-dark fs-6">TỔNG TIỀN THANH TOÁN:</span>
              <span class="fs-4 text-teal fw-bold"><?php echo $total_pay_formatted ?> VNĐ</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Chọn phương thức thanh toán -->
      <div class="col-lg-7 col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-3">
          <div class="card-body">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
              <i class="bi bi-wallet2 text-teal me-2"></i>CHỌN PHƯƠNG THỨC THANH TOÁN
            </h5>

            <form method="POST" action="pay_now.php">
              <!-- Truyền ẩn các tham số -->
              <input type="hidden" name="name" value="<?php echo $booking_data['name'] ?>">
              <input type="hidden" name="phonenum" value="<?php echo $booking_data['phonenum'] ?>">
              <input type="hidden" name="address" value="<?php echo $booking_data['address'] ?>">
              <input type="hidden" name="checkin" value="<?php echo $booking_data['checkin'] ?>">
              <input type="hidden" name="checkout" value="<?php echo $booking_data['checkout'] ?>">

              <div class="row g-3 mb-4">
                
                <div class="col-md-6">
                  <input type="radio" class="btn-check" name="payment_method" id="pay_vietqr" value="Chuyển khoản VietQR" checked>
                  <label class="btn btn-outline-teal w-100 p-3 rounded-3 text-start h-100 d-flex align-items-center" for="pay_vietqr">
                    <i class="bi bi-qr-code-scan fs-2 me-3 text-teal"></i>
                    <div>
                      <div class="fw-bold">Chuyển khoản QR (VietQR)</div>
                      <small class="text-muted">Quét mã ngân hàng 24/7</small>
                    </div>
                  </label>
                </div>

                <div class="col-md-6">
                  <input type="radio" class="btn-check" name="payment_method" id="pay_momo" value="Ví điện tử MoMo">
                  <label class="btn btn-outline-teal w-100 p-3 rounded-3 text-start h-100 d-flex align-items-center" for="pay_momo">
                    <i class="bi bi-phone-vibrate fs-2 me-3 text-danger"></i>
                    <div>
                      <div class="fw-bold">Ví Điện Tử MoMo</div>
                      <small class="text-muted">Thanh toán siêu tốc 1 chạm</small>
                    </div>
                  </label>
                </div>

                <div class="col-md-6">
                  <input type="radio" class="btn-check" name="payment_method" id="pay_vnpay" value="Cổng VNPay">
                  <label class="btn btn-outline-teal w-100 p-3 rounded-3 text-start h-100 d-flex align-items-center" for="pay_vnpay">
                    <i class="bi bi-credit-card-2-front fs-2 me-3 text-primary"></i>
                    <div>
                      <div class="fw-bold">Cổng VNPay / ATM</div>
                      <small class="text-muted">Thẻ ATM nội địa & Visa/Mastercard</small>
                    </div>
                  </label>
                </div>

                <div class="col-md-6">
                  <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="Tiền mặt tại khách sạn">
                  <label class="btn btn-outline-teal w-100 p-3 rounded-3 text-start h-100 d-flex align-items-center" for="pay_cash">
                    <i class="bi bi-cash-coin fs-2 me-3 text-success"></i>
                    <div>
                      <div class="fw-bold">Thanh toán khi nhận phòng</div>
                      <small class="text-muted">Giữ phòng & trả tiền tại quầy</small>
                    </div>
                  </label>
                </div>

              </div>

              <!-- Mã QR Demo -->
              <div class="p-3 bg-light rounded-4 text-center mb-4">
                <div class="mb-2 fw-semibold text-dark"><i class="bi bi-qr-code me-1 text-teal"></i> Quét mã QR bên dưới bằng ứng dụng ngân hàng hoặc MoMo:</div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=GETHOTELS_BOOKING_<?php echo $_SESSION['room']['payment'] ?>" class="img-fluid border rounded-3 p-2 bg-white shadow-sm" alt="Mã QR Thanh Toán">
                <div class="small text-muted mt-2">Tự động nhận diện giao dịch & cấp Hóa đơn điện tử ngay tức thì</div>
              </div>

              <button type="submit" name="complete_payment" class="btn btn-dark custom-bg text-white shadow-none w-100 py-3 rounded-3 fs-5 fw-bold pop">
                <i class="bi bi-shield-check me-2"></i> XÁC NHẬN THANH TOÁN THÀNH CÔNG
              </button>
            </form>

          </div>
        </div>
      </div>

    </div>
  </div>

  <?php require('inc/footer.php'); ?>

</body>
</html>