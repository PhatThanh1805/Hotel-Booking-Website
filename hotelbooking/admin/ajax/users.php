<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['get_users']))
  {
    $res = selectAll('user_cred');    
    $i=1;
    $path = USERS_IMG_PATH;

    $data = "";

    while($row = mysqli_fetch_assoc($res))
    {
      $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm rounded-3' title='Xóa tài khoản chưa xác thực'>
        <i class='bi bi-trash me-1'></i>Xóa
      </button>";

      $verified = "<span class='badge bg-warning text-dark rounded-pill px-2 py-1'><i class='bi bi-x-circle me-1'></i>Chưa xác thực</span>";

      if($row['is_verified']){
        $verified = "<span class='badge bg-success rounded-pill px-2 py-1'><i class='bi bi-check-circle me-1'></i>Đã xác thực</span>";
        $del_btn = ""; 
      }

      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-success btn-sm shadow-none rounded-3 px-3'>
        <i class='bi bi-check-lg me-1'></i>Hoạt động
      </button>";

      if(!$row['status']){
        $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none rounded-3 px-3'>
          <i class='bi bi-lock-fill me-1'></i>Đã khóa
        </button>";
      }

      $user_role = isset($row['role']) ? $row['role'] : 'customer';
      if($user_role == 'staff'){
        $role_badge = "<button onclick=\"toggle_role($row[id],'customer')\" class='btn btn-primary btn-sm shadow-none rounded-3 me-1'>
          <i class='bi bi-person-badge-fill me-1'></i>Nhân viên
        </button>";
      } else {
        $role_badge = "<button onclick=\"toggle_role($row[id],'staff')\" class='btn btn-outline-secondary btn-sm shadow-none rounded-3 me-1'>
          <i class='bi bi-person me-1'></i>Khách hàng
        </button>";
      }

      $date = date("d/m/Y",strtotime($row['datentime']));

      $data.="
        <tr>
          <td>$i</td>
          <td>
            <img src='$path$row[profile]' class='rounded-circle border me-2' style='width: 40px; height: 40px; object-fit: cover;'>
            <span class='fw-semibold'>$row[name]</span>
          </td>
          <td>$row[email]</td>
          <td>$row[phonenum]</td>
          <td>$row[address] | $row[pincode]</td>
          <td>".date("d/m/Y", strtotime($row['dob']))."</td>
          <td>$role_badge</td>
          <td>$verified</td>
          <td>$status</td>
          <td>$date</td>
          <td>$del_btn</td>
        </tr>
      ";
      $i++;
    }

    echo $data;
  }

  if(isset($_POST['toggle_status']))
  {
    $frm_data = filteration($_POST);

    $q = "UPDATE `user_cred` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'],$frm_data['toggle_status']];

    if(update($q,$v,'ii')){
      echo 1;
    }
    else{
      echo 0;
    }
  }

  if(isset($_POST['toggle_role']))
  {
    $frm_data = filteration($_POST);

    $q = "UPDATE `user_cred` SET `role`=? WHERE `id`=?";
    $v = [$frm_data['role_val'], $frm_data['toggle_role']];

    if(update($q,$v,'si')){
      echo 1;
    }
    else{
      echo 0;
    }
  }

  if(isset($_POST['remove_user']))
  {
    $frm_data = filteration($_POST);

    $res = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?",[$frm_data['user_id'],0],'ii');

    if($res){
      echo 1;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['search_user']))
  {
    $frm_data = filteration($_POST);

    $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ? OR `email` LIKE ? OR `phonenum` LIKE ?";

    $res = select($query,["%$frm_data[name]%","%$frm_data[name]%","%$frm_data[name]%"],'sss');    
    $i=1;
    $path = USERS_IMG_PATH;

    $data = "";

    while($row = mysqli_fetch_assoc($res))
    {
      $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm rounded-3' title='Xóa tài khoản chưa xác thực'>
        <i class='bi bi-trash me-1'></i>Xóa
      </button>";

      $verified = "<span class='badge bg-warning text-dark rounded-pill px-2 py-1'><i class='bi bi-x-circle me-1'></i>Chưa xác thực</span>";

      if($row['is_verified']){
        $verified = "<span class='badge bg-success rounded-pill px-2 py-1'><i class='bi bi-check-circle me-1'></i>Đã xác thực</span>";
        $del_btn = ""; 
      }

      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-success btn-sm shadow-none rounded-3 px-3'>
        <i class='bi bi-check-lg me-1'></i>Hoạt động
      </button>";

      if(!$row['status']){
        $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none rounded-3 px-3'>
          <i class='bi bi-lock-fill me-1'></i>Đã khóa
        </button>";
      }

      $user_role = isset($row['role']) ? $row['role'] : 'customer';
      if($user_role == 'staff'){
        $role_badge = "<button onclick=\"toggle_role($row[id],'customer')\" class='btn btn-primary btn-sm shadow-none rounded-3 me-1'>
          <i class='bi bi-person-badge-fill me-1'></i>Nhân viên
        </button>";
      } else {
        $role_badge = "<button onclick=\"toggle_role($row[id],'staff')\" class='btn btn-outline-secondary btn-sm shadow-none rounded-3 me-1'>
          <i class='bi bi-person me-1'></i>Khách hàng
        </button>";
      }

      $date = date("d/m/Y",strtotime($row['datentime']));

      $data.="
        <tr>
          <td>$i</td>
          <td>
            <img src='$path$row[profile]' class='rounded-circle border me-2' style='width: 40px; height: 40px; object-fit: cover;'>
            <span class='fw-semibold'>$row[name]</span>
          </td>
          <td>$row[email]</td>
          <td>$row[phonenum]</td>
          <td>$row[address] | $row[pincode]</td>
          <td>".date("d/m/Y", strtotime($row['dob']))."</td>
          <td>$role_badge</td>
          <td>$verified</td>
          <td>$status</td>
          <td>$date</td>
          <td>$del_btn</td>
        </tr>
      ";
      $i++;
    }

    echo $data;
  }

?>