<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - DANH SÁCH PHÒNG</title>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 

    $checkin_default="";
    $checkout_default="";
    $adult_default="";
    $children_default="";

    if(isset($_GET['check_availability']))
    {
      $frm_data = filteration($_GET);

      $checkin_default = $frm_data['checkin'];
      $checkout_default = $frm_data['checkout'];
      $adult_default = $frm_data['adult'];
      $children_default = $frm_data['children'];
    }
  ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center text-dark">DANH SÁCH PHÒNG NGHỈ</h2>
    <div class="h-line mb-4"></div>
    <p class="text-center text-secondary">Lựa chọn phòng nghỉ phù hợp nhất với nhu cầu của bạn và trải nghiệm dịch vụ khách sạn đẳng cấp.</p>
  </div>

  <div class="container-fluid px-lg-4">
    <div class="row">

      <!-- Bộ lọc tìm kiếm -->
      <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded-4 shadow-sm p-3">
          <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2 fw-bold text-dark d-flex align-items-center justify-content-between">
              <span><i class="bi bi-funnel-fill text-teal me-2"></i>BỘ LỌC TÌM KIẾM</span>
            </h4>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-3" id="filterDropdown">
              
              <!-- Đặt phòng theo ngày -->
              <div class="border-0 bg-light p-3 rounded-3 mb-3">
                <h5 class="d-flex align-items-center justify-content-between mb-3 fw-bold text-dark" style="font-size: 16px;">
                  <span><i class="bi bi-calendar-range text-teal me-2"></i>KIỂM TRA THỜI GIAN</span>
                  <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-danger d-none p-0 fw-semibold">Xóa lọc</button>
                </h5>
                <label class="form-label fw-semibold">Ngày nhận phòng</label>
                <input type="date" class="form-control shadow-none mb-3 py-2 rounded-3" value="<?php echo $checkin_default ?>" id="checkin" onchange="chk_avail_filter()">
                <label class="form-label fw-semibold">Ngày trả phòng</label>
                <input type="date" class="form-control shadow-none py-2 rounded-3" value="<?php echo $checkout_default ?>"  id="checkout" onchange="chk_avail_filter()">
              </div>

              <!-- Tiện ích đi kèm -->
              <div class="border-0 bg-light p-3 rounded-3 mb-3">
                <h5 class="d-flex align-items-center justify-content-between mb-3 fw-bold text-dark" style="font-size: 16px;">
                  <span><i class="bi bi-stars text-teal me-2"></i>TIỆN ÍCH PHÒNG</span>
                  <button id="facilities_btn" onclick="facilities_clear()" class="btn shadow-none btn-sm text-danger d-none p-0 fw-semibold">Xóa lọc</button>
                </h5>
                <?php 
                  $facilities_q = selectAll('facilities');
                  while($row = mysqli_fetch_assoc($facilities_q))
                  {
                    echo<<<facilities
                      <div class="mb-2">
                        <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="$row[id]" class="form-check-input shadow-none me-2" id="facility_$row[id]">
                        <label class="form-check-label fw-medium" for="facility_$row[id]">$row[name]</label>
                      </div>
                    facilities;
                  }
                ?>
              </div>

              <!-- Số lượng khách -->
              <div class="border-0 bg-light p-3 rounded-3 mb-3">
                <h5 class="d-flex align-items-center justify-content-between mb-3 fw-bold text-dark" style="font-size: 16px;">
                  <span><i class="bi bi-people text-teal me-2"></i>SỨC CHỨA (GUEST)</span>
                  <button id="guests_btn" onclick="guests_clear()" class="btn shadow-none btn-sm text-danger d-none p-0 fw-semibold">Xóa lọc</button>
                </h5>
                <div class="d-flex">
                  <div class="me-3 w-50">
                    <label class="form-label fw-semibold">Người lớn</label>
                    <input type="number" min="1" id="adults" value="<?php echo $adult_default ?>" oninput="guests_filter()" class="form-control shadow-none py-2 rounded-3" placeholder="0">                 
                  </div>
                  <div class="w-50">
                    <label class="form-label fw-semibold">Trẻ em</label>
                    <input type="number" min="0" id="children" value="<?php echo $children_default ?>" oninput="guests_filter()" class="form-control shadow-none py-2 rounded-3" placeholder="0">                 
                  </div>
                </div>
              </div>

            </div>
          </div>
        </nav>
      </div>

      <!-- Danh sách phòng hiển thị qua AJAX -->
      <div class="col-lg-9 col-md-12 px-lg-4 px-md-3" id="rooms-data">
      </div>

    </div>
  </div>


  <script>

    let rooms_data = document.getElementById('rooms-data');

    let checkin = document.getElementById('checkin');
    let checkout = document.getElementById('checkout');
    let chk_avail_btn = document.getElementById('chk_avail_btn');

    let adults = document.getElementById('adults');
    let children = document.getElementById('children');
    let guests_btn = document.getElementById('guests_btn');
    
    let facilities_btn = document.getElementById('facilities_btn');

    function fetch_rooms()
    {
      let chk_avail = JSON.stringify({
        checkin: checkin.value,
        checkout: checkout.value
      });

      let guests = JSON.stringify({
        adults: adults.value,
        children: children.value
      });

      let facility_list = {"facilities":[]};

      let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
      if(get_facilities.length>0)
      {
        get_facilities.forEach((facility)=>{
          facility_list.facilities.push(facility.value);
        });
        facilities_btn.classList.remove('d-none');
      }
      else{
        facilities_btn.classList.add('d-none');
      }

      facility_list = JSON.stringify(facility_list);

      let xhr = new XMLHttpRequest();
      xhr.open("GET","ajax/rooms.php?fetch_rooms&chk_avail="+chk_avail+"&guests="+guests+"&facility_list="+facility_list,true);

      xhr.onprogress = function(){
        rooms_data.innerHTML = `<div class="spinner-border text-teal mb-3 d-block mx-auto" id="loader" role="status">
          <span class="visually-hidden">Đang tải dữ liệu phòng...</span>
        </div>`;
      }

      xhr.onload = function(){
        rooms_data.innerHTML = this.responseText;
      }

      xhr.send();
    }

    function chk_avail_filter(){
      if(checkin.value!='' && checkout.value !=''){
        fetch_rooms();
        chk_avail_btn.classList.remove('d-none');
      }
    }

    function chk_avail_clear(){
      checkin.value='';
      checkout.value='';
      chk_avail_btn.classList.add('d-none');
      fetch_rooms();
    }

    function guests_filter(){
      if(adults.value>0 || children.value>0){
        fetch_rooms();
        guests_btn.classList.remove('d-none');
      }
    }

    function guests_clear(){
      adults.value='';
      children.value='';
      guests_btn.classList.add('d-none');
      fetch_rooms();
    }

    function facilities_clear(){
      let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
      get_facilities.forEach((facility)=>{
        facility.checked=false;
      });
      facilities_btn.classList.add('d-none');
      fetch_rooms();
    }


    window.onload = function(){
      fetch_rooms();
    }

  </script>

  <?php require('inc/footer.php'); ?>

</body>
</html>