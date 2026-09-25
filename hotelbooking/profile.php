<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - HỒ SƠ CÁ NHÂN</title>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }

    $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'s');

    if(mysqli_num_rows($u_exist)==0){
      redirect('index.php');
    }

    $u_fetch = mysqli_fetch_assoc($u_exist);
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 px-4">
        <h2 class="fw-bold text-dark">HỒ SƠ CÁ NHÂN</h2>
        <div style="font-size: 15px;" class="fw-medium">
          <a href="index.php" class="text-secondary text-decoration-none"><i class="bi bi-house me-1"></i>TRANG CHỦ</a>
          <span class="text-secondary mx-2"> > </span>
          <span class="text-dark">HỒ SƠ CÁ NHÂN</span>
        </div>
      </div>

      <!-- Thông tin cơ bản -->
      <div class="col-12 mb-5 px-4">
        <div class="bg-white p-4 rounded-4 shadow-sm">
          <form id="info-form">
            <h5 class="mb-3 fw-bold text-dark"><i class="bi bi-person-lines-fill text-teal me-2"></i>Thông Tin Cá Nhân</h5>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Họ và tên</label>
                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Ngày sinh</label>
                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Mã bưu chính (Pincode)</label>
                <input name="pincode" type="number" value="<?php echo $u_fetch['pincode'] ?>" class="form-control shadow-none py-2 rounded-3" required>
              </div>
              <div class="col-md-8 mb-4">
                <label class="form-label fw-semibold">Địa chỉ liên hệ</label>
                <textarea name="address" class="form-control shadow-none rounded-3" rows="2" required><?php echo $u_fetch['address'] ?></textarea>
              </div>
            </div>
            <button type="submit" class="btn text-white custom-bg shadow-none px-4 py-2 rounded-3 fw-bold">
              <i class="bi bi-check-lg me-1"></i> LƯU THAY ĐỔI
            </button>
          </form>
        </div>
      </div>

      <!-- Ảnh đại diện -->
      <div class="col-md-4 mb-5 px-4">
        <div class="bg-white p-4 rounded-4 shadow-sm h-100">
          <form id="profile-form">
            <h5 class="mb-3 fw-bold text-dark"><i class="bi bi-image text-teal me-2"></i>Ảnh Đại Diện</h5>
            <div class="text-center mb-3">
              <img src="<?php echo USERS_IMG_PATH.$u_fetch['profile'] ?>" class="rounded-circle img-fluid border border-3 border-teal" style="width: 140px; height: 140px; object-fit: cover;">
            </div>

            <label class="form-label fw-semibold">Chọn ảnh mới</label>
            <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="mb-4 form-control shadow-none py-2 rounded-3" required>

            <button type="submit" class="btn text-white custom-bg shadow-none w-100 py-2 rounded-3 fw-bold">
              <i class="bi bi-upload me-1"></i> TẢI ÁNH LÊN
            </button>
          </form>
        </div>
      </div>

      <!-- Đổi mật khẩu -->
      <div class="col-md-8 mb-5 px-4">
        <div class="bg-white p-4 rounded-4 shadow-sm h-100">
          <form id="pass-form">
            <h5 class="mb-3 fw-bold text-dark"><i class="bi bi-key-fill text-teal me-2"></i>Đổi Mật Khẩu</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Mật khẩu mới</label>
                <div class="input-group">
                  <input id="prof_new_pass" name="new_pass" type="password" class="form-control shadow-none py-2 rounded-start-3" placeholder="Mật khẩu mới..." required>
                  <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('prof_new_pass', this)">
                    <i class="bi bi-eye-slash"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                <div class="input-group">
                  <input id="prof_confirm_pass" name="confirm_pass" type="password" class="form-control shadow-none py-2 rounded-start-3" placeholder="Nhập lại mật khẩu mới..." required>
                  <button class="btn btn-outline-secondary border shadow-none rounded-end-3" type="button" onclick="togglePasswordVisibility('prof_confirm_pass', this)">
                    <i class="bi bi-eye-slash"></i>
                  </button>
                </div>
              </div>
            </div>
            <button type="submit" class="btn text-white custom-bg shadow-none px-4 py-2 rounded-3 fw-bold">
              <i class="bi bi-shield-lock me-1"></i> ĐỔI MẬT KHẨU
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <script>

    let info_form = document.getElementById('info-form');

    info_form.addEventListener('submit',function(e){
      e.preventDefault();

      let data = new FormData();
      data.append('info_form','');
      data.append('name',info_form.elements['name'].value);
      data.append('phonenum',info_form.elements['phonenum'].value);
      data.append('address',info_form.elements['address'].value);
      data.append('pincode',info_form.elements['pincode'].value);
      data.append('dob',info_form.elements['dob'].value);

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/profile.php",true);

      xhr.onload = function(){
        if(this.responseText == 'phone_already'){
          alert('error',"Số điện thoại này đã được sử dụng!");
        }
        else if(this.responseText == 0){
          alert('error',"Không có thông tin nào thay đổi!");
        }
        else{
          alert('success','Cập nhật thông tin cá nhân thành công!');
        }
      }

      xhr.send(data);

    });

    
    let profile_form = document.getElementById('profile-form');

    profile_form.addEventListener('submit',function(e){
      e.preventDefault();

      let data = new FormData();
      data.append('profile_form','');
      data.append('profile',profile_form.elements['profile'].files[0]);

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/profile.php",true);

      xhr.onload = function()
      {
        if(this.responseText == 'inv_img'){
          alert('error',"Chỉ chấp nhận định dạng ảnh JPG, WEBP hoặc PNG!");
        }
        else if(this.responseText == 'upd_failed'){
          alert('error',"Tải ảnh lên thất bại!");
        }
        else if(this.responseText == 0){
          alert('error',"Cập nhật ảnh đại diện thất bại!");
        }
        else{
          alert('success','Đã cập nhật ảnh đại diện mới!');
          setTimeout(() => { window.location.href=window.location.pathname; }, 1000);
        }
      }

      xhr.send(data);
    });


    let pass_form = document.getElementById('pass-form');

    pass_form.addEventListener('submit',function(e){
      e.preventDefault();

      let new_pass = pass_form.elements['new_pass'].value;
      let confirm_pass = pass_form.elements['confirm_pass'].value;

      if(new_pass!=confirm_pass){
        alert('error','Mật khẩu xác nhận không trùng khớp!');
        return false;
      }


      let data = new FormData();
      data.append('pass_form','');
      data.append('new_pass',new_pass);
      data.append('confirm_pass',confirm_pass);

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/profile.php",true);

      xhr.onload = function()
      {
        if(this.responseText == 'mismatch'){
          alert('error',"Mật khẩu xác nhận không trùng khớp!");
        }
        else if(this.responseText == 0){
          alert('error',"Cập nhật mật khẩu thất bại!");
        }
        else{
          alert('success','Đổi mật khẩu thành công!');
          pass_form.reset();
        }
      }

      xhr.send(data);
    });

  </script>

</body>
</html>