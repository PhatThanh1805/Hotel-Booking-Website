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
      AND (bo.booking_status=? AND bo.refund=?) ORDER BY bo.booking_id ASC";

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","cancelled",0],'sssss');
    
    $i=1;
    $table_data = "";

    if(mysqli_num_rows($res)==0){
      echo "<tr><td colspan='5' class='py-4 text-secondary fs-6'>Không có đơn hủy nào cần xử lý hoàn tiền!</td></tr>";
      exit;
    }

    while($data = mysqli_fetch_assoc($res))
    {
      $date = date("d/m/Y H:i",strtotime($data['datentime']));
      $checkin = date("d/m/Y",strtotime($data['check_in']));
      $checkout = date("d/m/Y",strtotime($data['check_out']));
      $total_formatted = number_format($data['trans_amt'] * 1000, 0, ',', '.');

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
            <b>Ngày nhận:</b> $checkin | <b>Trả:</b> $checkout
            <br>
            <b>Ngày đặt:</b> $date
          </td>
          <td>
            <b class='text-danger fs-6'>$total_formatted VNĐ</b> 
          </td>
          <td>
            <button type='button' onclick='refund_booking($data[booking_id])' class='btn btn-success btn-sm fw-bold shadow-none rounded-3 px-3'>
              <i class='bi bi-cash-stack me-1'></i> Duyệt Hoàn Tiền
            </button>
          </td>
        </tr>
      ";

      $i++;
    }

    echo $table_data;
  }

  if(isset($_POST['refund_booking']))
  {
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` SET `refund`=? WHERE `booking_id`=?";
    $values = [1,$frm_data['booking_id']];
    $res = update($query,$values,'ii');

    echo $res;
  }

?>