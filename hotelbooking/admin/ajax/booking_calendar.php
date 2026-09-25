<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  date_default_timezone_set("Asia/Ho_Chi_Minh");
  adminLogin();

  if(isset($_POST['get_calendar']))
  {
    $frm_data = filteration($_POST);

    $month = intval($frm_data['month']);
    $year = intval($frm_data['year']);

    if($month < 1 || $month > 12) $month = date('n');
    if($year < 2000 || $year > 2099) $year = date('Y');

    // Get total active rooms in hotel
    $room_res = selectAll('rooms');
    $total_rooms = 0;
    while($r = mysqli_fetch_assoc($room_res)){
      if($r['status'] == 1 && $r['removed'] == 0) $total_rooms++;
    }
    if($total_rooms == 0) $total_rooms = 1; // avoid divide by zero

    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $first_day_timestamp = strtotime("$year-$month-01");
    $first_weekday = date('N', $first_day_timestamp); // 1 (Mon) to 7 (Sun)

    $calendar_days = [];
    $total_month_booked_nights = 0;

    for($d = 1; $d <= $days_in_month; $d++){
      $date_str = sprintf("%04d-%02d-%02d", $year, $month, $d);

      // Query bookings overlapping this date
      $query = "SELECT COUNT(*) as `cnt` FROM `booking_order` 
        WHERE `booking_status`='booked' 
        AND `check_in` <= ? AND `check_out` > ?";
      $res = select($query, [$date_str, $date_str], 'ss');
      $row = mysqli_fetch_assoc($res);
      $booked_cnt = intval($row['cnt']);

      $total_month_booked_nights += $booked_cnt;

      $avail_cnt = max(0, $total_rooms - $booked_cnt);
      $occ_rate = round(($booked_cnt / $total_rooms) * 100);

      $badge_class = 'bg-success';
      $status_text = 'Còn phòng';

      if($occ_rate >= 100){
        $badge_class = 'bg-danger';
        $status_text = 'Hết phòng';
      } else if($occ_rate >= 50){
        $badge_class = 'bg-warning text-dark';
        $status_text = 'Công suất cao';
      }

      $calendar_days[] = [
        'day' => $d,
        'date' => $date_str,
        'booked' => $booked_cnt,
        'available' => $avail_cnt,
        'rate' => $occ_rate,
        'badge_class' => $badge_class,
        'status_text' => $status_text
      ];
    }

    $avg_occupancy = round($total_month_booked_nights / ($total_rooms * $days_in_month) * 100);

    echo json_encode([
      'status' => 'success',
      'month' => $month,
      'year' => $year,
      'days_in_month' => $days_in_month,
      'first_weekday' => $first_weekday,
      'total_rooms' => $total_rooms,
      'total_month_booked_nights' => $total_month_booked_nights,
      'avg_occupancy' => $avg_occupancy,
      'days' => $calendar_days
    ]);
  }

  if(isset($_POST['get_day_bookings']))
  {
    $frm_data = filteration($_POST);
    $date_str = $frm_data['date'];

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE bo.booking_status='booked' 
      AND bo.check_in <= ? AND bo.check_out > ?
      ORDER BY bo.booking_id DESC";

    $res = select($query, [$date_str, $date_str], 'ss');

    if(mysqli_num_rows($res) == 0){
      echo "<tr><td colspan='6' class='py-4 text-secondary fs-6'>Không có đơn đặt phòng nào đang hoạt động vào ngày " . date("d/m/Y", strtotime($date_str)) . "!</td></tr>";
      exit;
    }

    $i = 1;
    $table_data = "";

    while($data = mysqli_fetch_assoc($res))
    {
      $checkin = date("d/m/Y", strtotime($data['check_in']));
      $checkout = date("d/m/Y", strtotime($data['check_out']));
      $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');

      $arrival_badge = ($data['arrival'] == 1) 
        ? "<span class='badge bg-success rounded-pill px-2 py-1'><i class='bi bi-house-check me-1'></i>Đã nhận P.{$data['room_no']}</span>" 
        : "<span class='badge bg-warning text-dark rounded-pill px-2 py-1'><i class='bi bi-hourglass-split me-1'></i>Chưa nhận phòng</span>";

      $table_data .= "
        <tr>
          <td>$i</td>
          <td class='text-start'>
            <a href='javascript:void(0)' onclick='view_booking_modal($data[booking_id])' class='fw-bold text-teal text-decoration-underline'>
              $data[order_id]
            </a>
            <br>
            <b>Họ tên:</b> <a href='javascript:void(0)' onclick='view_booking_modal($data[booking_id])' class='text-dark fw-semibold'>$data[user_name]</a>
            <br>
            <b>SĐT:</b> $data[phonenum]
          </td>
          <td class='text-start'>
            <b>Tên phòng:</b> $data[room_name]
            <br>
            <b>Số phòng xếp:</b> <span class='badge bg-dark'>".($data['room_no'] ? $data['room_no'] : 'Chưa xếp')."</span>
          </td>
          <td class='text-start small'>
            <b>Nhận:</b> $checkin <br>
            <b>Trả:</b> $checkout
          </td>
          <td>$arrival_badge</td>
          <td>
            <button type='button' onclick='open_guest_modal($data[booking_id], \"$data[order_id]\")' class='btn btn-outline-teal btn-sm shadow-none rounded-3 py-1 px-2 me-1' title='Khai báo lưu trú CCCD'>
              <i class='bi bi-person-vcard me-1'></i>Khai báo lưu trú
            </button>
            <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-outline-dark btn-sm shadow-none rounded-3 py-1 px-2' title='Tải hóa đơn'>
              <i class='bi bi-file-earmark-pdf'></i>
            </a>
          </td>
        </tr>
      ";
      $i++;
    }

    echo $table_data;
  }

  if(isset($_POST['get_booking_details']))
  {
    $frm_data = filteration($_POST);

    $query = "SELECT bo.*, bd.*, uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      LEFT JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE bo.booking_id = ?";

    $res = select($query, [$frm_data['booking_id']], 'i');
    if(mysqli_num_rows($res) == 0){
      echo "<div class='text-danger text-center p-3'>Không tìm thấy chi tiết đơn đặt phòng này!</div>";
      exit;
    }

    $data = mysqli_fetch_assoc($res);

    $date = date("d/m/Y H:i", strtotime($data['datentime']));
    $checkin = date("d/m/Y", strtotime($data['check_in']));
    $checkout = date("d/m/Y", strtotime($data['check_out']));
    $price_formatted = number_format($data['price'] * 1000, 0, ',', '.');
    $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');

    $status_badge = "<span class='badge bg-success rounded-pill px-3 py-2'>ĐÃ ĐẶT THÀNH CÔNG</span>";
    if($data['booking_status'] == 'cancelled'){
      $status_badge = "<span class='badge bg-danger rounded-pill px-3 py-2'>ĐÃ HỦY ĐƠN</span>";
    } else if($data['booking_status'] == 'payment failed'){
      $status_badge = "<span class='badge bg-secondary rounded-pill px-3 py-2'>THANH TOÁN THẤT BẠI</span>";
    }

    $room_no = !empty($data['room_no']) ? "<b class='text-teal fs-6'>P.{$data['room_no']}</b>" : "<span class='badge bg-warning text-dark'>Chưa xếp phòng</span>";

    // Guests query
    $g_res = select("SELECT * FROM `booking_guests` WHERE `booking_id`=? ORDER BY `is_leader` DESC, `id` ASC", [$data['booking_id']], 'i');
    $guest_list = "";
    if(mysqli_num_rows($g_res) == 0){
      $guest_list = "<tr><td colspan='5' class='text-muted small py-2'>Chưa có thông tin khai báo tạm trú cho đơn này.</td></tr>";
    } else {
      $gi = 1;
      while($g = mysqli_fetch_assoc($g_res)){
        $l_tag = ($g['is_leader']==1) ? "<span class='badge bg-warning text-dark me-1'>Trưởng đoàn</span>" : "";
        $g_dob = !empty($g['dob']) ? date("d/m/Y", strtotime($g['dob'])) : '-';
        $guest_list .= "
          <tr>
            <td>$gi</td>
            <td class='text-start fw-bold'>$g[name] $l_tag</td>
            <td><code>$g[id_card]</code></td>
            <td>$g_dob / $g[gender]</td>
            <td class='text-start small'>$g[address]</td>
          </tr>
        ";
        $gi++;
      }
    }

    echo "
      <div class='row g-3'>
        <div class='col-md-6 border-end'>
          <h6 class='fw-bold text-teal mb-3'><i class='bi bi-person-lines-fill me-2'></i>THÔNG TIN KHÁCH HÀNG DỰ ÁN</h6>
          <table class='table table-sm table-bordered'>
            <tr><td class='bg-light fw-semibold' style='width:40%;'>Họ và tên:</td><td>$data[user_name]</td></tr>
            <tr><td class='bg-light fw-semibold'>Số điện thoại:</td><td>$data[phonenum]</td></tr>
            <tr><td class='bg-light fw-semibold'>Email liên hệ:</td><td>".($data['email']?$data['email']:'Chưa cập nhật')."</td></tr>
            <tr><td class='bg-light fw-semibold'>Địa chỉ:</td><td>$data[address]</td></tr>
          </table>

          <h6 class='fw-bold text-teal mt-4 mb-3'><i class='bi bi-receipt me-2'></i>THÔNG TIN ĐƠN & THANH TOÁN</h6>
          <table class='table table-sm table-bordered'>
            <tr><td class='bg-light fw-semibold' style='width:40%;'>Mã đơn hàng:</td><td><code class='fw-bold text-dark fs-6'>$data[order_id]</code></td></tr>
            <tr><td class='bg-light fw-semibold'>Mã giao dịch:</td><td>".($data['trans_id']?$data['trans_id']:'TẠI QUẦY KHÁCH SẠN')."</td></tr>
            <tr><td class='bg-light fw-semibold'>Trạng thái:</td><td>$status_badge</td></tr>
            <tr><td class='bg-light fw-semibold'>Thời gian đặt:</td><td>$date</td></tr>
            <tr><td class='bg-light fw-semibold'>Tổng tiền thanh toán:</td><td><span class='fw-bold text-teal fs-5'>$total_formatted VNĐ</span></td></tr>
          </table>
        </div>

        <div class='col-md-6'>
          <h6 class='fw-bold text-teal mb-3'><i class='bi bi-door-open-fill me-2'></i>THÔNG TIN PHÒNG NGHỈ & XẾP PHÒNG</h6>
          <table class='table table-sm table-bordered'>
            <tr><td class='bg-light fw-semibold' style='width:40%;'>Loại phòng:</td><td><b>$data[room_name]</b></td></tr>
            <tr><td class='bg-light fw-semibold'>Giá phòng/đêm:</td><td>$price_formatted VNĐ</td></tr>
            <tr><td class='bg-light fw-semibold'>Số phòng xếp:</td><td>$room_no</td></tr>
            <tr><td class='bg-light fw-semibold'>Ngày nhận phòng:</td><td>$checkin</td></tr>
            <tr><td class='bg-light fw-semibold'>Ngày trả phòng:</td><td>$checkout</td></tr>
          </table>

          <h6 class='fw-bold text-teal mt-4 mb-2 d-flex justify-content-between align-items-center'>
            <span><i class='bi bi-people-fill me-2'></i>DANH SÁCH THÀNH VIÊN TẠM TRÚ</span>
            <button type='button' onclick='open_guest_modal($data[booking_id], \"$data[order_id]\")' class='btn btn-teal btn-sm shadow-none rounded-3 py-1 px-2' style='background-color:#03989e;color:#fff;'>
              <i class='bi bi-plus-circle me-1'></i>Thêm/Quét CCCD
            </button>
          </h6>
          <div class='table-responsive'>
            <table class='table table-sm table-hover table-bordered text-center align-middle'>
              <thead>
                <tr class='bg-dark text-white'>
                  <th>STT</th>
                  <th>Họ Tên</th>
                  <th>Số CCCD</th>
                  <th>Sinh / GT</th>
                  <th>Quê quán</th>
                </tr>
              </thead>
              <tbody>
                $guest_list
              </tbody>
            </table>
          </div>
        </div>
      </div>
    ";
  }

?>
