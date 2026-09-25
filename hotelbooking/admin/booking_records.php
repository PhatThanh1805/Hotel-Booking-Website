<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản Trị Khách Sạn - Lịch Sử Đặt Phòng</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-clock-history me-2 text-info"></i>LỊCH SỬ ĐẶT PHÒNG</h3>

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-journal-text me-1 text-teal"></i>Tất Cả Đơn Đặt Phòng Trong Hệ Thống</h5>
              <input type="text" id="search_input" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 rounded-3 py-2" placeholder="Tìm tên, mã đơn, SĐT...">
            </div>

            <div class="table-responsive">
              <table class="table table-hover border align-middle text-center" style="min-width: 1200px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">STT</th>
                    <th scope="col">Thông Tin Khách Hàng</th>
                    <th scope="col">Thông Tin Phòng</th>
                    <th scope="col">Chi Tiết Đặt Phòng & Giá</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="table-data">                 
                </tbody>
              </table>
            </div>

            <nav>
              <ul class="pagination mt-3 justify-content-center" id="table-pagination">
              </ul>
            </nav>

          </div>
        </div>

      </div>
    </div>
  </div>

  <?php require('inc/guest_modal.php'); ?>

  <?php require('inc/scripts.php'); ?>

  <script src="scripts/booking_records.js"></script>

</body>
</html>