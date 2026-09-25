<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - GIỚI THIỆU</title>
  <style>
    .box{
      border-top-color: var(--teal) !important;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center text-dark">GIỚI THIỆU VỀ CHÚNG TÔI</h2>
    <div class="h-line mb-4"></div>
    <p class="text-center text-secondary">
      Khám phá hành trình xây dựng thương hiệu khách sạn đẳng cấp, không gian nghỉ dưỡng lý tưởng <br> và sứ mệnh mang lại sự hài lòng vượt mong đợi cho mỗi chuyến đi của quý khách.
    </p>
  </div>

  <div class="container mb-5">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-3 fw-bold text-dark">Trải Nghiệm Nghỉ Dưỡng Hoàn Hảo</h3>
        <p class="lh-base text-secondary">
          Chào mừng đến với Hệ thống Khách sạn Get Hotels. Với thiết kế sang trọng, hiện đại cùng trang thiết bị tiện nghi cao cấp, chúng tôi tự hào cung cấp dịch vụ lưu trú hàng đầu cho khách du lịch và doanh nhân.
        </p>
        <p class="lh-base text-secondary">
          Đội ngũ nhân viên chuyên nghiệp, tận tâm luôn sẵn sàng phục vụ 24/7 để mang đến cho bạn không gian ấm cúng như chính ngôi nhà của mình.
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/about/about.jpg" class="w-100 rounded-4 shadow-sm">
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded-4 shadow-sm p-4 border-top border-4 text-center box pop">
          <img src="images/about/hotel.svg" width="70px" class="mb-2">
          <h4 class="mt-3 fw-bold text-teal">100+ PHÒNG</h4>
          <span class="text-muted small">Thiết kế hiện đại</span>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded-4 shadow-sm p-4 border-top border-4 text-center box pop">
          <img src="images/about/customers.svg" width="70px" class="mb-2">
          <h4 class="mt-3 fw-bold text-teal">200+ KHÁCH HÀNG</h4>
          <span class="text-muted small">Tin tưởng mỗi tháng</span>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded-4 shadow-sm p-4 border-top border-4 text-center box pop">
          <img src="images/about/rating.svg" width="70px" class="mb-2">
          <h4 class="mt-3 fw-bold text-teal">150+ ĐÁNH GIÁ</h4>
          <span class="text-muted small">Chất lượng 5 sao</span>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded-4 shadow-sm p-4 border-top border-4 text-center box pop">
          <img src="images/about/staff.svg" width="70px" class="mb-2">
          <h4 class="mt-3 fw-bold text-teal">200+ NHÂN VIÊN</h4>
          <span class="text-muted small">Phục vụ tận tâm 24/7</span>
        </div>
      </div>
    </div>
  </div>

  <h3 class="my-5 fw-bold h-font text-center text-dark">ĐỘI NGŨ BAN QUẢN LÝ</h3>

  <div class="container px-4 mb-5">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php 
          $about_r = selectAll('team_details');
          $path=ABOUT_IMG_PATH;
          while($row = mysqli_fetch_assoc($about_r)){
            echo<<<data
              <div class="swiper-slide bg-white text-center overflow-hidden rounded-4 shadow-sm p-3">
                <img src="$path$row[picture]" class="w-100 rounded-3 mb-3" style="height: 320px; object-fit: cover;">
                <h5 class="mt-2 fw-bold text-dark">$row[name]</h5>
                <span class="text-secondary small">Quản trị viên hệ thống</span>
              </div>
            data;
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>


</body>
</html>