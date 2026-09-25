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
  <title>Quản Trị Khách Sạn - Quản Lý Tài Khoản</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-people-fill me-2 text-teal"></i>QUẢN LÝ TÀI KHOẢN (KHÁCH HÀNG & NHÂN VIÊN)</h3>

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-list-ul me-1 text-teal"></i>Danh Sách Tài Khoản Người Dùng</h5>
              <input type="text" oninput="search_user(this.value)" class="form-control shadow-none w-25 rounded-3 py-2" placeholder="Tìm tên, email, SĐT...">
            </div>

            <div class="table-responsive">
              <table class="table table-hover border text-center align-middle" style="min-width: 1300px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">STT</th>
                    <th scope="col">Họ & Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Số Điện Thoại</th>
                    <th scope="col">Địa Chỉ / Mã Bưu Chính</th>
                    <th scope="col">Ngày Sinh</th>
                    <th scope="col">Vai Trò</th>
                    <th scope="col">Xác Thực</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Ngày Đăng Ký</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="users-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>


  <?php require('inc/scripts.php'); ?>

  <script src="scripts/users.js"></script>

</body>
</html>