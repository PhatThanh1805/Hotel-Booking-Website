<?php 

  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');
  date_default_timezone_set("Asia/Ho_Chi_Minh");

  session_start();

  if(isset($_GET['fetch_rooms']))
  {
    // check availability data decode
    $chk_avail = json_decode($_GET['chk_avail'],true);
    
    // checkin and checkout filter validations
    if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
    {
      $today_date = new DateTime(date("Y-m-d"));
      $checkin_date = new DateTime($chk_avail['checkin']);
      $checkout_date = new DateTime($chk_avail['checkout']);
  
      if($checkin_date == $checkout_date){
        echo"<h3 class='text-center text-danger py-4 fw-bold'>Ngày nhận phòng và trả phòng không được trùng nhau!</h3>";
        exit;
      }
      else if($checkout_date < $checkin_date){
        echo"<h3 class='text-center text-danger py-4 fw-bold'>Ngày trả phòng phải sau ngày nhận phòng!</h3>";
        exit;
      }
      else if($checkin_date < $today_date){
        echo"<h3 class='text-center text-danger py-4 fw-bold'>Ngày nhận phòng không được ở quá khứ!</h3>";
        exit;
      }
    }

    // guests data decode
    $guests = json_decode($_GET['guests'],true);
    $adults = ($guests['adults']!='') ? $guests['adults'] : 0;
    $children = ($guests['children']!='') ? $guests['children'] : 0;

    // facilities data decode
    $facility_list = json_decode($_GET['facility_list'],true);

    // count no. of rooms and ouput variable to store room cards
    $count_rooms = 0;
    $output = "";


    // fetching settings table to check website is shutdown or not
    $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con,$settings_q));


    // query for room cards with guests filter
    $room_res = select("SELECT * FROM `rooms` WHERE `adult`>=? AND `children`>=? AND `status`=? AND `removed`=?",[$adults,$children,1,0],'iiii');

    while($room_data = mysqli_fetch_assoc($room_res))
    {
      // check availability filter
      if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
      {
        $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
          WHERE booking_status=? AND room_id=?
          AND check_out > ? AND check_in < ?";

        $values = ['booked',$room_data['id'],$chk_avail['checkin'],$chk_avail['checkout']];
        $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));

        if(($room_data['quantity']-$tb_fetch['total_bookings'])==0){
          continue;
        }
      }

      // get facilities of room with filters
      $fac_count=0;

      $fac_q = mysqli_query($con,"SELECT f.name, f.id FROM `facilities` f 
        INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
        WHERE rfac.room_id = '$room_data[id]'");

      $facilities_data = "";
      while($fac_row = mysqli_fetch_assoc($fac_q))
      {
        if( in_array($fac_row['id'],$facility_list['facilities']) ){
          $fac_count++;
        }

        $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1 border'>
          $fac_row[name]
        </span>";
      }

      if(count($facility_list['facilities'])!=$fac_count){
        continue;
      }


      // Đặc điểm nổi bật phòng
      $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f 
        INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
        WHERE rfea.room_id = '$room_data[id]'");

      $features_data = "";
      while($fea_row = mysqli_fetch_assoc($fea_q)){
        $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1 border'>
          $fea_row[name]
        </span>";
      }


      // Ảnh thumbnail
      $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
      $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
        WHERE `room_id`='$room_data[id]' 
        AND `thumb`='1'");

      if(mysqli_num_rows($thumb_q)>0){
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
      }

      $book_btn = "";

      if(!$settings_r['shutdown']){
        $login=0;
        if(isset($_SESSION['login']) && $_SESSION['login']==true){
          $login=1;
        }

        $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2 rounded-3 py-2 fw-semibold'><i class='bi bi-cart-check me-1'></i>Đặt phòng ngay</button>";
      }

      $price_formatted = number_format($room_data['price'] * 1000, 0, ',', '.');

      // print room card
      $output.="
        <div class='card mb-4 border-0 shadow-sm rounded-4 overflow-hidden pop'>
          <div class='row g-0 p-3 align-items-center'>
            <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
              <img src='$room_thumb' class='img-fluid rounded-3 w-100' style='height: 210px; object-fit: cover;'>
            </div>
            <div class='col-md-5 px-lg-4 px-md-3 px-0'>
              <h5 class='mb-2 fw-bold text-dark'>$room_data[name]</h5>
              <div class='features mb-2'>
                <h6 class='mb-1 fw-bold text-secondary' style='font-size: 13px;'>Đặc điểm:</h6>
                $features_data
              </div>
              <div class='facilities mb-2'>
                <h6 class='mb-1 fw-bold text-secondary' style='font-size: 13px;'>Tiện ích:</h6>
                $facilities_data
              </div>
              <div class='guests mb-2'>
                <h6 class='mb-1 fw-bold text-secondary' style='font-size: 13px;'>Sức chứa:</h6>
                <span class='badge rounded-pill bg-light text-dark text-wrap border me-1'>
                  <i class='bi bi-person me-1'></i>$room_data[adult] Người lớn
                </span>
                <span class='badge rounded-pill bg-light text-dark text-wrap border'>
                  <i class='bi bi-emoji-smile me-1'></i>$room_data[children] Trẻ em
                </span>
              </div>
            </div>
            <div class='col-md-2 mt-lg-0 mt-md-0 mt-3 text-center border-start-lg ps-lg-3'>
              <h5 class='mb-1 text-teal fw-bold'>$price_formatted VNĐ</h5>
              <small class='text-muted d-block mb-3'>mỗi đêm</small>
              $book_btn
              <a href='room_details.php?id=$room_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none rounded-3 py-2'><i class='bi bi-eye me-1'></i>Chi tiết</a>
            </div>
          </div>
        </div>
      ";

      $count_rooms++;
    }

    if($count_rooms>0){
      echo $output;
    }
    else{
      echo"<h3 class='text-center text-secondary py-5'>Không tìm thấy phòng nghỉ phù hợp với yêu cầu!</h3>";
    }

  }

?>