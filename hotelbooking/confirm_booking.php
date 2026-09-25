<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - XÁC NHẬN ĐẶT PHÒNG</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <?php 

    if(!isset($_GET['id']) || $settings_r['shutdown']==true){
      redirect('rooms.php');
    }
    else if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('rooms.php');
    }

    $data = filteration($_GET);

    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

    if(mysqli_num_rows($room_res)==0){
      redirect('rooms.php');
    }

    $room_data = mysqli_fetch_assoc($room_res);
    $price_formatted = number_format($room_data['price'] * 1000, 0, ',', '.');

    $_SESSION['room'] = [
      "id" => $room_data['id'],
      "name" => $room_data['name'],
      "price" => $room_data['price'],
      "payment" => null,
      "available" => false,
    ];

    $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");
    $user_data = mysqli_fetch_assoc($user_res);

    $booking_created_at = date("d/m/Y H:i:s");
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold text-dark">XÁC NHẬN ĐẶT PHÒNG</h2>
        <div style="font-size: 15px;" class="fw-medium">
          <a href="index.php" class="text-secondary text-decoration-none"><i class="bi bi-house me-1"></i>TRANG CHỦ</a>
          <span class="text-secondary mx-2"> > </span>
          <a href="rooms.php" class="text-secondary text-decoration-none">DANH SÁCH PHÒNG</a>
          <span class="text-secondary mx-2"> > </span>
          <span class="text-dark">XÁC NHẬN ĐẶT PHÒNG</span>
        </div>
      </div>

      <!-- Ảnh và thông tin phòng -->
      <div class="col-lg-7 col-md-12 px-4 mb-4">
        <?php 

          $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
          $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
            WHERE `room_id`='$room_data[id]' 
            AND `thumb`='1'");

          if(mysqli_num_rows($thumb_q)>0){
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
          }

          echo<<<data
            <div class="card p-3 shadow-sm border-0 rounded-4 pop">
              <img src="$room_thumb" class="img-fluid rounded-3 mb-3" style="height: 350px; object-fit: cover;">
              <h4 class="fw-bold text-dark mb-1">$room_data[name]</h4>
              <h5 class="text-teal fw-bold">$price_formatted VNĐ <span class="fs-6 text-muted fw-normal">/ đêm</span></h5>
            </div>
          data;

        ?>
      </div>

      <!-- Form nhập thông tin đặt phòng -->
      <div class="col-lg-5 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-4 p-3">
          <div class="card-body">
            <form action="pay_now.php" method="POST" id="booking_form">
              <h5 class="mb-3 fw-bold text-dark border-bottom pb-2">
                <i class="bi bi-journal-check text-teal me-2"></i>THÔNG TIN ĐẶT PHÒNG
              </h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Họ và tên khách hàng</label>
                  <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none py-2 rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Số điện thoại</label>
                  <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none py-2 rounded-3" required>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label fw-semibold">Địa chỉ liên hệ</label>
                  <textarea name="address" class="form-control shadow-none rounded-3" rows="2" required><?php echo $user_data['address'] ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Ngày nhận phòng</label>
                  <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none py-2 rounded-3" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Ngày trả phòng</label>
                  <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none py-2 rounded-3" required>
                </div>
                
                <div class="col-md-12 mb-3">
                  <label class="form-label fw-semibold text-secondary">Thời gian thực hiện đặt phòng</label>
                  <input type="text" value="<?php echo $booking_created_at ?>" class="form-control shadow-none py-2 rounded-3 bg-light" readonly>
                </div>
                
                <div class="col-12">
                  <div class="spinner-border text-teal mb-3 d-none" id="info_loader" role="status">
                    <span class="visually-hidden">Đang tính toán giá...</span>
                  </div>

                  <div class="alert alert-warning border-0 rounded-3 mb-3 p-3" id="pay_info">
                    <i class="bi bi-info-circle-fill me-1"></i> Vui lòng chọn <strong>Ngày nhận phòng</strong> & <strong>Ngày trả phòng</strong> để tính giá!
                  </div>

                  <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none py-2 rounded-3 fs-6 fw-bold" disabled>
                    <i class="bi bi-credit-card me-1"></i> THANH TOÁN NGAY
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>


  <?php require('inc/footer.php'); ?>
  <script>

    let booking_form = document.getElementById('booking_form');
    let info_loader = document.getElementById('info_loader');
    let pay_info = document.getElementById('pay_info');

    function check_availability()
    {
      let checkin_val = booking_form.elements['checkin'].value;
      let checkout_val = booking_form.elements['checkout'].value;

      booking_form.elements['pay_now'].setAttribute('disabled',true);

      if(checkin_val!='' && checkout_val!='')
      {
        pay_info.classList.add('d-none');
        info_loader.classList.remove('d-none');

        let data = new FormData();

        data.append('check_availability','');
        data.append('check_in',checkin_val);
        data.append('check_out',checkout_val);

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/confirm_booking.php",true);

        xhr.onload = function()
        {
          let data = JSON.parse(this.responseText);

          pay_info.className = 'alert border-0 rounded-3 mb-3 p-3';

          if(data.status == 'check_in_out_equal'){
            pay_info.classList.add('alert-danger');
            pay_info.innerHTML = "<i class='bi bi-exclamation-triangle-fill me-1'></i> Ngày trả phòng không được trùng với ngày nhận phòng!";
          }
          else if(data.status == 'check_out_earlier'){
            pay_info.classList.add('alert-danger');
            pay_info.innerHTML = "<i class='bi bi-exclamation-triangle-fill me-1'></i> Ngày trả phòng không được trước ngày nhận phòng!";
          }
          else if(data.status == 'check_in_earlier'){
            pay_info.classList.add('alert-danger');
            pay_info.innerHTML = "<i class='bi bi-exclamation-triangle-fill me-1'></i> Ngày nhận phòng không được ở quá khứ!";
          }
          else if(data.status == 'unavailable'){
            pay_info.classList.add('alert-danger');
            pay_info.innerHTML = "<i class='bi bi-exclamation-triangle-fill me-1'></i> Rất tiếc, phòng đã được đặt kín vào khoảng thời gian này!";
          }
          else{
            let formatted_payment = new Intl.NumberFormat('vi-VN').format(data.payment * 1000);
            pay_info.classList.add('alert-success');
            pay_info.innerHTML = `
              <div class="fw-bold mb-1"><i class="bi bi-check-circle-fill me-1"></i> Đặt phòng khả dụng!</div>
              <div>• <strong>Số đêm lưu trú:</strong> ${data.days} đêm</div>
              <div>• <strong>Thời gian tạo đơn:</strong> <?php echo $booking_created_at ?></div>
              <div>• <strong>Tổng tiền thanh toán:</strong> <span class="fs-5 text-teal fw-bold">${formatted_payment} VNĐ</span></div>
            `;
            booking_form.elements['pay_now'].removeAttribute('disabled');
          }

          pay_info.classList.remove('d-none');
          info_loader.classList.add('d-none');
        }

        xhr.send(data);
      }

    }

  </script>

</body>
</html>