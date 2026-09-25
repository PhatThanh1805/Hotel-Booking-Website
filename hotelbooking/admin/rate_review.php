<?php
  require('inc/essentials.php');
  require('inc/db_config.php');
  adminLogin();

  if(isset($_GET['seen']))
  {
    $frm_data = filteration($_GET);

    if($frm_data['seen']=='all'){
      $q = "UPDATE `rating_review` SET `seen`=?";
      $values = [1];
      if(update($q,$values,'i')){
        alert('success','Đã đánh dấu tất cả là đã đọc!');
      }
      else{
        alert('error','Thao tác thất bại!');
      }
    }
    else{
      $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
      $values = [1,$frm_data['seen']];
      if(update($q,$values,'ii')){
        alert('success','Đã đánh dấu là đã đọc!');
      }
      else{
        alert('error','Thao tác thất bại!');
      }
    }
  }

  if(isset($_GET['del']))
  {
    $frm_data = filteration($_GET);

    if($frm_data['del']=='all'){
      $q = "DELETE FROM `rating_review`";
      if(mysqli_query($con,$q)){
        alert('success','Đã xóa toàn bộ đánh giá!');
      }
      else{
        alert('error','Thao tác thất bại!');
      }
    }
    else{
      $q = "DELETE FROM `rating_review` WHERE `sr_no`=?";
      $values = [$frm_data['del']];
      if(delete($q,$values,'i')){
        alert('success','Đã xóa đánh giá thành công!');
      }
      else{
        alert('error','Thao tác thất bại!');
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản Trị Khách Sạn - Đánh Giá & Bình Luận</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="fw-bold text-dark mb-4"><i class="bi bi-star-half me-2 text-warning"></i>QUẢN LÝ ĐÁNH GIÁ & BÌNH LUẬN KHÁCH HÀNG</h3>

        <div class="card border-0 shadow-sm mb-4 rounded-4 pop">
          <div class="card-body p-4">

            <div class="text-end mb-4">
              <a href="?seen=all" class="btn btn-dark custom-bg text-white rounded-3 shadow-none btn-sm px-3 py-2 me-2">
                <i class="bi bi-check-all me-1"></i> Đánh dấu tất cả đã đọc
              </a>
              <a href="?del=all" class="btn btn-danger rounded-3 shadow-none btn-sm px-3 py-2">
                <i class="bi bi-trash me-1"></i> Xóa tất cả
              </a>
            </div>

            <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
              <table class="table table-hover border align-middle text-center">
                <thead>
                  <tr class="bg-dark text-light sticky-top">
                    <th scope="col">STT</th>
                    <th scope="col">Tên Phòng</th>
                    <th scope="col">Khách Hàng</th>
                    <th scope="col">Đánh Giá (Sao)</th>
                    <th scope="col" width="30%">Bình Luận / Nhận Xét</th>
                    <th scope="col">Ngày Đăng</th>
                    <th scope="col">Thao Tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                      INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                      INNER JOIN `rooms` r ON rr.room_id = r.id
                      ORDER BY `sr_no` DESC";

                    $data = mysqli_query($con,$q);
                    $i=1;

                    while($row = mysqli_fetch_assoc($data))
                    {
                      $date = date('d-m-Y',strtotime($row['datentime']));

                      $seen='';
                      if($row['seen']!=1){
                        $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2 shadow-none'><i class='bi bi-check2 me-1'></i>Đánh dấu đã đọc</a> <br>";
                      }
                      $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger shadow-none'><i class='bi bi-trash me-1'></i>Xóa</a>";

                      echo<<<query
                        <tr>
                          <td>$i</td>
                          <td>$row[rname]</td>
                          <td>$row[uname]</td>
                          <td><span class='badge bg-warning text-dark'><i class='bi bi-star-fill me-1'></i>$row[rating]</span></td>
                          <td>$row[review]</td>
                          <td>$date</td>
                          <td>$seen</td>
                        </tr>
                      query;
                      $i++;
                    }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
  

  <?php require('inc/scripts.php'); ?>

</body>
</html>