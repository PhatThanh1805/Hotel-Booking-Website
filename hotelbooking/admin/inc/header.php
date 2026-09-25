<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top shadow">
  <h3 class="mb-0 h-font text-white"><i class="bi bi-building me-2 text-teal"></i>QUẢN TRỊ KHÁCH SẠN</h3>
  <div class="d-flex align-items-center gap-2">
    <button type="button" class="btn btn-theme-toggle btn-sm rounded-pill px-3 py-1 shadow-none fw-semibold d-flex align-items-center" onclick="toggleDarkMode()" title="Chuyển đổi giao diện Sáng / Tối">
      <i class="bi bi-moon-stars-fill text-warning me-1"></i>
      <span class="theme-text small">Tối</span>
    </button>
    <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3 shadow-none fw-semibold">
      <i class="bi bi-box-arrow-right me-1"></i>ĐĂNG XUẤT
    </a>
  </div>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-teal" id="dashboard-menu">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid flex-lg-column align-items-stretch">
      <h5 class="mt-2 text-light text-uppercase tracking-wider fw-bold border-bottom pb-2 border-secondary">
        <i class="bi bi-speedometer2 me-2 text-teal"></i>MENU QUẢN TRỊ
      </h5>
      <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="dashboard.php">
              <i class="bi bi-grid-1x2-fill me-2 text-teal"></i>Thống kê Dashboard
            </a>
          </li>
          <li class="nav-item">
            <button class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between py-2 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
              <span><i class="bi bi-journal-bookmark-fill me-2 text-warning"></i>Đơn Đặt Phòng</span>
              <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse show px-2 small mb-2" id="bookingLinks">
              <ul class="nav nav-pills flex-column rounded bg-secondary bg-opacity-25 p-1 border border-secondary border-opacity-50">
                <li class="nav-item">
                  <a class="nav-link text-white py-2" href="new_bookings.php">
                    <i class="bi bi-plus-circle me-2 text-success"></i>Đơn đặt mới
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white py-2" href="refund_bookings.php">
                    <i class="bi bi-arrow-counterclockwise me-2 text-danger"></i>Đơn hủy & Hoàn tiền
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white py-2" href="booking_calendar.php">
                    <i class="bi bi-calendar-week me-2 text-warning"></i>Lịch công suất phòng
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white py-2" href="booking_records.php">
                    <i class="bi bi-clock-history me-2 text-info"></i>Lịch sử đặt phòng
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="users.php">
              <i class="bi bi-people-fill me-2 text-info"></i>Quản lý Tài khoản (User/NV)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="user_queries.php">
              <i class="bi bi-chat-left-dots-fill me-2 text-primary"></i>Phản hồi & Liên hệ
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="rate_review.php">
              <i class="bi bi-star-fill me-2 text-warning"></i>Đánh giá & Bình luận
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="rooms.php">
              <i class="bi bi-door-closed-fill me-2 text-success"></i>Quản lý Phòng nghỉ
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="features_facilities.php">
              <i class="bi bi-stars me-2 text-secondary"></i>Tiện ích & Đặc điểm
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="carousel.php">
              <i class="bi bi-images me-2 text-light"></i>Banner quảng cáo
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white py-2 mb-1 rounded" href="settings.php">
              <i class="bi bi-gear-fill me-2 text-danger"></i>Cài đặt Hệ thống
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>