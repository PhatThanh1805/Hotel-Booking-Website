<div class="container-fluid bg-white mt-5 border-top">
  <div class="row">
    <div class="col-lg-4 p-4">
      <h3 class="h-font fw-bold fs-3 mb-3 text-teal"><?php echo $settings_r['site_title'] ?></h3>
      <p class="text-secondary lh-base">
        <?php echo $settings_r['site_about'] ?>
      </p>
    </div>
    <div class="col-lg-4 p-4">
      <h5 class="mb-3 fw-bold">Liên kết nhanh</h5>
      <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none"><i class="bi bi-chevron-right me-1 text-teal"></i>Trang chủ</a> <br>
      <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none"><i class="bi bi-chevron-right me-1 text-teal"></i>Danh sách Phòng</a> <br>
      <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none"><i class="bi bi-chevron-right me-1 text-teal"></i>Tiện ích</a> <br>
      <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none"><i class="bi bi-chevron-right me-1 text-teal"></i>Liên hệ</a> <br>
      <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none"><i class="bi bi-chevron-right me-1 text-teal"></i>Giới thiệu</a>
    </div>
    <div class="col-lg-4 p-4">
        <h5 class="mb-3 fw-bold">Theo dõi chúng tôi</h5>
        <?php 
          if($contact_r['tw']!=''){
            echo<<<data
              <a href="$contact_r[tw]" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-twitter me-1 text-info"></i> Twitter
              </a><br>
            data;
          }
        ?>
        <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark text-decoration-none mb-2">
          <i class="bi bi-facebook me-1 text-primary"></i> Facebook
        </a><br>
        <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark text-decoration-none">
          <i class="bi bi-instagram me-1 text-danger"></i> Instagram
        </a><br>
    </div>
  </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0 fw-normal">Hệ thống Đặt phòng Khách sạn Trực tuyến - Được thiết kế & Nâng cấp bởi He & [W]eat</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>

  function alert(type,msg,position='body')
  {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let element = document.createElement('div');
    element.innerHTML = `
      <div class="alert ${bs_class} alert-dismissible fade show shadow-lg border-0 rounded-3" role="alert">
        <strong class="me-3">${msg}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    if(position=='body'){
      document.body.append(element);
      element.classList.add('custom-alert');
    }
    else{
      document.getElementById(position).appendChild(element);
    }
    setTimeout(remAlert, 3500);
  }

  function remAlert(){
    let alerts = document.getElementsByClassName('alert');
    if(alerts.length > 0){
      alerts[0].remove();
    }
  }

  function setActive()
  {
    let navbar = document.getElementById('nav-bar');
    let a_tags = navbar.getElementsByTagName('a');

    for(i=0; i<a_tags.length; i++)
    {
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      if(document.location.href.indexOf(file_name) >= 0){
        a_tags[i].classList.add('active');
      }

    }
  }

  let register_form = document.getElementById('register-form');

  if(register_form) {
    register_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('name',register_form.elements['name'].value);
      data.append('email',register_form.elements['email'].value);
      data.append('phonenum',register_form.elements['phonenum'].value);
      data.append('address',register_form.elements['address'].value);
      data.append('pincode',register_form.elements['pincode'].value);
      data.append('dob',register_form.elements['dob'].value);
      data.append('pass',register_form.elements['pass'].value);
      data.append('cpass',register_form.elements['cpass'].value);
      data.append('profile',register_form.elements['profile'].files[0]);
      data.append('register','');

      var myModal = document.getElementById('registerModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      if(modal) modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'pass_mismatch'){
          alert('error',"Mật khẩu xác nhận không trùng khớp!");
        }
        else if(this.responseText == 'email_already'){
          alert('error',"Địa chỉ Email này đã được đăng ký!");
        }
        else if(this.responseText == 'phone_already'){
          alert('error',"Số điện thoại này đã được sử dụng!");
        }
        else if(this.responseText == 'inv_img'){
          alert('error',"Chỉ chấp nhận ảnh định dạng JPG, WEBP hoặc PNG!");
        }
        else if(this.responseText == 'upd_failed'){
          alert('error',"Tải ảnh đại diện lên thất bại!");
        }
        else if(this.responseText == 'mail_failed'){
          alert('error',"Không thể gửi email xác nhận! Lỗi kết nối server.");
        }
        else if(this.responseText == 'ins_failed'){
          alert('error',"Đăng ký thất bại! Lỗi hệ thống cơ sở dữ liệu.");
        }
        else{
          alert('success',"Đăng ký tài khoản thành công! Đã gửi liên kết xác nhận đến Email của bạn.");
          register_form.reset();
        }
      }

      xhr.send(data);
    });
  }

  let login_form = document.getElementById('login-form');

  if(login_form) {
    login_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email_mob',login_form.elements['email_mob'].value);
      data.append('pass',login_form.elements['pass'].value);
      data.append('login','');

      var myModal = document.getElementById('loginModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      if(modal) modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'inv_email_mob'){
          alert('error',"Email hoặc Số điện thoại không đúng!");
        }
        else if(this.responseText == 'not_verified'){
          alert('error',"Tài khoản chưa được xác thực Email!");
        }
        else if(this.responseText == 'inactive'){
          alert('error',"Tài khoản đã bị tạm khóa! Vui lòng liên hệ Quản trị viên.");
        }
        else if(this.responseText == 'invalid_pass'){
          alert('error',"Mật khẩu không chính xác!");
        }
        else{
          let fileurl = window.location.href.split('/').pop().split('?').shift();
          if(fileurl == 'room_details.php'){
            window.location = window.location.href;
          }
          else{
            window.location = window.location.pathname;
          }
        }
      }

      xhr.send(data);
    });
  }

  let forgot_form = document.getElementById('forgot-form');

  if(forgot_form) {
    forgot_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email',forgot_form.elements['email'].value);
      data.append('forgot_pass','');

      var myModal = document.getElementById('forgotModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      if(modal) modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'inv_email'){
          alert('error',"Địa chỉ Email không tồn tại!");
        }
        else if(this.responseText == 'not_verified'){
          alert('error',"Email chưa được xác thực! Vui lòng liên hệ Quản trị viên.");
        }
        else if(this.responseText == 'inactive'){
          alert('error',"Tài khoản đã bị tạm khóa! Vui lòng liên hệ Quản trị viên.");
        }
        else if(this.responseText == 'mail_failed'){
          alert('error',"Không thể gửi Email. Lỗi hệ thống máy chủ!");
        }
        else if(this.responseText == 'upd_failed'){
          alert('error',"Khôi phục tài khoản thất bại! Vui lòng thử lại.");
        }
        else{
          alert('success',"Đã gửi liên kết đặt lại mật khẩu đến Email của bạn!");
          forgot_form.reset();
        }
      }

      xhr.send(data);
    });
  }

  function checkLoginToBook(status,room_id){
    if(status){
      window.location.href='confirm_booking.php?id='+room_id;
    }
    else{
      alert('error','Vui lòng đăng nhập tài khoản để tiến hành đặt phòng!');
    }
  }

  setActive();

  // Dark Mode System Logic
  function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const isDark = savedTheme === 'dark';
    if (isDark) {
      document.documentElement.classList.add('dark-mode');
      if (document.body) document.body.classList.add('dark-mode');
    } else {
      document.documentElement.classList.remove('dark-mode');
      if (document.body) document.body.classList.remove('dark-mode');
    }
    updateThemeToggleButtons(isDark);
  }

  function toggleDarkMode() {
    const isDark = document.documentElement.classList.toggle('dark-mode');
    if (document.body) document.body.classList.toggle('dark-mode', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeToggleButtons(isDark);
  }

  function updateThemeToggleButtons(isDark) {
    const btns = document.querySelectorAll('.btn-theme-toggle');
    btns.forEach(btn => {
      const icon = btn.querySelector('i');
      const text = btn.querySelector('.theme-text');
      if (isDark) {
        if(icon) icon.className = 'bi bi-sun-fill text-warning me-1';
        if(text) text.textContent = 'Sáng';
        btn.title = 'Chuyển sang Chế độ Sáng';
      } else {
        if(icon) icon.className = 'bi bi-moon-stars-fill text-warning me-1';
        if(text) text.textContent = 'Tối';
        btn.title = 'Chuyển sang Chế độ Tối';
      }
    });
  }

  document.addEventListener('DOMContentLoaded', initTheme);
  initTheme();

</script>