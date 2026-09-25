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
  <title>Quản Trị Khách Sạn - Quản Lý Phòng Nghi</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-door-closed-fill me-2 text-teal"></i>QUẢN LÝ PHÒNG NGHỈ KHÁCH SẠN</h3>
            <p class="text-muted small m-0">Quản lý danh sách phòng, theo dõi 4 trạng thái vận hành và cập nhật thông tin nhanh chóng.</p>
          </div>
          <button type="button" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#add-room">
            <i class="bi bi-plus-square-fill me-2"></i>Thêm Phòng Mới
          </button>
        </div>

        <!-- Metric Counter Cards - 4 Trạng Thái Chi Tiết -->
        <div class="row g-3 mb-4">
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-success bg-white pop">
              <div class="text-success fs-3 mb-1"><i class="bi bi-check-circle-fill"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_available">0</h4>
              <small class="text-muted fw-semibold">Đang Trống</small>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-warning bg-white pop">
              <div class="text-warning fs-3 mb-1"><i class="bi bi-stars"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_cleaning">0</h4>
              <small class="text-muted fw-semibold">Đang Dọn Dẹp</small>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-danger bg-white pop">
              <div class="text-danger fs-3 mb-1"><i class="bi bi-person-fill-check"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_occupied">0</h4>
              <small class="text-muted fw-semibold">Đang Có Khách</small>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-primary bg-white pop">
              <div class="text-primary fs-3 mb-1"><i class="bi bi-calendar-check-fill"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_booked">0</h4>
              <small class="text-muted fw-semibold">Đã Được Đặt</small>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-secondary bg-white pop">
              <div class="text-secondary fs-3 mb-1"><i class="bi bi-slash-circle-fill"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_maintenance">0</h4>
              <small class="text-muted fw-semibold">Tạm Dừng</small>
            </div>
          </div>
          <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-dark bg-white pop">
              <div class="text-dark fs-3 mb-1"><i class="bi bi-building"></i></div>
              <h4 class="fw-bold text-dark mb-0" id="stat_total">0</h4>
              <small class="text-muted fw-semibold">Tổng Số Phòng</small>
            </div>
          </div>
        </div>

        <!-- Main Card Section với Bộ Lọc Trạng Thái -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <!-- Filter Controls -->
            <div class="row align-items-center justify-content-between mb-4 g-3">
              <div class="col-md-7">
                <div class="d-flex flex-wrap gap-2" id="status-filter-tabs">
                  <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-2 fw-semibold filter-tab active" onclick="filterByStatus('all', this)">
                    Tất Cả
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 fw-semibold filter-tab" onclick="filterByStatus('1', this)">
                    <i class="bi bi-check-circle-fill me-1"></i>Đang Trống
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-2 fw-semibold filter-tab" onclick="filterByStatus('2', this)">
                    <i class="bi bi-stars me-1"></i>Dọn Dẹp
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-semibold filter-tab" onclick="filterByStatus('3', this)">
                    <i class="bi bi-person-fill-check me-1"></i>Đang Có Khách
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-semibold filter-tab" onclick="filterByStatus('4', this)">
                    <i class="bi bi-calendar-check-fill me-1"></i>Đã Đặt
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold filter-tab" onclick="filterByStatus('0', this)">
                    <i class="bi bi-slash-circle-fill me-1"></i>Tạm Dừng
                  </button>
                </div>
              </div>

              <div class="col-md-5">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted"><i class="bi bi-search"></i></span>
                  <input type="text" id="search_room_input" class="form-control shadow-none border-start-0 rounded-end-3 py-2" placeholder="Tìm kiếm theo tên phòng..." oninput="onSearchInput()">
                </div>
              </div>
            </div>

            <!-- Table section -->
            <div class="table-responsive-lg" style="height: 480px; overflow-y: scroll;">
              <table class="table table-hover border align-middle text-center">
                <thead>
                  <tr class="bg-dark text-light sticky-top" style="z-index: 5;">
                    <th scope="col">STT</th>
                    <th scope="col" class="text-start ps-3">Tên Phòng nghỉ</th>
                    <th scope="col">Diện Tích</th>
                    <th scope="col">Sức Chứa</th>
                    <th scope="col">Đơn Giá / Đêm</th>
                    <th scope="col">Số Lượng</th>
                    <th scope="col">Trạng Thái Chi Tiết</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="room-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
  

  <!-- Modal Thêm phòng mới -->
  <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <form id="add_room_form" autocomplete="off">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle-fill text-teal me-2"></i>Thêm Loại Phòng Mới</h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tên loại phòng</label>
                <input type="text" name="name" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập tên phòng..." required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Diện tích (m²)</label>
                <input type="number" min="1" name="area" class="form-control shadow-none py-2 rounded-3" placeholder="Ví dụ: 350" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Đơn giá phòng / đêm (x 1.000 VNĐ)</label>
                <input type="number" min="1" name="price" class="form-control shadow-none py-2 rounded-3" placeholder="Ví dụ: 500 (là 500.000 VNĐ)" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số lượng phòng hiện có</label>
                <input type="number" min="1" name="quantity" class="form-control shadow-none py-2 rounded-3" placeholder="Ví dụ: 10" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số người lớn (Tối đa)</label>
                <input type="number" min="1" name="adult" class="form-control shadow-none py-2 rounded-3" placeholder="Số người lớn..." required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số trẻ em (Tối đa)</label>
                <input type="number" min="0" name="children" class="form-control shadow-none py-2 rounded-3" placeholder="Số trẻ em..." required>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-teal"><i class="bi bi-star me-1"></i>Đặc điểm nổi bật</label>
                <div class="row">
                  <?php 
                    $res = selectAll('features');
                    while($opt = mysqli_fetch_assoc($res)){
                      echo"
                        <div class='col-md-3 mb-2'>
                          <label class='form-check-label'>
                            <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none me-1'>
                            $opt[name]
                          </label>
                        </div>
                      ";
                    }
                  ?>
                </div>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-teal"><i class="bi bi-wifi me-1"></i>Tiện ích đi kèm</label>
                <div class="row">
                  <?php 
                    $res = selectAll('facilities');
                    while($opt = mysqli_fetch_assoc($res)){
                      echo"
                        <div class='col-md-3 mb-2'>
                          <label class='form-check-label'>
                            <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none me-1'>
                            $opt[name]
                          </label>
                        </div>
                      ";
                    }
                  ?>
                </div>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold">Mô tả phòng chi tiết</label>
                <textarea name="desc" rows="3" class="form-control shadow-none rounded-3" placeholder="Nhập mô tả chi tiết về loại phòng..." required></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="reset" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
            <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold"><i class="bi bi-check-lg me-1"></i>THÊM PHÒNG</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Sửa thông tin phòng -->
  <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <form id="edit_room_form" autocomplete="off">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-teal me-2"></i>Cập Nhật Thông Tin Phòng</h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Tên loại phòng</label>
                <input type="text" name="name" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Diện tích (m²)</label>
                <input type="number" min="1" name="area" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Đơn giá phòng / đêm (x 1.000 VNĐ)</label>
                <input type="number" min="1" name="price" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số lượng phòng hiện có</label>
                <input type="number" min="1" name="quantity" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số người lớn (Tối đa)</label>
                <input type="number" min="1" name="adult" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Số trẻ em (Tối đa)</label>
                <input type="number" min="0" name="children" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-teal"><i class="bi bi-star me-1"></i>Đặc điểm nổi bật</label>
                <div class="row">
                  <?php 
                    $res = selectAll('features');
                    while($opt = mysqli_fetch_assoc($res)){
                      echo"
                        <div class='col-md-3 mb-2'>
                          <label class='form-check-label'>
                            <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none me-1'>
                            $opt[name]
                          </label>
                        </div>
                      ";
                    }
                  ?>
                </div>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-teal"><i class="bi bi-wifi me-1"></i>Tiện ích đi kèm</label>
                <div class="row">
                  <?php 
                    $res = selectAll('facilities');
                    while($opt = mysqli_fetch_assoc($res)){
                      echo"
                        <div class='col-md-3 mb-2'>
                          <label class='form-check-label'>
                            <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none me-1'>
                            $opt[name]
                          </label>
                        </div>
                      ";
                    }
                  ?>
                </div>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold">Mô tả phòng chi tiết</label>
                <textarea name="desc" rows="3" class="form-control shadow-none rounded-3" required></textarea>
              </div>
              <input type="hidden" name="room_id">
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="reset" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
            <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold"><i class="bi bi-check-lg me-1"></i>LƯU THAY ĐỔI</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Quản lý ảnh phòng -->
  <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark" id="room-name-title"><i class="bi bi-images text-teal me-2"></i>Quản Lý Hình Ảnh Phòng</h5>
          <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div id="image-alert"></div>
          <div class="border-bottom pb-3 mb-4">
            <form id="add_image_form">
              <label class="form-label fw-semibold">Tải lên ảnh phòng mới</label>
              <div class="d-flex gap-2">
                <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none rounded-3" required>
                <button class="btn custom-bg text-white shadow-none rounded-3 px-4 fw-semibold text-nowrap"><i class="bi bi-upload me-1"></i>TẢI LÊN</button>
              </div>
              <input type="hidden" name="room_id">
            </form>
          </div>
          <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
            <table class="table table-hover border text-center align-middle">
              <thead>
                <tr class="bg-dark text-light sticky-top">
                  <th scope="col" width="50%">Hình Ảnh</th>
                  <th scope="col">Ảnh Đại Diện</th>
                  <th scope="col">Xóa Ảnh</th>
                </tr>
              </thead>
              <tbody id="room-image-data">                 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php require('inc/scripts.php'); ?>

  <script src="scripts/rooms.js"></script>

</body>
</html>