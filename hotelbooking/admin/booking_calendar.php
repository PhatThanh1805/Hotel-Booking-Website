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
  <title>Quản Trị Khách Sạn - Lịch Công Suất Phòng</title>
  <?php require('inc/links.php'); ?>
  <style>
    .cursor-pointer { cursor: pointer; }
    .calendar-day-card { transition: all 0.2s ease-in-out; }
    .calendar-day-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important; }
    .btn-teal { background-color: #03989e; color: #fff; }
    .btn-teal:hover { background-color: #027a7f; color: #fff; }
    .btn-outline-teal { border-color: #03989e; color: #03989e; }
    .btn-outline-teal:hover { background-color: #03989e; color: #fff; }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h3 class="fw-bold text-dark mb-0"><i class="bi bi-calendar-week me-2 text-teal"></i>LỊCH TRẠNG THÁI & CÔNG SUẤT PHÒNG</h3>
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-dark shadow-none rounded-3 px-3 py-2" onclick="change_month(-1)"><i class="bi bi-chevron-left me-1"></i>Tháng trước</button>
            <select id="select_month" onchange="load_calendar()" class="form-select shadow-none rounded-3 py-2 fw-semibold w-auto">
              <?php
                for($m=1;$m<=12;$m++){
                  $sel = ($m == date('n')) ? "selected" : "";
                  echo "<option value='$m' $sel>Tháng $m</option>";
                }
              ?>
            </select>
            <select id="select_year" onchange="load_calendar()" class="form-select shadow-none rounded-3 py-2 fw-semibold w-auto">
              <?php
                $cy = date('Y');
                for($y=$cy-1;$y<=$cy+2;$y++){
                  $sel = ($y == $cy) ? "selected" : "";
                  echo "<option value='$y' $sel>Năm $y</option>";
                }
              ?>
            </select>
            <button class="btn btn-dark shadow-none rounded-3 px-3 py-2" onclick="change_month(1)">Tháng sau<i class="bi bi-chevron-right ms-1"></i></button>
          </div>
        </div>

        <!-- Metric Counter Cards -->
        <div class="row mb-4 g-3">
          <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white pop border-start border-4 border-teal">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h6 class="text-secondary fw-semibold mb-1">TỔNG SỐ PHÒNG ĐANG MỞ</h6>
                  <h3 class="fw-bold text-teal mb-0" id="stat_total_rooms">0</h3>
                </div>
                <div class="bg-teal bg-opacity-10 p-3 rounded-circle text-teal">
                  <i class="bi bi-door-open-fill fs-2"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white pop border-start border-4 border-warning">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h6 class="text-secondary fw-semibold mb-1">TỔNG ĐÊM ĐÃ ĐẶT TRONG THÁNG</h6>
                  <h3 class="fw-bold text-warning mb-0" id="stat_booked_nights">0</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                  <i class="bi bi-journal-bookmark-fill fs-2"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white pop border-start border-4 border-success">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h6 class="text-secondary fw-semibold mb-1">TỶ LỆ CÔNG SUẤT LẤP ĐẦY TRUNG BÌNH</h6>
                  <h3 class="fw-bold text-success mb-0" id="stat_occ_rate">0%</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                  <i class="bi bi-pie-chart-fill fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Calendar Card -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
              <h5 class="fw-bold text-dark m-0" id="calendar_title"><i class="bi bi-calendar-event me-2 text-teal"></i>LỊCH CÔNG SUẤT</h5>
              <div class="small d-flex align-items-center gap-3">
                <span><i class="bi bi-circle-fill text-success me-1"></i>Còn trống (&lt;50%)</span>
                <span><i class="bi bi-circle-fill text-warning me-1"></i>Công suất cao (≥50%)</span>
                <span><i class="bi bi-circle-fill text-danger me-1"></i>Hết phòng (100%)</span>
              </div>
            </div>

            <!-- Calendar Grid -->
            <div id="calendar_grid" class="d-grid gap-2" style="grid-template-columns: repeat(7, 1fr);">
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- Modal Chi Tiết Ngày Booking -->
  <div class="modal fade" id="dayBookingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark" id="day_modal_title">
            <i class="bi bi-calendar-check text-teal me-2"></i>LỊCH CÔNG SUẤT & ĐƠN ĐẶT PHÒNG
          </h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">

          <div class="table-responsive">
            <table class="table table-hover border align-middle text-center">
              <thead>
                <tr class="bg-dark text-light">
                  <th scope="col">STT</th>
                  <th scope="col">Thông Tin Đơn & Khách Hàng</th>
                  <th scope="col">Thông Tin Phòng</th>
                  <th scope="col">Thời Gian Nhận/Trả</th>
                  <th scope="col">Trạng Thái Xếp Phòng</th>
                  <th scope="col">Thao Tác</th>
                </tr>
              </thead>
              <tbody id="day-bookings-data">
              </tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary shadow-none rounded-3 px-4 py-2" data-bs-dismiss="modal">ĐÓNG</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Xem Chi Tiết Đơn Đặt Phòng -->
  <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark">
            <i class="bi bi-file-text-fill text-teal me-2"></i>HỒ SƠ & CHI TIẾT ĐƠN ĐẶT PHÒNG KHÁCH SẠN
          </h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" id="booking-detail-content">
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary shadow-none rounded-3 px-4 py-2" data-bs-dismiss="modal">ĐÓNG</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Khai Báo Lưu Trú CCCD (Guest Declaration) -->
  <?php require('inc/guest_modal.php'); ?>



  <?php require('inc/scripts.php'); ?>

  <script src="scripts/booking_calendar.js"></script>

</body>
</html>
