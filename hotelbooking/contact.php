<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - LIÊN HỆ</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center text-dark">LIÊN HỆ VỚI CHÚNG TÔI</h2>
    <div class="h-line mb-4"></div>
    <p class="text-center text-secondary">
      Bạn có bất kỳ thắc mắc hoặc yêu cầu hỗ trợ nào? <br> Vui lòng gửi tin nhắn cho chúng tôi, đội ngũ hỗ trợ khách hàng sẽ phản hồi sớm nhất!
    </p>
  </div>

  <div class="container mb-5">
    <div class="row">
      <div class="col-lg-6 col-md-6 mb-5 px-4">

        <div class="bg-white rounded-4 shadow-sm p-4">
          <iframe class="w-100 rounded-3 mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>

          <h5 class="fw-bold"><i class="bi bi-geo-alt-fill text-teal me-2"></i>Địa chỉ Khách sạn</h5>
          <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-3 fw-medium">
            <?php echo $contact_r['address'] ?>
          </a>

          <h5 class="mt-3 fw-bold"><i class="bi bi-telephone-fill text-teal me-2"></i>Hotline Hỗ Trợ</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark fw-medium">
            +<?php echo $contact_r['pn1'] ?>
          </a>
          <br>
          <?php 
            if($contact_r['pn2']!=''){
              echo<<<data
                <a href="tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark fw-medium">
                  +$contact_r[pn2]
                </a>
              data;
            }
          ?>


          <h5 class="mt-4 fw-bold"><i class="bi bi-envelope-fill text-teal me-2"></i>Email Liên Hệ</h5>
          <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block text-decoration-none text-dark fw-medium">
            <?php echo $contact_r['email'] ?>
          </a>

          <h5 class="mt-4 fw-bold"><i class="bi bi-share text-teal me-2"></i>Kênh Mạng Xã Hội</h5>
          <?php 
            if($contact_r['tw']!=''){
              echo<<<data
                <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-3">
                  <i class="bi bi-twitter text-info"></i>
                </a>
              data;
            }
          ?>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-3">
            <i class="bi bi-facebook text-primary"></i>
          </a>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5">
            <i class="bi bi-instagram text-danger"></i>
          </a>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded-4 shadow-sm p-4">
          <form method="POST">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-right-text text-teal me-2"></i>Gửi Tin Nhắn Phản Hồi</h5>
            <div class="mt-3">
              <label class="form-label fw-semibold">Họ và tên</label>
              <input name="name" required type="text" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập họ tên...">
            </div>
            <div class="mt-3">
              <label class="form-label fw-semibold">Địa chỉ Email</label>
              <input name="email" required type="email" class="form-control shadow-none py-2 rounded-3" placeholder="name@example.com">
            </div>
            <div class="mt-3">
              <label class="form-label fw-semibold">Tiêu đề tin nhắn</label>
              <input name="subject" required type="text" class="form-control shadow-none py-2 rounded-3" placeholder="Ví dụ: Hỏi về dịch vụ phòng...">
            </div>
            <div class="mt-3">
              <label class="form-label fw-semibold">Nội dung chi tiết</label>
              <textarea name="message" required class="form-control shadow-none rounded-3" rows="5" style="resize: none;" placeholder="Nhập nội dung thắc mắc của bạn..."></textarea>
            </div>
            <button type="submit" name="send" class="btn text-white custom-bg mt-4 py-2 px-4 rounded-3 fw-bold">
              <i class="bi bi-send me-1"></i> GỬI TIN NHẮN
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <?php 

    if(isset($_POST['send']))
    {
      $frm_data = filteration($_POST);

      $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
      $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

      $res = insert($q,$values,'ssss');
      if($res==1){
        alert('success','Gửi tin nhắn liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.');
      }
      else{
        alert('error','Lỗi kết nối máy chủ! Vui lòng thử lại sau.');
      }
    }
  ?>

  <?php require('inc/footer.php'); ?>

</body>
</html>