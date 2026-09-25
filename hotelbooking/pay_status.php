<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - TRẠNG THÁI THANH TOÁN & HÓA ĐƠN</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container my-5">
    <div class="row justify-content-center">

      <div class="col-12 my-3 px-4 text-center">
        <h2 class="fw-bold text-dark">TRẠNG THÁI THANH TOÁN & HÓA ĐƠN ĐIỆN TỬ</h2>
        <p class="text-secondary">Chi tiết xác nhận đơn đặt phòng của quý khách</p>
      </div>

      <?php 

        $frm_data = filteration($_GET);

        if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
          redirect('index.php');
        }

        $booking_q = "SELECT bo.*, bd.* FROM `booking_order` bo 
          INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
          WHERE bo.order_id=? AND bo.user_id=? AND bo.booking_status!=?";
      
        $booking_res = select($booking_q,[$frm_data['order'],$_SESSION['uId'],'pending'],'sis');

        if(mysqli_num_rows($booking_res)==0){
          redirect('index.php');
        }

        $booking_fetch = mysqli_fetch_assoc($booking_res);

        $date = date("d/m/Y H:i", strtotime($booking_fetch['datentime']));
        $checkin = date("d/m/Y", strtotime($booking_fetch['check_in']));
        $checkout = date("d/m/Y", strtotime($booking_fetch['check_out']));
        $total_formatted = number_format($booking_fetch['total_pay'] * 1000, 0, ',', '.');
        $price_formatted = number_format($booking_fetch['price'] * 1000, 0, ',', '.');

        if($booking_fetch['trans_status']=="TXN_SUCCESS")
        {
          echo<<<data
            <div class="col-lg-8 col-md-12 px-4 mb-5">
              <div class="card border-0 shadow-sm rounded-4 overflow-hidden pop">
                
                <div class="bg-teal text-white p-4 text-center">
                  <i class="bi bi-check-circle-fill display-3 d-block mb-2 text-warning"></i>
                  <h3 class="fw-bold m-0">THANH TOÁN THÀNH CÔNG!</h3>
                  <p class="m-0 mt-1 opacity-75">Cảm ơn bạn đã đặt phòng tại Khách sạn Get Hotels</p>
                </div>

                <div class="card-body p-4 p-md-5">
                  <div class="alert alert-success border-0 rounded-3 mb-4 p-3 small">
                    <i class="bi bi-shield-check me-1"></i> Hóa đơn điện tử hợp lệ đã được tạo tự động cho đơn hàng này.
                  </div>

                  <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="bi bi-file-earmark-text text-teal me-2"></i>THÔNG TIN HÓA ĐƠN ĐIỆN TỬ
                  </h5>

                  <div class="row g-3 mb-4">
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Mã đơn hàng:</span>
                      <code class="fs-6 text-dark fw-bold">$booking_fetch[order_id]</code>
                    </div>
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Mã giao dịch:</span>
                      <code class="fs-6 text-dark fw-bold">$booking_fetch[trans_id]</code>
                    </div>
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Khách hàng đặt:</span>
                      <strong class="text-dark">$booking_fetch[user_name] ($booking_fetch[phonenum])</strong>
                    </div>
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Thời gian khởi tạo:</span>
                      <strong class="text-dark">$date</strong>
                    </div>
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Tên loại phòng:</span>
                      <strong class="text-teal fs-6">$booking_fetch[room_name]</strong>
                    </div>
                    <div class="col-md-6">
                      <span class="text-secondary d-block small">Thời gian lưu trú:</span>
                      <strong class="text-dark">$checkin đến $checkout</strong>
                    </div>
                  </div>

                  <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light mb-4">
                    <span class="fw-bold text-dark fs-6">TỔNG TIỀN ĐÃ THANH TOÁN:</span>
                    <span class="fs-4 text-teal fw-bold">$total_formatted VNĐ</span>
                  </div>

                  <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="generate_pdf.php?gen_pdf&id=$booking_fetch[booking_id]" class="btn btn-dark custom-bg text-white shadow-none rounded-3 px-4 py-2 text-decoration-none fw-semibold">
                      <i class="bi bi-file-earmark-pdf-fill me-1"></i> TẢI HÓA ĐƠN ĐIỆN TỬ (PDF)
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-dark shadow-none rounded-3 px-4 py-2 fw-semibold">
                      <i class="bi bi-printer-fill me-1"></i> In Hóa Đơn Trực Tiếp
                    </button>
                    <a href="bookings.php" class="btn btn-light shadow-none rounded-3 px-4 py-2 text-decoration-none fw-semibold">
                      <i class="bi bi-journal-check me-1"></i> Danh Sách Đơn Đặt Phòng
                    </a>
                  </div>

                </div>

              </div>
            </div>
          data;
        }
        else
        {
          echo<<<data
            <div class="col-lg-8 col-md-12 px-4 mb-5">
              <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-danger text-white p-4 text-center">
                  <i class="bi bi-x-circle-fill display-3 d-block mb-2"></i>
                  <h3 class="fw-bold m-0">THANH TOÁN THẤT BẠI</h3>
                  <p class="m-0 mt-1 opacity-75">Không thể xử lý giao dịch đặt phòng</p>
                </div>
                <div class="card-body p-4 p-md-5 text-center">
                  <p class="fw-semibold text-danger fs-5 mb-4">Lý do: $booking_fetch[trans_resp_msg]</p>
                  <a href='rooms.php' class='btn btn-dark custom-bg text-white shadow-none rounded-3 px-4 py-2 text-decoration-none fw-semibold me-2'>
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Thử đặt lại phòng
                  </a>
                  <a href='bookings.php' class='btn btn-outline-secondary shadow-none rounded-3 px-4 py-2 text-decoration-none fw-semibold'>
                    <i class="bi bi-journal-check me-1"></i> Xem danh sách Đơn đặt phòng
                  </a>
                </div>
              </div>
            </div>
          data;
        }

      ?>

    </div>
  </div>


  <?php require('inc/footer.php'); ?>

</body>
</html>