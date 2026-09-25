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
  <title>Quản Trị Khách Sạn - Cài Đặt Hệ Thống</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-gear-fill me-2 text-primary"></i>CÀI ĐẶT HỆ THỐNG KHÁCH SẠN</h3>

        <!-- General settings section -->

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-sliders me-2 text-teal"></i>Cài Đặt Chung</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#general-s">
                <i class="bi bi-pencil-square me-1"></i> Chỉnh Sửa
              </button>
            </div>
            <h6 class="card-subtitle mb-1 fw-bold text-muted">Tên Trang Web</h6>
            <p class="card-text fs-6 text-dark" id="site_title"></p>
            <h6 class="card-subtitle mb-1 fw-bold text-muted">Nội Dung Giới Thiệu (About Us)</h6>
            <p class="card-text fs-6 text-dark" id="site_about"></p>
          </div>
        </div>

        <!-- General settings modal -->

        <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form id="general_s_form">
              <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold text-dark"><i class="bi bi-sliders text-teal me-2"></i>Cài Đặt Thông Tin Chung</h5>
                  <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Tên trang web</label>
                    <input type="text" name="site_title" id="site_title_inp" class="form-control shadow-none py-2 rounded-3" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Nội dung giới thiệu khách sạn</label>
                    <textarea name="site_about" id="site_about_inp" class="form-control shadow-none rounded-3" rows="6" required></textarea>
                  </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                  <button type="button" onclick="site_title.value = general_data.site_title, site_about.value = general_data.site_about" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
                  <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">LƯU THAY ĐỔI</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Shutdown section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-power text-danger me-2"></i>Tạm Dừng Hoạt Động Website</h5>
              <div class="form-check form-switch fs-5">
                <form>
                  <input onchange="upd_shutdown(this.value)" class="form-check-input shadow-none" type="checkbox" id="shutdown-toggle">
                </form>
              </div>
            </div>
            <p class="card-text text-muted">
              Khi bật chế độ tạm dừng, khách hàng sẽ không thể thực hiện đặt phòng trực tuyến trên hệ thống.
            </p>
          </div>
        </div>

        <!-- Contact details section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-telephone-outbound-fill me-2 text-success"></i>Thông Tin Liên Hệ Khách Sạn</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#contacts-s">
                <i class="bi bi-pencil-square me-1"></i> Chỉnh Sửa
              </button>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Địa Chỉ</h6>
                  <p class="card-text fs-6 text-dark" id="address"></p>
                </div>
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Đường Dẫn Google Maps</h6>
                  <p class="card-text fs-6 text-dark text-break" id="gmap"></p>
                </div>
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Số Điện Thoại Hotline</h6>
                  <p class="card-text mb-1 fs-6">
                    <i class="bi bi-telephone-fill me-1 text-teal"></i>
                    <span id="pn1"></span>
                  </p>
                  <p class="card-text fs-6">
                    <i class="bi bi-telephone-fill me-1 text-teal"></i>
                    <span id="pn2"></span>
                  </p>
                </div>
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Email Liên Hệ</h6>
                  <p class="card-text fs-6 text-dark" id="email"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Mạng Xã Hội</h6>
                  <p class="card-text mb-1 fs-6">
                    <i class="bi bi-facebook me-2 text-primary"></i>
                    <span id="fb"></span>
                  </p>
                  <p class="card-text mb-1 fs-6">
                    <i class="bi bi-instagram me-2 text-danger"></i>
                    <span id="insta"></span>
                  </p>
                  <p class="card-text fs-6">
                    <i class="bi bi-twitter me-2 text-info"></i>
                    <span id="tw"></span>
                  </p>
                </div>
                <div class="mb-4">
                  <h6 class="card-subtitle mb-1 fw-bold text-muted">Bản Đồ Nhúng (iFrame)</h6>
                  <iframe id="iframe" class="border rounded-3 p-2 w-100" style="height: 180px;" loading="lazy"></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contacts details modal -->

        <div class="modal fade" id="contacts-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="contacts_s_form">
              <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold text-dark"><i class="bi bi-telephone-outbound text-teal me-2"></i>Cài Đặt Thông Tin Liên Hệ</h5>
                  <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <div class="container-fluid p-0">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Địa chỉ khách sạn</label>
                          <input type="text" name="address" id="address_inp" class="form-control shadow-none py-2 rounded-3" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Link Google Map</label>
                          <input type="text" name="gmap" id="gmap_inp" class="form-control shadow-none py-2 rounded-3" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Số điện thoại liên hệ</label>
                          <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="number" name="pn1" id="pn1_inp" class="form-control shadow-none py-2" required>
                          </div>
                          <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="number" name="pn2" id="pn2_inp" class="form-control shadow-none py-2">
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Email hỗ trợ</label>
                          <input type="email" name="email" id="email_inp" class="form-control shadow-none py-2 rounded-3" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Liên kết Mạng xã hội</label>
                          <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                            <input type="text" name="fb" id="fb_inp" class="form-control shadow-none py-2" required>
                          </div>
                          <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                            <input type="text" name="insta" id="insta_inp" class="form-control shadow-none py-2" required>
                          </div>
                          <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                            <input type="text" name="tw" id="tw_inp" class="form-control shadow-none py-2">
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Mã nhúng iFrame Google Map</label>
                          <input type="text" name="iframe" id="iframe_inp" class="form-control shadow-none py-2 rounded-3" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                  <button type="button" onclick="contacts_inp(contacts_data)" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
                  <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">LƯU THAY ĐỔI</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Management Team section -->

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="fw-bold text-dark m-0"><i class="bi bi-people-fill me-2 text-warning"></i>Đội Ngũ Quản Lý Khách Sạn</h5>
              <button type="button" class="btn btn-dark custom-bg text-white shadow-none btn-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#team-s">
                <i class="bi bi-plus-square me-1"></i> Thêm Thành Viên
              </button>
            </div>

            <div class="row" id="team-data">
            </div>

          </div>
        </div>

        <!-- Management Team modal -->

        <div class="modal fade" id="team-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form id="team_s_form">
              <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus-fill text-teal me-2"></i>Thêm Thành Viên Ban Quản Lý</h5>
                  <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Họ và tên thành viên</label>
                    <input type="text" name="member_name" id="member_name_inp" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập họ tên..." required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Ảnh đại diện</label>
                    <input type="file" name="member_picture" id="member_picture_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none rounded-3" required>
                  </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                  <button type="button" onclick="member_name.value='', member_picture.value=''" class="btn btn-light shadow-none rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
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
  <script src="scripts/settings.js"></script>

</body>
</html>