<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - TIỆN ÍCH DỊCH VỤ</title>
  <style>
    .pop:hover{
      border-top-color: var(--teal) !important;
      transform: scale(1.03);
      transition: all 0.3s;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center text-dark">TIỆN ÍCH & DỊCH VỤ NỔI BẬT</h2>
    <div class="h-line mb-4"></div>
    <p class="text-center text-secondary">
      Chúng tôi cung cấp hệ thống dịch vụ tiện nghi hiện đại nhất nhằm phục vụ trải nghiệm <br> nghỉ dưỡng hoàn hảo và đáng nhớ nhất cho quý khách.
    </p>
  </div>

  <div class="container mb-5">
    <div class="row">
      <?php 
        $res = selectAll('facilities');
        $path = FACILITIES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo<<<data
            <div class="col-lg-4 col-md-6 mb-5 px-4">
              <div class="bg-white rounded-4 shadow-sm p-4 border-top border-4 border-teal pop h-100">
                <div class="d-flex align-items-center mb-3">
                  <img src="$path$row[icon]" width="48px">
                  <h5 class="m-0 ms-3 fw-bold text-dark">$row[name]</h5>
                </div>
                <p class="text-secondary lh-base m-0">$row[description]</p>
              </div>
            </div>
          data;
        }
      ?>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

</body>
</html>