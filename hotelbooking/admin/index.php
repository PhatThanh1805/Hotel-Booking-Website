<?php
  require('inc/essentials.php');
  require('inc/db_config.php');

  session_start();
  if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
    redirect('dashboard.php');
  }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Quản Trị - Đăng Nhập</title>
  <?php require('inc/links.php'); ?>
  <style>
    div.login-form{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      width: 400px;
    }
  </style>
</head>
<body class="bg-light">
  
  <div class="login-form text-center rounded-4 bg-white shadow overflow-hidden border-0">
    <form method="POST">
      <h4 class="bg-dark custom-bg text-white py-3 fw-bold m-0"><i class="bi bi-shield-lock me-2"></i>ĐĂNG NHẬP QUẢN TRỊ</h4>
      <div class="p-4">
        <div class="mb-3 text-start">
          <label class="form-label fw-semibold">Tên đăng nhập</label>
          <input name="admin_name" required type="text" class="form-control shadow-none py-2 rounded-3" placeholder="Nhập tài khoản admin...">
        </div>
        <div class="mb-4 text-start">
          <label class="form-label fw-semibold">Mật khẩu</label>
          <div class="input-group">
            <input id="admin_pass_input" name="admin_pass" required type="password" class="form-control shadow-none py-2 rounded-start-3" placeholder="Nhập mật khẩu admin...">
            <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('admin_pass_input', this)">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
        </div>
        <button name="login" type="submit" class="btn text-white custom-bg shadow-none w-100 py-2 rounded-3 fw-bold fs-6">
          <i class="bi bi-box-arrow-in-right me-1"></i> ĐĂNG NHẬP
        </button>
      </div>
    </form>
  </div>


  <?php 
    
    if(isset($_POST['login']))
    {
      $frm_data = filteration($_POST);

      $query = "SELECT * FROM  `admin_cred` WHERE `admin_name`=? AND `admin_pass`=?";
      $values = [$frm_data['admin_name'],$frm_data['admin_pass']];

      $res = select($query,$values,"ss");
      if($res->num_rows==1){
        $row = mysqli_fetch_assoc($res);
        $_SESSION['adminLogin'] = true;
        $_SESSION['adminId'] = $row['sr_no'];
        redirect('dashboard.php');
      }
      else{
        alert('error','Đăng nhập thất bại - Tài khoản hoặc mật khẩu không đúng!');
      }
    }
  
  ?>


  <?php require('inc/scripts.php') ?>
  <script>
    function togglePasswordVisibility(inputId, btn) {
      let input = document.getElementById(inputId);
      if (input) {
        let icon = btn.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          if(icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
        } else {
          input.type = 'password';
          if(icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
        }
      }
    }
  </script>
</body>
</html>