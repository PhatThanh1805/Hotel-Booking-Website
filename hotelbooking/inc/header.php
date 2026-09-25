<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-4 py-lg-3 shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font text-primary-gradient" href="index.php">
      <i class="bi bi-building-fill me-2 text-teal"></i><?php echo $settings_r['site_title'] ?>
    </a>
    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium">
        <li class="nav-item">
          <a class="nav-link me-2" href="index.php"><i class="bi bi-house-door me-1"></i>Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="rooms.php"><i class="bi bi-door-open me-1"></i>Danh sách Phòng</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="facilities.php"><i class="bi bi-stars me-1"></i>Tiện ích</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="contact.php"><i class="bi bi-envelope-paper me-1"></i>Liên hệ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php"><i class="bi bi-info-circle me-1"></i>Giới thiệu</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-theme-toggle rounded-pill px-3 py-1 me-lg-3 me-2 d-flex align-items-center" onclick="toggleDarkMode()" title="Chuyển đổi giao diện Sáng / Tối">
          <i class="bi bi-moon-stars-fill text-warning me-1"></i>
          <span class="theme-text fw-semibold small">Tối</span>
        </button>
        <?php 
          if(isset($_SESSION['login']) && $_SESSION['login']==true)
          {
            $path = USERS_IMG_PATH;
            echo<<<data
              <div class="btn-group">
                <button type="button" class="btn btn-outline-dark shadow-none dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                  <img src="$path$_SESSION[uPic]" style="width: 28px; height: 28px; object-fit: cover;" class="me-2 rounded-circle border border-2 border-primary">
                  <span class="fw-semibold">$_SESSION[uName]</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end shadow border-0 rounded-3 mt-2">
                  <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2 text-primary"></i>Hồ sơ cá nhân</a></li>
                  <li><a class="dropdown-item py-2" href="bookings.php"><i class="bi bi-journal-check me-2 text-success"></i>Đơn đặt phòng</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                </ul>
              </div>
            data;
          }
          else
          {
            echo<<<data
              <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2 rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
              </button>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="bi bi-person-plus me-1"></i>Đăng ký
              </button>
            data;
          }
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- Modal Đăng nhập -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form id="login-form">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title d-flex align-items-center fw-bold fs-4 text-dark">
            <i class="bi bi-person-circle fs-2 me-2 text-teal"></i> Đăng nhập Khách hàng
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Email hoặc Số điện thoại</label>
            <input type="text" name="email_mob" required class="form-control shadow-none py-2 rounded-3" placeholder="Nhập email hoặc SĐT...">
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Mật khẩu</label>
            <div class="input-group">
              <input type="password" id="login_pass_input" name="pass" required class="form-control shadow-none py-2 rounded-start-3" placeholder="Nhập mật khẩu...">
              <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('login_pass_input', this)">
                <i class="bi bi-eye-slash"></i>
              </button>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <button type="submit" class="btn btn-dark custom-bg text-white shadow-none px-4 py-2 rounded-3 fw-semibold">
              <i class="bi bi-box-arrow-in-right me-1"></i> ĐĂNG NHẬP
            </button>
            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0 fw-medium" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
              Quên mật khẩu?
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Đăng ký -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form id="register-form">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title d-flex align-items-center fw-bold fs-4 text-dark">
            <i class="bi bi-person-lines-fill fs-2 me-2 text-teal"></i> Đăng ký Tài khoản Mới
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="container-fluid p-0">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Họ và tên</label>
                <input name="name" type="text" class="form-control shadow-none py-2 rounded-3" placeholder="Nguyễn Văn A" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Địa chỉ Email</label>
                <input name="email" type="email" class="form-control shadow-none py-2 rounded-3" placeholder="example@gmail.com" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <input name="phonenum" type="number" class="form-control shadow-none py-2 rounded-3" placeholder="0912345678" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Ảnh đại diện</label>
                <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label fw-semibold">Địa chỉ liên hệ</label>
                <textarea name="address" class="form-control shadow-none rounded-3" rows="2" placeholder="Số nhà, Tên đường, Quận/Huyện, Tỉnh/TP..." required></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Mã bưu chính (Pincode)</label>
                <input name="pincode" type="number" class="form-control shadow-none py-2 rounded-3" placeholder="700000" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Ngày sinh</label>
                <input name="dob" type="date" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Mật khẩu</label>
                <div class="input-group">
                  <input id="reg_pass_input" name="pass" type="password" class="form-control shadow-none py-2 rounded-start-3" placeholder="Tối thiểu 6 ký tự..." required>
                  <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('reg_pass_input', this)">
                    <i class="bi bi-eye-slash"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                <div class="input-group">
                  <input id="reg_cpass_input" name="cpass" type="password" class="form-control shadow-none py-2 rounded-start-3" placeholder="Nhập lại mật khẩu..." required>
                  <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('reg_cpass_input', this)">
                    <i class="bi bi-eye-slash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-dark custom-bg text-white shadow-none px-5 py-2 rounded-3 fw-bold">
              <i class="bi bi-check-circle me-1"></i> HOÀN TẤT ĐĂNG KÝ
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Quên Mật Khẩu -->
<div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form id="forgot-form">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title d-flex align-items-center fw-bold fs-4 text-dark">
            <i class="bi bi-shield-lock fs-2 me-2 text-teal"></i> Khôi phục Mật khẩu
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-info border-0 rounded-3 mb-3 text-wrap lh-base small">
            <i class="bi bi-info-circle-fill me-1"></i> Lưu ý: Đường dẫn đặt lại mật khẩu sẽ được gửi trực tiếp đến địa chỉ Email đã đăng ký của bạn!
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Địa chỉ Email đã đăng ký</label>
            <input type="email" name="email" required class="form-control shadow-none py-2 rounded-3" placeholder="nhapemail@gmail.com">
          </div>
          <div class="mb-2 text-end">
            <button type="button" class="btn btn-light shadow-none me-2 py-2 px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              QUAY LẠI
            </button>
            <button type="submit" class="btn btn-dark custom-bg text-white shadow-none py-2 px-4 rounded-3 fw-semibold">
              <i class="bi bi-send me-1"></i> GỬI LIÊN KẾT
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function togglePasswordVisibility(inputId, btn) {
    let input = document.getElementById(inputId);
    if (input) {
      let icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        if(icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
      } else {
        input.type = 'password';
        if(icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
      }
    }
  }
</script>