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
  <title>Quản Trị Khách Sạn - Tiện Ích & Đặc Điểm</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-stars me-2 text-warning"></i>QUẢN LÝ TIỆN ÍCH & ĐẶC ĐIỂM PHÒNG</h3>

        <!-- Card Đặc điểm -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-star-fill text-warning me-2"></i>Đặc Điểm Nổi Bật</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#feature-s">
                <i class="bi bi-plus-square me-1"></i> Thêm Đặc Điểm
              </button>
            </div>

            <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
              <table class="table table-hover border align-middle text-center">
                <thead>
                  <tr class="bg-dark text-light sticky-top">
                    <th scope="col">STT</th>
                    <th scope="col">Tên Đặc Điểm</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="features-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

        <!-- Card Tiện ích -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-wifi text-teal me-2"></i>Tiện Ích Đi Kèm</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#facility-s">
                <i class="bi bi-plus-square me-1"></i> Thêm Tiện Ích
              </button>
            </div>

            <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
              <table class="table table-hover border align-middle text-center">
                <thead>
                  <tr class="bg-dark text-light sticky-top">
                    <th scope="col">STT</th>
                    <th scope="col">Biểu Tượng</th>
                    <th scope="col">Tên Tiện Ích</th>
                    <th scope="col" width="40%">Mô Tả Chi Tiết</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="facilities-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
  

  <!-- Feature modal -->
  <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="feature_s_form">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle text-warning me-2"></i>Thêm Đặc Điểm Nổi Bật</h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Tên đặc điểm</label>
              <input type="text" name="feature_name" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập tên đặc điểm (vd: View biển)..." required>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="reset" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
            <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">THÊM MỚI</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Facility modal -->
  <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="facility_s_form">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle text-teal me-2"></i>Thêm Tiện Ích Mới</h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Tên tiện ích</label>
              <input type="text" name="facility_name" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập tên tiện ích (vd: Wifi tốc độ cao)..." required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Biểu tượng (File SVG)</label>
              <input type="file" name="facility_icon" accept=".svg" class="form-control shadow-none rounded-3" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Mô tả tiện ích</label>
              <textarea name="facility_desc" class="form-control shadow-none rounded-3" rows="3" placeholder="Nhập mô tả ngắn..."></textarea>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="reset" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
            <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">THÊM MỚI</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <?php require('inc/scripts.php'); ?>
  <script src="scripts/features_facilities.js"></script>

</body>
</html>