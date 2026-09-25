<?php
  require('inc/essentials.php');
  adminLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản Trị Khách Sạn - Banner Quảng Cáo</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-images me-2 text-info"></i>QUẢN LÝ BANNER QUẢNG CÁO (CAROUSEL)</h3>


        <!-- Carousel section -->

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-image me-2 text-teal"></i>Danh Sách Hình Ảnh Banner</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#carousel-s">
                <i class="bi bi-plus-square me-1"></i> Thêm Banner Mới
              </button>
            </div>

            <div class="row" id="carousel-data">
            </div>

          </div>
        </div>

        <!-- Carousel modal -->

        <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form id="carousel_s_form">
              <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle text-teal me-2"></i>Thêm Banner Quảng Cáo Mới</h5>
                  <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Chọn hình ảnh banner</label>
                    <input type="file" name="carousel_picture" id="carousel_picture_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none rounded-3" required>
                  </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                  <button type="button" onclick="carousel_picture.value=''" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
                  <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">THÊM MỚI</button>
                </div>
              </div>
            </form>
          </div>
        </div>


      </div>
    </div>
  </div>
  

  <?php require('inc/scripts.php'); ?>
  <script src="scripts/carousel.js"></script>

</body>
</html>