<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  date_default_timezone_set("Asia/Ho_Chi_Minh");
  adminLogin();

  if(isset($_POST['get_bookings']))
  {
    $frm_data = filteration($_POST);

    $limit = 5;
    $page = $frm_data['page'];
    $start = ($page-1) * $limit;

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE (bo.booking_status='booked' 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR bo.booking_status='payment failed') 
      AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      ORDER BY bo.booking_id DESC";

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');
    
    $limit_query = $query ." LIMIT $start,$limit";
    $limit_res = select($limit_query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');

    $total_rows = mysqli_num_rows($res);

    if($total_rows==0){
      $output = json_encode(["table_data"=>"<tr><td colspan='6' class='py-4 text-secondary fs-6'>Không tìm thấy lịch sử đặt phòng nào!</td></tr>", "pagination"=>'']);
      echo $output;
      exit;
    }

    $i=$start+1;
    $table_data = "";

    while($data = mysqli_fetch_assoc($limit_res))
    {
      $date = date("d/m/Y H:i",strtotime($data['datentime']));
      $checkin = date("d/m/Y",strtotime($data['check_in']));
      $checkout = date("d/m/Y",strtotime($data['check_out']));
      $price_formatted = number_format($data['price'] * 1000, 0, ',', '.');
      $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');

      $status_bg = 'bg-success';
      $status_title = 'Thành công';

      if($data['booking_status']=='booked'){
        $status_bg = 'bg-success';
        $status_title = 'Đã đặt thành công';
      }
      else if($data['booking_status']=='cancelled'){
        $status_bg = 'bg-danger';
        $status_title = 'Đã hủy đơn';
      }
      else{
        $status_bg = 'bg-secondary';
        $status_title = 'Thất bại';
      }
      
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
            <b>Tổng tiền:</b> <span class='text-teal fw-bold'>$total_formatted VNĐ</span>
            <br>
            <b>Thời gian:</b> $date
          </td>
          <td>
            <span class='badge $status_bg rounded-pill px-3 py-2'>$status_title</span>
          </td>
          <td>
            <button type='button' onclick='open_guest_modal($data[booking_id], \"$data[order_id]\")' class='btn btn-outline-teal btn-sm fw-bold shadow-none rounded-3 px-3 mb-1' title='Khai báo lưu trú'>
              <i class='bi bi-person-vcard me-1'></i> Tạm trú
            </button>
            <br>
            <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none rounded-3 px-3' title='Tải Hóa Đơn PDF'>
              <i class='bi bi-file-earmark-pdf-fill me-1'></i> Xuất PDF
            </button>
          </td>
        </tr>
      ";

      $i++;
    }

    $pagination = "";

    if($total_rows>$limit)
    {
      $total_pages = ceil($total_rows/$limit); 

      if($page!=1){
        $pagination .="<li class='page-item'>
          <button onclick='change_page(1)' class='page-link shadow-none'>Trang đầu</button>
        </li>";
      }

      $disabled = ($page==1) ? "disabled" : "";
      $prev= $page-1;
      $pagination .="<li class='page-item $disabled'>
        <button onclick='change_page($prev)' class='page-link shadow-none'>Trang trước</button>
      </li>";


      $disabled = ($page==$total_pages) ? "disabled" : "";
      $next = $page+1;
      $pagination .="<li class='page-item $disabled'>
        <button onclick='change_page($next)' class='page-link shadow-none'>Trang sau</button>
      </li>";

      if($page!=$total_pages){
        $pagination .="<li class='page-item'>
          <button onclick='change_page($total_pages)' class='page-link shadow-none'>Trang cuối</button>
        </li>";
      }

    }

    $output = json_encode(["table_data"=>$table_data,"pagination"=>$pagination]);

    echo $output;
  }

?>