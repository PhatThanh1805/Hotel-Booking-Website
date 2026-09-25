<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['add_room']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "INSERT INTO `rooms` (`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc']];

    if(insert($q1,$values,'siiiiis')){
      $flag = 1;
    }
    
    $room_id = mysqli_insert_id($con);

    $q2 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$room_id,$f);
        mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$room_id,$f);
        mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }
    
    if($flag){
      echo 1;
    }
    else{
      echo 0;
    }


  }


  if(isset($_POST['get_room_stats']))
  {
    $total_res = select("SELECT COUNT(*) AS `total` FROM `rooms` WHERE `removed`=?",[0],'i');
    $avail_res = select("SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=? AND `status`=?",[0,1],'ii');
    $clean_res = select("SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=? AND `status`=?",[0,2],'ii');
    $occup_res = select("SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=? AND `status`=?",[0,3],'ii');
    $bookd_res = select("SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=? AND `status`=?",[0,4],'ii');
    $maint_res = select("SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=? AND `status`=?",[0,0],'ii');

    $stats = [
      'total' => mysqli_fetch_assoc($total_res)['total'],
      'available' => mysqli_fetch_assoc($avail_res)['cnt'],
      'cleaning' => mysqli_fetch_assoc($clean_res)['cnt'],
      'occupied' => mysqli_fetch_assoc($occup_res)['cnt'],
      'booked' => mysqli_fetch_assoc($bookd_res)['cnt'],
      'maintenance' => mysqli_fetch_assoc($maint_res)['cnt']
    ];

    echo json_encode($stats);
    exit;
  }

  if(isset($_POST['get_all_rooms']))
  {
    $status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : 'all';
    $search_query = isset($_POST['search_query']) ? trim($_POST['search_query']) : '';

    $sql = "SELECT * FROM `rooms` WHERE `removed`=0";
    $params = [];
    $types = "";

    if($status_filter !== 'all' && $status_filter !== '') {
      $sql .= " AND `status`=?";
      $params[] = (int)$status_filter;
      $types .= "i";
    }

    if($search_query !== '') {
      $sql .= " AND `name` LIKE ?";
      $params[] = "%$search_query%";
      $types .= "s";
    }

    $sql .= " ORDER BY `id` DESC";

    if(!empty($params)) {
      $res = select($sql, $params, $types);
    } else {
      $res = mysqli_query($con, "SELECT * FROM `rooms` WHERE `removed`=0 ORDER BY `id` DESC");
    }

    $i = 1;
    $data = "";

    if(mysqli_num_rows($res) == 0) {
      echo "<tr><td colspan='8' class='text-center text-muted py-4 fw-semibold'><i class='bi bi-info-circle me-1'></i>Không tìm thấy phòng nào phù hợp!</td></tr>";
      exit;
    }

    while($row = mysqli_fetch_assoc($res))
    {
      // 4 + 1 Trạng thái phòng chi tiết Badges
      $status_badge = "";
      switch($row['status']) {
        case 1:
          $status_badge = "<span class='badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold'><i class='bi bi-check-circle-fill me-1'></i>Phòng Đang Trống</span>";
          break;
        case 2:
          $status_badge = "<span class='badge bg-warning-subtle text-dark border border-warning px-3 py-2 rounded-pill fw-semibold'><i class='bi bi-stars me-1'></i>Đang Dọn Dẹp</span>";
          break;
        case 3:
          $status_badge = "<span class='badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold'><i class='bi bi-person-fill-check me-1'></i>Đang Có Khách</span>";
          break;
        case 4:
          $status_badge = "<span class='badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill fw-semibold'><i class='bi bi-calendar-check-fill me-1'></i>Đã Được Đặt</span>";
          break;
        case 0:
        default:
          $status_badge = "<span class='badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2 rounded-pill fw-semibold'><i class='bi bi-slash-circle-fill me-1'></i>Tạm Dừng</span>";
          break;
      }

      $price_formatted = number_format($row['price'] * 1000, 0, ',', '.') . " VNĐ";

      $data.="
        <tr class='align-middle'>
          <td class='fw-bold text-secondary'>$i</td>
          <td class='fw-bold text-dark text-start ps-3'>
            <div>$row[name]</div>
            <small class='text-muted fw-normal'>Mã phòng: #R-$row[id]</small>
          </td>
          <td><span class='badge bg-light text-dark border px-2 py-1'>$row[area] m²</span></td>
          <td>
            <span class='badge rounded-pill bg-light text-dark border me-1 mb-1'>
              <i class='bi bi-person me-1 text-primary'></i>Lớn: $row[adult]
            </span><br>
            <span class='badge rounded-pill bg-light text-dark border'>
              <i class='bi bi-emoji-smile me-1 text-info'></i>Trẻ: $row[children]
            </span>
          </td>
          <td class='fw-bold text-teal'>$price_formatted</td>
          <td class='fw-bold fs-6'>$row[quantity]</td>
          <td>$status_badge</td>
          <td>
            <div class='d-flex align-items-center justify-content-center gap-1'>
              <div class='dropdown d-inline-block'>
                <button class='btn btn-sm btn-outline-secondary dropdown-toggle shadow-none rounded-3 py-1 px-2' type='button' data-bs-toggle='dropdown' aria-expanded='false' title='Chuyển trạng thái phòng'>
                  <i class='bi bi-arrow-repeat me-1'></i>Đổi Trạng Thái
                </button>
                <ul class='dropdown-menu dropdown-menu-end shadow border-0 rounded-3 fs-7'>
                  <li><h6 class='dropdown-header text-uppercase fw-bold text-teal'>Cập nhật 4 trạng thái</h6></li>
                  <li><a class='dropdown-item fw-semibold text-success py-2' href='javascript:void(0)' onclick='change_status($row[id], 1)'><i class='bi bi-check-circle-fill me-2'></i>1. Phòng Đang Trống (Available)</a></li>
                  <li><a class='dropdown-item fw-semibold text-warning py-2' href='javascript:void(0)' onclick='change_status($row[id], 2)'><i class='bi bi-stars me-2'></i>2. Đang Dọn Dẹp (Cleaning)</a></li>
                  <li><a class='dropdown-item fw-semibold text-danger py-2' href='javascript:void(0)' onclick='change_status($row[id], 3)'><i class='bi bi-person-fill-check me-2'></i>3. Đang Có Khách (Occupied)</a></li>
                  <li><a class='dropdown-item fw-semibold text-primary py-2' href='javascript:void(0)' onclick='change_status($row[id], 4)'><i class='bi bi-calendar-check-fill me-2'></i>4. Đã Được Đặt (Booked)</a></li>
                  <li><hr class='dropdown-divider'></li>
                  <li><a class='dropdown-item fw-semibold text-secondary py-2' href='javascript:void(0)' onclick='change_status($row[id], 0)'><i class='bi bi-slash-circle-fill me-2'></i>0. Tạm Dừng / Bảo Trì</a></li>
                </ul>
              </div>

              <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm rounded-3' title='Sửa phòng' data-bs-toggle='modal' data-bs-target='#edit-room'>
                <i class='bi bi-pencil-square'></i>
              </button>
              <button type='button' onclick=\"room_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm rounded-3 text-white' title='Quản lý ảnh' data-bs-toggle='modal' data-bs-target='#room-images'>
                <i class='bi bi-images'></i>
              </button>
              <button type='button' onclick='remove_room($row[id])' class='btn btn-danger shadow-none btn-sm rounded-3' title='Xóa phòng'>
                <i class='bi bi-trash'></i>
              </button>
            </div>
          </td>
        </tr>
      ";
      $i++;
    }

    echo $data;
  }

  if(isset($_POST['get_room']))
  {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$frm_data['get_room']],'i');
    $res2 = select("SELECT * FROM `room_features` WHERE `room_id`=?",[$frm_data['get_room']],'i');
    $res3 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?",[$frm_data['get_room']],'i');

    $roomdata = mysqli_fetch_assoc($res1);
    $features = [];
    $facilities = [];

    if(mysqli_num_rows($res2)>0)
    {
      while($row = mysqli_fetch_assoc($res2)){
        array_push($features,$row['features_id']);
      }
    }

    if(mysqli_num_rows($res3)>0)
    {
      while($row = mysqli_fetch_assoc($res3)){
        array_push($facilities,$row['facilities_id']);
      }
    }

    $data = ["roomdata" => $roomdata, "features" => $features, "facilities" => $facilities];
    
    $data = json_encode($data);

    echo $data;

  }

  if(isset($_POST['edit_room']))
  {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
      `adult`=?,`children`=?,`description`=? WHERE `id`=?";
    $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['adult'],$frm_data['children'],$frm_data['desc'],$frm_data['room_id']];
    
    if(update($q1,$values,'siiiiisi')){
      $flag = 1;
    }

    $del_features = delete("DELETE FROM `room_features` WHERE `room_id`=?", [$frm_data['room_id']],'i');
    $del_facilities = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']],'i');

    if(!($del_facilities && $del_features)){
      $flag = 0;
    }

    $q2 = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q2))
    {
      foreach($facilities as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$f);
        mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }

    
    $q3 = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?,?)";
    if($stmt = mysqli_prepare($con,$q3))
    {
      foreach($features as $f){
        mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$f);
        mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
    }
    else{
      $flag = 0;
      die('query cannot be prepared - insert');
    }
    
    if($flag){
      echo 1;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['change_status']))
  {
    $frm_data = filteration($_POST);

    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['val'], $frm_data['room_id']];

    if(update($q, $v, 'ii')){
      echo 1;
    }
    else{
      echo 0;
    }
  }

  if(isset($_POST['toggle_status']))
  {
    $frm_data = filteration($_POST);

    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'],$frm_data['toggle_status']];

    if(update($q,$v,'ii')){
      echo 1;
    }
    else{
      echo 0;
    }
  }

  if(isset($_POST['add_image']))
  {
    $frm_data = filteration($_POST);

    $img_r = uploadImage($_FILES['image'],ROOMS_FOLDER);

    if($img_r == 'inv_img'){
      echo $img_r;
    }
    else if($img_r == 'inv_size'){
      echo $img_r;
    }
    else if($img_r == 'upd_failed'){
      echo $img_r;
    }
    else{
      $q = "INSERT INTO `room_images`(`room_id`, `image`) VALUES (?,?)";
      $values = [$frm_data['room_id'],$img_r];
      $res = insert($q,$values,'is');
      echo $res;
    }
  }

  if(isset($_POST['get_room_images']))
  {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm_data['get_room_images']],'i');

    $path = ROOMS_IMG_PATH;

    while($row = mysqli_fetch_assoc($res))
    {
      if($row['thumb']==1){
        $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
      }
      else{
        $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[room_id])' class='btn btn-secondary shadow-none'>
          <i class='bi bi-check-lg'></i>
        </button>";
      }

      echo<<<data
        <tr class='align-middle'>
          <td><img src='$path$row[image]' class='img-fluid'></td>
          <td>$thumb_btn</td>
          <td>
            <button onclick='rem_image($row[sr_no],$row[room_id])' class='btn btn-danger shadow-none'>
              <i class='bi bi-trash'></i>
            </button>
          </td>
        </tr>
      data;
    }

  }

  if(isset($_POST['rem_image']))
  {
    $frm_data = filteration($_POST);

    $values = [$frm_data['image_id'],$frm_data['room_id']];

    $pre_q = "SELECT * FROM `room_images` WHERE `sr_no`=? AND `room_id`=?";
    $res = select($pre_q,$values,'ii');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['image'],ROOMS_FOLDER)){
      $q = "DELETE FROM `room_images` WHERE `sr_no`=? AND `room_id`=?";
      $res = delete($q,$values,'ii');
      echo $res;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['thumb_image']))
  {
    $frm_data = filteration($_POST);

    $pre_q = "UPDATE `room_images` SET `thumb`=? WHERE `room_id`=?";
    $pre_v = [0,$frm_data['room_id']];
    $pre_res = update($pre_q,$pre_v,'ii');

    $q = "UPDATE `room_images` SET `thumb`=? WHERE `sr_no`=? AND `room_id`=?";
    $v = [1,$frm_data['image_id'],$frm_data['room_id']];
    $res = update($q,$v,'iii');

    echo $res;

  }

  if(isset($_POST['remove_room']))
  {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm_data['room_id']],'i');

    while($row = mysqli_fetch_assoc($res1)){
      deleteImage($row['image'],ROOMS_FOLDER);
    }

    $res2 = delete("DELETE FROM `room_images` WHERE `room_id`=?",[$frm_data['room_id']],'i');
    $res3 = delete("DELETE FROM `room_features` WHERE `room_id`=?",[$frm_data['room_id']],'i');
    $res4 = delete("DELETE FROM `room_facilities` WHERE `room_id`=?",[$frm_data['room_id']],'i');
    $res5 = update("UPDATE `rooms` SET `removed`=? WHERE `id`=?",[1,$frm_data['room_id']],'ii');

    if($res2 || $res3 || $res4 || $res5){
      echo 1;
    }
    else{
      echo 0;
    }

  }

?>