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
  <title>Quản Trị Khách Sạn - Thống Kê Tổng Quan & Analytics</title>
  <?php require('inc/links.php'); ?>
  <style>
    .analytics-card {
      transition: all 0.3s ease;
      border-radius: 1rem;
    }
    .analytics-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .chart-container {
      position: relative;
      min-height: 320px;
    }
    .occupancy-gauge {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #fff;
    }
  </style>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 
    
    $is_shutdown = mysqli_fetch_assoc(mysqli_query($con,"SELECT `shutdown` FROM `settings`"));

    $current_bookings = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
      COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
      COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings`
      FROM `booking_order`"));

    $unread_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
      FROM `user_queries` WHERE `seen`=0"));

    $unread_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
      FROM `rating_review` WHERE `seen`=0"));
    
    $current_users = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
      COUNT(id) AS `total`,
      COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
      COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
      COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
      FROM `user_cred`"));  
  ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        
        <!-- Header Banner & Maintenance Alert -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
          <div>
            <h3 class="fw-bold text-dark mb-1">
              <i class="bi bi-speedometer2 text-teal me-2"></i>THỐNG KÊ TỔNG QUAN & ANALYTICS
            </h3>
            <p class="text-muted small mb-0">Hệ thống báo cáo doanh thu, tỷ lệ công suất phòng & tương tác khách hàng thời gian thực</p>
          </div>
          <?php 
            if($is_shutdown['shutdown']){
              echo<<<data
                <div class="badge bg-danger py-2 px-3 rounded-pill shadow-sm fs-7">
                  <i class="bi bi-exclamation-triangle-fill me-1"></i>Chế độ Bảo trì Hệ thống đang BẬT!
                </div>
              data;
            }
          ?>
        </div>

        <!-- 1. Thống kê nhanh đơn cần xử lý ngay -->
        <div class="row mb-4">
          <div class="col-xl-3 col-md-6 mb-3">
            <a href="new_bookings.php" class="text-decoration-none">
              <div class="card border-0 shadow-sm analytics-card bg-white p-3 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-semibold text-muted mb-1 fs-7">Đơn Đặt Mới</h6>
                    <h2 class="fw-bold text-success mb-0"><?php echo $current_bookings['new_bookings'] ?></h2>
                  </div>
                  <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 fs-3">
                    <i class="bi bi-journal-plus"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <a href="refund_bookings.php" class="text-decoration-none">
              <div class="card border-0 shadow-sm analytics-card bg-white p-3 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-semibold text-muted mb-1 fs-7">Yêu Cầu Hoàn Tiền</h6>
                    <h2 class="fw-bold text-warning mb-0"><?php echo $current_bookings['refund_bookings'] ?></h2>
                  </div>
                  <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 fs-3">
                    <i class="bi bi-arrow-counterclockwise"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <a href="user_queries.php" class="text-decoration-none">
              <div class="card border-0 shadow-sm analytics-card bg-white p-3 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-semibold text-muted mb-1 fs-7">Tin Nhắn Khách Hàng</h6>
                    <h2 class="fw-bold text-info mb-0"><?php echo $unread_queries['count'] ?></h2>
                  </div>
                  <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 fs-3">
                    <i class="bi bi-chat-left-dots-fill"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <div class="col-xl-3 col-md-6 mb-3">
            <a href="rate_review.php" class="text-decoration-none">
              <div class="card border-0 shadow-sm analytics-card bg-white p-3 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-semibold text-muted mb-1 fs-7">Đánh Giá & Bình Luận</h6>
                    <h2 class="fw-bold text-primary mb-0"><?php echo $unread_reviews['count'] ?></h2>
                  </div>
                  <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 fs-3">
                    <i class="bi bi-star-fill"></i>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <!-- 2. Thống kê Tỷ lệ Lấp Đầy Phòng Realtime & Biểu đồ Trạng thái Phòng -->
        <div class="row mb-4">
          <div class="col-lg-7 mb-3">
            <div class="card border-0 shadow-sm rounded-4 occupancy-gauge p-4 h-100">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-white">
                  <i class="bi bi-pie-chart-fill text-warning me-2"></i>Tỷ Lệ Lấp Đầy Phòng (Occupancy Rate)
                </h5>
                <span class="badge bg-teal px-3 py-2 rounded-pill fw-semibold">Realtime Status</span>
              </div>

              <div class="row align-items-center my-auto">
                <div class="col-md-5 text-center my-3 border-end border-secondary border-opacity-50">
                  <span class="text-white-50 text-uppercase tracking-wider small fw-semibold">Tỷ Lệ Sử Dụng Phòng</span>
                  <h1 class="display-3 fw-extrabold text-teal my-2" id="occupancy_rate_val">0%</h1>
                  <p class="text-white-50 small mb-0">Tính trên các phòng đang hoạt động</p>
                </div>
                <div class="col-md-7 ps-md-4">
                  <div class="mb-3">
                    <div class="d-flex justify-content-between text-white-50 small mb-1">
                      <span>Mức độ lấp đầy hiện tại</span>
                      <span class="fw-bold text-white">Công suất phòng</span>
                    </div>
                    <div class="progress bg-secondary bg-opacity-50 rounded-pill" style="height: 12px;">
                      <div class="progress-bar bg-teal rounded-pill progress-bar-striped progress-bar-animated" id="occupancy_bar" role="progressbar" style="width: 0%"></div>
                    </div>
                  </div>
                  
                  <div class="row text-center g-2 mt-3 fs-7">
                    <div class="col-4">
                      <div class="p-2 rounded bg-white bg-opacity-10">
                        <small class="text-white-50 d-block">Tổng Phòng</small>
                        <strong class="text-white fs-6" id="room_stat_total">0</strong>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="p-2 rounded bg-success bg-opacity-20 border border-success border-opacity-50">
                        <small class="text-success d-block">Đang Trống</small>
                        <strong class="text-white fs-6" id="room_stat_avail">0</strong>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="p-2 rounded bg-danger bg-opacity-20 border border-danger border-opacity-50">
                        <small class="text-danger d-block">Đang Có Khách</small>
                        <strong class="text-white fs-6" id="room_stat_occup">0</strong>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="p-2 rounded bg-primary bg-opacity-20 border border-primary border-opacity-50">
                        <small class="text-primary d-block">Đã Đặt</small>
                        <strong class="text-white fs-6" id="room_stat_bookd">0</strong>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="p-2 rounded bg-warning bg-opacity-20 border border-warning border-opacity-50">
                        <small class="text-warning d-block">Dọn Dẹp</small>
                        <strong class="text-white fs-6" id="room_stat_clean">0</strong>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="p-2 rounded bg-secondary bg-opacity-20">
                        <small class="text-white-50 d-block">Bảo Trì</small>
                        <strong class="text-white fs-6" id="room_stat_maint">0</strong>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-5 mb-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
              <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-donut-chart-fill text-teal me-2"></i>Phân Phối Trạng Thái Phòng
              </h5>
              <div class="chart-container d-flex align-items-center justify-content-center">
                <canvas id="occupancyChart" style="max-height: 240px;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Phân tích Đơn đặt phòng, Doanh thu & Charts -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
          <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 border-bottom pb-3">
            <div>
              <h5 class="fw-bold text-dark m-0">
                <i class="bi bi-graph-up-arrow text-teal me-2"></i>Báo Cáo Doanh Thu & Số Lượng Đơn Đặt Phòng
              </h5>
              <small class="text-muted">Xem biểu đồ xu hướng theo từng khoảng thời gian tùy chọn</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <label class="fw-semibold text-muted small me-1">Khoảng thời gian:</label>
              <select class="form-select shadow-none bg-light border-0 w-auto rounded-3 fw-bold text-teal" onchange="booking_analytics(this.value)">
                <option value="0">7 Ngày Qua</option>
                <option value="1" selected>30 Ngày Qua</option>
                <option value="2">90 Ngày Qua</option>
                <option value="3">1 Năm Qua</option>
                <option value="4">Tất Cả Thời Gian</option>
              </select>
            </div>
          </div>

          <!-- Cards chỉ số trực quan -->
          <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-teal bg-opacity-10 text-teal border border-teal border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">Tổng Doanh Thu</small>
                <h5 class="fw-bold mt-2 mb-0" id="total_amt">0 VNĐ</h5>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">Tổng Số Đơn</small>
                <h5 class="fw-bold mt-2 mb-0" id="total_bookings">0</h5>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-info bg-opacity-10 text-info border border-info border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">TB / Đơn (ADR)</small>
                <h5 class="fw-bold mt-2 mb-0" id="avg_amt">0 VNĐ</h5>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-success bg-opacity-10 text-success border border-success border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">Đơn Hoạt Động</small>
                <h5 class="fw-bold mt-2 mb-0" id="active_bookings">0</h5>
                <small class="d-block fw-semibold" id="active_amt">0 VNĐ</small>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">Đơn Đã Hủy</small>
                <h5 class="fw-bold mt-2 mb-0" id="cancelled_bookings">0</h5>
                <small class="d-block fw-semibold" id="cancelled_amt">0 VNĐ</small>
              </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
              <div class="p-3 rounded-4 bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 text-center">
                <small class="fw-semibold text-uppercase fs-7 d-block">Tỷ Lệ Hủy Đơn</small>
                <h5 class="fw-bold mt-2 mb-0" id="cancel_rate">0%</h5>
              </div>
            </div>
          </div>

          <!-- Section Biểu đồ -->
          <div class="row align-items-center">
            <div class="col-lg-8 mb-3">
              <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-bar-chart-fill me-2 text-teal"></i>Biểu Đồ Doanh Thu & Số Lượng Đơn Đặt Theo Thời Gian</h6>
              <div class="chart-container bg-light rounded-4 p-3 border">
                <canvas id="revenueChart" style="height: 320px;"></canvas>
              </div>
            </div>
            <div class="col-lg-4 mb-3">
              <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-pie-chart-fill me-2 text-teal"></i>Tỷ Lệ Trạng Thái Đơn Phòng</h6>
              <div class="chart-container bg-light rounded-4 p-3 border d-flex align-items-center justify-content-center">
                <canvas id="statusChart" style="max-height: 280px;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- 4. Phân tích Tương tác Khách hàng -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
            <h5 class="fw-bold text-dark m-0">
              <i class="bi bi-people-fill text-teal me-2"></i>Phân Tích Đăng Ký Tài Khoản & Tương Tác Khách Hàng
            </h5>
            <select class="form-select shadow-none bg-light border-0 w-auto rounded-3 fw-bold text-teal" onchange="user_analytics(this.value)">
              <option value="0">7 Ngày Qua</option>
              <option value="1" selected>30 Ngày Qua</option>
              <option value="2">90 Ngày Qua</option>
              <option value="3">1 Năm Qua</option>
            </select>
          </div>
        
          <div class="row">
            <div class="col-md-4 mb-3">
              <div class="card border-0 bg-success bg-opacity-10 p-3 rounded-4 text-center">
                <h6 class="fw-semibold text-success mb-1 fs-7">Đăng Ký Tài Khoản Mới</h6>
                <h2 class="fw-bold text-success mb-0" id="total_new_reg">0</h2>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card border-0 bg-info bg-opacity-10 p-3 rounded-4 text-center">
                <h6 class="fw-semibold text-info mb-1 fs-7">Phản Hồi & Liên Hệ Mới</h6>
                <h2 class="fw-bold text-info mb-0" id="total_queries">0</h2>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <div class="card border-0 bg-warning bg-opacity-10 p-3 rounded-4 text-center">
                <h6 class="fw-semibold text-dark mb-1 fs-7">Đánh Giá Từ Khách Hàng</h6>
                <h2 class="fw-bold text-warning mb-0" id="total_reviews">0</h2>
              </div>
            </div>
          </div>
        </div>
  
        <!-- 5. Thống kê Trạng thái Tài khoản Người dùng -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
          <h5 class="fw-bold text-dark mb-3 border-bottom pb-3">
            <i class="bi bi-person-badge-fill text-teal me-2"></i>Quản Lý Trạng Thái Tài Khoản Người Dùng
          </h5>
          <div class="row">
            <div class="col-md-3 mb-3">
              <div class="card border-0 bg-light p-3 text-center rounded-4 border-start border-4 border-info">
                <h6 class="fw-semibold text-muted mb-1 fs-7">Tổng Số Tài Khoản</h6>
                <h3 class="fw-bold text-dark mb-0"><?php echo $current_users['total'] ?></h3>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-0 bg-light p-3 text-center rounded-4 border-start border-4 border-success">
                <h6 class="fw-semibold text-muted mb-1 fs-7">Đang Hoạt Động</h6>
                <h3 class="fw-bold text-success mb-0"><?php echo $current_users['active'] ?></h3>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-0 bg-light p-3 text-center rounded-4 border-start border-4 border-warning">
                <h6 class="fw-semibold text-muted mb-1 fs-7">Đã Bị Khóa</h6>
                <h3 class="fw-bold text-warning mb-0"><?php echo $current_users['inactive'] ?></h3>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card border-0 bg-light p-3 text-center rounded-4 border-start border-4 border-danger">
                <h6 class="fw-semibold text-muted mb-1 fs-7">Chưa Xác Thực</h6>
                <h3 class="fw-bold text-danger mb-0"><?php echo $current_users['unverified'] ?></h3>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php require('inc/scripts.php'); ?>
  <script src="scripts/dashboard.js"></script>
</body>
</html>