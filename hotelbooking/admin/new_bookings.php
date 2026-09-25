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
  <title>Quản Trị Khách Sạn - Đơn Đặt Phòng Mới</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-plus-circle me-2 text-teal"></i>ĐƠN ĐẶT PHÒNG MỚI</h3>

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-journal-check me-1 text-teal"></i>Danh Sách Đơn Đặt Phòng Chưa Nhận Phòng</h5>
              <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 rounded-3 py-2" placeholder="Tìm kiếm đơn đặt phòng...">
            </div>

            <div class="table-responsive">
              <table class="table table-hover border align-middle text-center" style="min-width: 1200px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">STT</th>
                    <th scope="col">Thông Tin Khách Hàng</th>
                    <th scope="col">Thông Tin Phòng</th>
                    <th scope="col">Chi Tiết Đặt Phòng & Giá</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="table-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- Modal Xếp Số Phòng -->
  <div class="modal fade" id="assign-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="assign_room_form">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-door-open-fill text-teal me-2"></i>Xếp Số Phòng Nhận</h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Số phòng gán cho khách</label>
              <input type="text" name="room_no" class="form-control shadow-none py-2 rounded-3" placeholder="Ví dụ: P.101, P.202..." required>
            </div>
            <div class="alert alert-info border-0 rounded-3 mb-3 small">
              <i class="bi bi-info-circle-fill me-1"></i> Lưu ý: Chỉ thực hiện xếp số phòng khi khách hàng đã làm thủ tục nhận phòng (Check-in) tại quầy!
            </div>
            <input type="hidden" name="booking_id">
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="reset" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
            <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold"><i class="bi bi-check-lg me-1"></i>XÁC NHẬN XẾP PHÒNG</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <?php require('inc/guest_modal.php'); ?>

  <?php require('inc/scripts.php'); ?>

  <script src="scripts/new_bookings.js"></script>

</body>
</html>