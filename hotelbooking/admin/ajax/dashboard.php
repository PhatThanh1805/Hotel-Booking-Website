<?php 

  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  // 1. Phân tích Đơn đặt phòng & Doanh thu + Dữ liệu Biểu đồ Trend
  if(isset($_POST['booking_analytics']))
  {
    $frm_data = filteration($_POST);

    $condition = "";
    $period = (int)$frm_data['period'];

    if($period == 0){ // 7 ngày qua
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 7 DAY AND NOW()";
      $group_format = "%d/%m";
    }
    else if($period == 1){ // 30 ngày qua
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
      $group_format = "%d/%m";
    }
    else if($period == 2){ // 90 ngày qua
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
      $group_format = "%d/%m";
    }
    else if($period == 3){ // 1 năm qua
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
      $group_format = "T%m/%Y";
    }
    else { // Tất cả thời gian
      $condition = "";
      $group_format = "T%m/%Y";
    }

    // Thống kê tổng quan
    $summary_q = "SELECT 
      COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `total_bookings`,
      COALESCE(SUM(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN `trans_amt` END), 0) AS `total_amt`,
      COUNT(CASE WHEN booking_status='booked' THEN 1 END) AS `active_bookings`,
      COALESCE(SUM(CASE WHEN booking_status='booked' THEN `trans_amt` END), 0) AS `active_amt`,
      COUNT(CASE WHEN booking_status='cancelled' THEN 1 END) AS `cancelled_bookings`,
      COALESCE(SUM(CASE WHEN booking_status='cancelled' THEN `trans_amt` END), 0) AS `cancelled_amt`
      FROM `booking_order` $condition";

    $summary_res = mysqli_fetch_assoc(mysqli_query($con, $summary_q));

    $total_b = (int)$summary_res['total_bookings'];
    $cancelled_b = (int)$summary_res['cancelled_bookings'];
    $total_a = (float)$summary_res['total_amt'];

    $avg_amt = ($total_b > 0) ? round(($total_a * 1000) / $total_b) : 0;
    $cancel_rate = (($total_b + $cancelled_b) > 0) ? round(($cancelled_b / ($total_b + $cancelled_b)) * 100, 1) : 0;

    // Dữ liệu chuỗi thời gian cho Biểu đồ Doanh Thu & Số Lượng Đơn
    if($period == 0 || $period == 1 || $period == 2){
      $trend_sql = "SELECT 
        DATE_FORMAT(datentime, '$group_format') AS `label`,
        COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `bookings`,
        COALESCE(SUM(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN `trans_amt` END), 0) AS `revenue`
        FROM `booking_order` $condition
        GROUP BY DATE(datentime)
        ORDER BY datentime ASC";
    } else {
      $trend_sql = "SELECT 
        DATE_FORMAT(datentime, '$group_format') AS `label`,
        COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `bookings`,
        COALESCE(SUM(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN `trans_amt` END), 0) AS `revenue`
        FROM `booking_order` $condition
        GROUP BY YEAR(datentime), MONTH(datentime)
        ORDER BY datentime ASC";
    }

    $trend_res = mysqli_query($con, $trend_sql);
    $chart_labels = [];
    $chart_revenue = [];
    $chart_bookings = [];

    while($row = mysqli_fetch_assoc($trend_res)){
      $chart_labels[] = $row['label'];
      $chart_revenue[] = (float)$row['revenue'] * 1000;
      $chart_bookings[] = (int)$row['bookings'];
    }

    // Biểu đồ phân bổ trạng thái đơn
    $status_sql = "SELECT 
      COUNT(CASE WHEN booking_status='booked' THEN 1 END) AS `booked_cnt`,
      COUNT(CASE WHEN booking_status='cancelled' THEN 1 END) AS `cancelled_cnt`,
      COUNT(CASE WHEN booking_status='payment failed' THEN 1 END) AS `failed_cnt`,
      COUNT(CASE WHEN booking_status='pending' THEN 1 END) AS `pending_cnt`
      FROM `booking_order` $condition";

    $status_res = mysqli_fetch_assoc(mysqli_query($con, $status_sql));

    $response = [
      'total_bookings' => $summary_res['total_bookings'],
      'total_amt' => $summary_res['total_amt'],
      'active_bookings' => $summary_res['active_bookings'],
      'active_amt' => $summary_res['active_amt'],
      'cancelled_bookings' => $summary_res['cancelled_bookings'],
      'cancelled_amt' => $summary_res['cancelled_amt'],
      'avg_amt' => $avg_amt,
      'cancel_rate' => $cancel_rate,
      'chart_labels' => $chart_labels,
      'chart_revenue' => $chart_revenue,
      'chart_bookings' => $chart_bookings,
      'status_counts' => [
        (int)$status_res['booked_cnt'],
        (int)$status_res['cancelled_cnt'],
        (int)$status_res['failed_cnt'],
        (int)$status_res['pending_cnt']
      ]
    ];

    echo json_encode($response);
    exit;
  }

  // 2. Thống kê Tỷ lệ Lấp Đầy & Trạng Thái Phòng Realtime
  if(isset($_POST['room_analytics']))
  {
    $total_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0"));
    $avail_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0 AND `status`=1"));
    $clean_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0 AND `status`=2"));
    $occup_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0 AND `status`=3"));
    $bookd_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0 AND `status`=4"));
    $maint_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `cnt` FROM `rooms` WHERE `removed`=0 AND `status`=0"));

    $total = (int)$total_res['cnt'];
    $available = (int)$avail_res['cnt'];
    $cleaning = (int)$clean_res['cnt'];
    $occupied = (int)$occup_res['cnt'];
    $booked = (int)$bookd_res['cnt'];
    $maintenance = (int)$maint_res['cnt'];

    $active_capacity = max(1, $total - $maintenance);
    $occupancy_rate = round((($occupied + $booked) / $active_capacity) * 100, 1);

    $room_stats = [
      'total' => $total,
      'available' => $available,
      'cleaning' => $cleaning,
      'occupied' => $occupied,
      'booked' => $booked,
      'maintenance' => $maintenance,
      'occupancy_rate' => $occupancy_rate
    ];

    echo json_encode($room_stats);
    exit;
  }

  // 3. Phân tích Tương tác Khách hàng
  if(isset($_POST['user_analytics']))
  {
    $frm_data = filteration($_POST);

    $condition="";
    $period = (int)$frm_data['period'];

    if($period == 0){
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 7 DAY AND NOW()";
    }
    else if($period == 1){
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($period == 2){
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($period == 3){
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $total_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` FROM `rating_review` $condition"));
    $total_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` FROM `user_queries` $condition"));
    $total_new_reg = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(id) AS `count` FROM `user_cred` $condition"));

    $output = [
      'total_queries' => $total_queries['count'],
      'total_reviews' => $total_reviews['count'],
      'total_new_reg' => $total_new_reg['count']
    ];

    echo json_encode($output);
    exit;
  }

?>