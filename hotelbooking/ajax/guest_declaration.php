<?php 

  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');

  date_default_timezone_set("Asia/Ho_Chi_Minh");
  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)){
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thao tác!']);
    exit;
  }

  // Helper to check if booking belongs to current user
  function checkUserBooking($booking_id, $user_id) {
    $res = select("SELECT `booking_id` FROM `booking_order` WHERE `booking_id`=? AND `user_id`=?", [$booking_id, $user_id], 'ii');
    return mysqli_num_rows($res) > 0;
  }

  if(isset($_POST['get_guests']))
  {
    $frm_data = filteration($_POST);

    if(!checkUserBooking($frm_data['booking_id'], $_SESSION['uId'])){
      echo "<tr><td colspan='6' class='text-danger py-3'>Không có quyền truy cập đơn hàng này!</td></tr>";
      exit;
    }

    $res = select("SELECT * FROM `booking_guests` WHERE `booking_id`=? ORDER BY `is_leader` DESC, `id` ASC", [$frm_data['booking_id']], 'i');

    $table_data = "";
    $i = 1;

    if(mysqli_num_rows($res) == 0){
      echo "<tr><td colspan='6' class='py-3 text-secondary'>Chưa có thông tin thành viên đi cùng. Vui lòng bấm thêm mới hoặc quét mã QR CCCD.</td></tr>";
      exit;
    }

    while($data = mysqli_fetch_assoc($res))
    {
      $dob = !empty($data['dob']) ? date("d/m/Y", strtotime($data['dob'])) : 'Chưa nhập';
      $leader_badge = ($data['is_leader'] == 1) 
        ? "<span class='badge bg-warning text-dark me-1'><i class='bi bi-star-fill me-1'></i>Trưởng đoàn</span>" 
        : "<span class='badge bg-secondary me-1'>Đi cùng</span>";

      $table_data .= "
        <tr>
          <td>$i</td>
          <td class='text-start fw-bold'>
            $data[name] <br>
            $leader_badge
          </td>
          <td><code class='text-dark fw-bold'>$data[id_card]</code></td>
          <td>$dob</td>
          <td>$data[gender]</td>
          <td class='text-center'>
            <button type='button' onclick='user_delete_guest($data[id], $data[booking_id])' class='btn btn-outline-danger btn-sm shadow-none rounded-2 py-1 px-2' title='Xóa'>
              <i class='bi bi-trash me-1'></i>Xóa
            </button>
          </td>
        </tr>
      ";
      $i++;
    }

    echo $table_data;
  }

  if(isset($_POST['add_guest']))
  {
    $frm_data = filteration($_POST);

    if(!checkUserBooking($frm_data['booking_id'], $_SESSION['uId'])){
      echo 0;
      exit;
    }

    $dob_val = !empty($frm_data['dob']) ? $frm_data['dob'] : NULL;
    $is_leader = isset($_POST['is_leader']) ? 1 : 0;

    $query = "INSERT INTO `booking_guests` (`booking_id`, `name`, `id_card`, `dob`, `gender`, `nationality`, `phonenum`, `address`, `is_leader`) VALUES (?,?,?,?,?,?,?,?,?)";
    $values = [
      $frm_data['booking_id'],
      $frm_data['name'],
      $frm_data['id_card'],
      $dob_val,
      $frm_data['gender'],
      $frm_data['nationality'],
      $frm_data['phonenum'],
      $frm_data['address'],
      $is_leader
    ];

    $res = insert($query, $values, 'isssssssi');
    echo $res;
  }

  if(isset($_POST['delete_guest']))
  {
    $frm_data = filteration($_POST);
    if(!checkUserBooking($frm_data['booking_id'], $_SESSION['uId'])){
      echo 0;
      exit;
    }

    $res = delete("DELETE FROM `booking_guests` WHERE `id`=? AND `booking_id`=?", [$frm_data['id'], $frm_data['booking_id']], 'ii');
    echo $res;
  }

  if(isset($_POST['parse_cccd_qr']))
  {
    $qr_data = trim($_POST['qr_string']);
    $parts = explode('|', $qr_data);

    if(count($parts) >= 6) {
      $id_card = trim($parts[0]);
      $name = trim($parts[2]);
      $dob_raw = trim($parts[3]);
      $gender = trim($parts[4]);
      $address = trim($parts[5]);

      $dob_formatted = "";
      if(strlen($dob_raw) == 8) {
        $day = substr($dob_raw, 0, 2);
        $month = substr($dob_raw, 2, 2);
        $year = substr($dob_raw, 4, 4);
        $dob_formatted = "$year-$month-$day";
      }

      echo json_encode([
        'status' => 'success',
        'data' => [
          'id_card' => $id_card,
          'name' => $name,
          'dob' => $dob_formatted,
          'gender' => $gender,
          'address' => $address,
          'nationality' => 'Việt Nam'
        ]
      ]);
    }
    else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Chuỗi mã QR không đúng định dạng thẻ CCCD gắn chip Việt Nam!'
      ]);
    }
  }

?>
