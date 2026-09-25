<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  if(isset($_POST['get_bookings']))
  {
    $frm_data = filteration($_POST);

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      AND (bo.booking_status=? AND bo.arrival=?) ORDER BY bo.booking_id ASC";

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","booked",0],'sssss');
    
    $i=1;
    $table_data = "";

    if(mysqli_num_rows($res)==0){
      echo "<tr><td colspan='5' class='py-4 text-secondary fs-6'>Không tìm thấy dữ liệu đơn đặt phòng!</td></tr>";
      exit;
    }

    while($data = mysqli_fetch_assoc($res))
    {
      $date = date("d/m/Y H:i",strtotime($data['datentime']));
      $checkin = date("d/m/Y",strtotime($data['check_in']));
      $checkout = date("d/m/Y",strtotime($data['check_out']));
      $price_formatted = number_format($data['price'] * 1000, 0, ',', '.');
      $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');

      $table_data .="
        <tr>
          <td>$i</td>
          <td class='text-start'>
            <span class='badge bg-teal mb-1 px-2 py-1'>
              Mã đơn: $data[order_id]
            </span>
            <br>
            <b>Họ tên:</b> $data[user_name]
            <br>
            <b>SĐT:</b> $data[phonenum]
          </td>
          <td class='text-start'>
            <b>Tên phòng:</b> $data[room_name]
            <br>
            <b>Giá/đêm:</b> $price_formatted VNĐ
          </td>
          <td class='text-start'>
            <b>Ngày nhận:</b> $checkin
            <br>
            <b>Ngày trả:</b> $checkout
            <br>
            <b>Đã thanh toán:</b> <span class='text-teal fw-bold'>$total_formatted VNĐ</span>
            <br>
            <b>Ngày đặt:</b> $date
          </td>
          <td>
            <button type='button' onclick='assign_room($data[booking_id])' class='btn text-white btn-sm fw-bold custom-bg shadow-none rounded-3 px-3' data-bs-toggle='modal' data-bs-target='#assign-room'>
              <i class='bi bi-check2-square me-1'></i> Xếp số phòng
            </button>
            <br>
            <button type='button' onclick='open_guest_modal($data[booking_id], \"$data[order_id]\")' class='mt-2 btn btn-outline-teal btn-sm fw-bold shadow-none rounded-3 px-3'>
              <i class='bi bi-person-vcard me-1'></i> Khai báo tạm trú
            </button>
            <br>
            <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none rounded-3 px-3'>
              <i class='bi bi-x-circle me-1'></i> Hủy đơn phòng
            </button>
          </td>
        </tr>
      ";

      $i++;
    }

    echo $table_data;
  }

  if(isset($_POST['assign_room']))
  {
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` bo INNER JOIN `booking_details` bd
      ON bo.booking_id = bd.booking_id
      SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ? 
      WHERE bo.booking_id = ?";

    $values = [1,0,$frm_data['room_no'],$frm_data['booking_id']];

    $res = update($query,$values,'iisi'); // it will update 2 rows so it will return 2

    echo ($res==2) ? 1 : 0;
  }

  if(isset($_POST['cancel_booking']))
  {
    $frm_data = filteration($_POST);
    
    $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
    $values = ['cancelled',0,$frm_data['booking_id']];
    $res = update($query,$values,'sii');

    echo $res;
  }

?>