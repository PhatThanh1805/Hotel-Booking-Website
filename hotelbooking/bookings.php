<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - ĐƠN ĐẶT PHÒNG CỦA TÔI</title>
</head>
<body class="bg-light">

  <?php 
    require('inc/header.php'); 

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }
  ?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 px-4">
        <h2 class="fw-bold text-dark">LỊCH SỬ ĐẶT PHÒNG</h2>
        <div style="font-size: 15px;" class="fw-medium">
          <a href="index.php" class="text-secondary text-decoration-none"><i class="bi bi-house me-1"></i>TRANG CHỦ</a>
          <span class="text-secondary mx-2"> > </span>
          <span class="text-dark">ĐƠN ĐẶT PHÒNG</span>
        </div>
      </div>

      <?php 
        
        $query = "SELECT bo.*, bd.* FROM `booking_order` bo
          INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
          WHERE ((bo.booking_status='booked') 
          OR (bo.booking_status='cancelled')
          OR (bo.booking_status='payment failed')) 
          AND (bo.user_id=?)
          ORDER BY bo.booking_id DESC";

        $result = select($query,[$_SESSION['uId']],'i');

        if(mysqli_num_rows($result)==0){
          echo "<div class='col-12 px-4 text-center py-5 text-secondary fs-5'>Bạn chưa có đơn đặt phòng nào!</div>";
        }

        while($data = mysqli_fetch_assoc($result))
        {
          $date = date("d/m/Y H:i",strtotime($data['datentime']));
          $checkin = date("d/m/Y",strtotime($data['check_in']));
          $checkout = date("d/m/Y",strtotime($data['check_out']));
          $price_formatted = number_format($data['price'] * 1000, 0, ',', '.');
          $total_formatted = number_format($data['total_pay'] * 1000, 0, ',', '.');

          $status_badge = "";
          $btn = "";
          
          if($data['booking_status']=='booked')
          {
            $status_badge = "<span class='badge bg-success rounded-pill px-3 py-2'>Đã thành công</span>";
            $btn = "<button type='button' onclick='open_user_guest_modal($data[booking_id], \"$data[order_id]\")' class='btn btn-outline-teal btn-sm shadow-none rounded-3 me-2 mb-1'><i class='bi bi-person-vcard me-1'></i>Khai báo đoàn / đi cùng</button>";
            $btn .= "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark custom-bg text-white btn-sm shadow-none rounded-3 me-2 mb-1'><i class='bi bi-file-earmark-pdf me-1'></i>Tải hóa đơn PDF</a>";

            if($data['arrival']==1)
            {
              if($data['rate_review']==0){
                $btn .= "<button type='button' onclick='review_room($data[booking_id],$data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-outline-dark btn-sm shadow-none rounded-3 mb-1'><i class='bi bi-star me-1'></i>Đánh giá</button>";
              }
            }
            else{
              $btn .= "<button onclick='cancel_booking($data[booking_id])' type='button' class='btn btn-outline-danger btn-sm shadow-none rounded-3 mb-1'><i class='bi bi-x-circle me-1'></i>Hủy đặt</button>";
            }
          }
          else if($data['booking_status']=='cancelled')
          {
            $status_badge = "<span class='badge bg-danger rounded-pill px-3 py-2'>Đã hủy đơn</span>";

            if($data['refund']==0){
              $btn="<span class='badge bg-warning text-dark p-2 rounded-3'><i class='bi bi-clock-history me-1'></i>Đang xử lý hoàn tiền</span>";
            }
            else{
              $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none rounded-3'><i class='bi bi-file-earmark-pdf me-1'></i>Tải hóa đơn PDF</a>";
            }
          }
          else
          {
            $status_badge = "<span class='badge bg-secondary rounded-pill px-3 py-2'>Thất bại</span>";
            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none rounded-3'><i class='bi bi-file-earmark-pdf me-1'></i>Tải hóa đơn PDF</a>";
          }

          echo<<<bookings
            <div class='col-md-6 px-4 mb-4'>
              <div class='bg-white p-4 rounded-4 shadow-sm border-0 h-100 d-flex flex-column justify-content-between pop'>
                <div>
                  <div class='d-flex align-items-center justify-content-between mb-3'>
                    <h5 class='fw-bold text-dark m-0'>$data[room_name]</h5>
                    $status_badge
                  </div>
                  <p class='text-teal fw-bold mb-3 fs-5'>$price_formatted VNĐ <span class='fs-6 text-muted fw-normal'>/ đêm</span></p>
                  <div class='p-3 bg-light rounded-3 mb-3 small'>
                    <div class='mb-1'><b>Ngày nhận phòng:</b> $checkin</div>
                    <div class='mb-1'><b>Ngày trả phòng:</b> $checkout</div>
                    <div class='mb-1'><b>Mã đơn hàng:</b> <code class='text-dark fw-bold'>$data[order_id]</code></div>
                    <div><b>Thời gian đặt:</b> $date</div>
                  </div>
                  <div class='mb-3'>
                    <b>Tổng tiền thanh toán:</b> <span class='fs-5 text-dark fw-bold'>$total_formatted VNĐ</span>
                  </div>
                </div>
                <div class='border-top pt-3 text-end'>
                  $btn
                </div>
              </div>
            </div>
          bookings;

        }

      ?>

    </div>
  </div>


  <!-- Modal Khai Báo Người Đi Cùng Phía Khách Hàng -->
  <div class="modal fade" id="userGuestModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold text-dark">
            <i class="bi bi-person-vcard-fill text-teal me-2"></i>KHAI BÁO THÀNH VIÊN ĐI CÙNG (ĐƠN: <span id="u_guest_modal_order_id" class="text-teal"></span>)
          </h5>
          <button type="button" class="btn-close shadow-none" onclick="u_stop_camera_scanner()" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">

          <!-- CCCD QR Scan Bar Multi-Method -->
          <div class="card border-0 bg-light p-3 rounded-4 mb-4 border-start border-4 border-teal">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-qr-code-scan me-2 text-teal"></i>QUÉT MÃ QR THẺ CĂN CƯỚC CÔNG DÂN (CCCD)</h6>
            
            <div class="row g-3 align-items-center">
              <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small"><i class="bi bi-file-earmark-image me-1 text-primary"></i>1. Chọn ảnh thẻ CCCD / Mã QR:</label>
                <input type="file" id="u_qr_file_input" accept="image/*" class="form-control shadow-none py-1 rounded-3" onchange="u_scan_qr_from_file_input(this)">
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small d-block"><i class="bi bi-camera-video me-1 text-danger"></i>2. Hoặc mở Camera quét trực tiếp:</label>
                <button type="button" onclick="u_toggle_camera_scanner()" class="btn btn-outline-dark w-100 shadow-none rounded-3 py-1 fw-semibold btn-sm">
                  <i class="bi bi-camera me-1"></i>Bật Camera Quét Live
                </button>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold mb-1 small"><i class="bi bi-keyboard me-1 text-success"></i>3. Hoặc dán chuỗi dữ liệu mã QR:</label>
                <div class="input-group">
                  <input type="text" id="u_qr_string_input" class="form-control shadow-none py-1 rounded-3" placeholder="Dán mã QR tại đây...">
                  <button type="button" onclick="u_parse_cccd_qr_input()" class="btn custom-bg text-white shadow-none rounded-3 py-1 fw-semibold btn-sm">
                    <i class="bi bi-lightning-charge-fill me-1"></i>TỰ ĐỘNG ĐIỀN
                  </button>
                </div>
              </div>
            </div>

            <!-- Live Camera Stream Box -->
            <div id="u_camera_scanner_container" style="display:none;" class="mt-3 text-center bg-dark p-3 rounded-4">
              <h6 class="text-white mb-2"><i class="bi bi-webcam me-2 text-teal"></i>Đưa mã QR trên thẻ CCCD vào khung camera bên dưới</h6>
              <div id="u-qr-camera-view" style="width:100%; max-width:350px; margin:0 auto;" class="rounded-3 overflow-hidden border border-teal"></div>
              <button type="button" onclick="u_stop_camera_scanner()" class="btn btn-outline-light btn-sm mt-2 rounded-pill px-3">
                <i class="bi bi-x-circle me-1"></i>Tắt Camera
              </button>
            </div>

            <div id="u_qr_file_scanner_temp" style="display:none;"></div>
          </div>

          <!-- Form Thêm Thành Viên -->
          <form id="u_add_guest_form" class="mb-4">
            <input type="hidden" name="booking_id" id="u_guest_modal_booking_id">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Số CCCD / CMND / Hộ chiếu <span class="text-danger">*</span></label>
                <input type="text" name="id_card" required class="form-control shadow-none py-2 rounded-3" placeholder="Nhập số CCCD...">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Họ và tên thành viên <span class="text-danger">*</span></label>
                <input type="text" name="name" required class="form-control shadow-none py-2 rounded-3" placeholder="Nhập họ tên...">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Ngày sinh</label>
                <input type="date" name="dob" class="form-control shadow-none py-2 rounded-3">
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Giới tính</label>
                <select name="gender" class="form-select shadow-none py-2 rounded-3">
                  <option value="Nam">Nam</option>
                  <option value="Nữ">Nữ</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Quốc tịch</label>
                <input type="text" name="nationality" value="Việt Nam" class="form-control shadow-none py-2 rounded-3">
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">
                  <i class="bi bi-plus-lg me-1"></i>THÊM NGƯỜI ĐI CÙNG
                </button>
              </div>
            </div>
          </form>

          <!-- Danh sách người đi cùng -->
          <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill me-1 text-teal"></i>Danh Sách Thành Viên Khai Báo:</h6>
          <div class="table-responsive">
            <table class="table table-hover border align-middle text-center">
              <thead>
                <tr class="bg-dark text-light">
                  <th scope="col">STT</th>
                  <th scope="col">Họ và Tên</th>
                  <th scope="col">Số CCCD</th>
                  <th scope="col">Ngày sinh</th>
                  <th scope="col">Giới tính</th>
                  <th scope="col">Thao tác</th>
                </tr>
              </thead>
              <tbody id="u-guest-table-data">
              </tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary shadow-none rounded-3 px-4 py-2" onclick="u_stop_camera_scanner()" data-bs-dismiss="modal">ĐÓNG</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Đánh giá phòng -->
  <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <form id="review-form">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title d-flex align-items-center fw-bold text-dark">
              <i class="bi bi-star-fill text-warning fs-3 me-2"></i> Đánh Giá & Bình Luận Phòng
            </h5>
            <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Mức độ hài lòng</label>
              <select class="form-select shadow-none py-2 rounded-3" name="rating">
                <option value="5">⭐⭐⭐⭐⭐ 5 Sao - Xuất sắc</option>
                <option value="4">⭐⭐⭐⭐ 4 Sao - Rất tốt</option>
                <option value="3">⭐⭐⭐ 3 Sao - Tốt</option>
                <option value="2">⭐⭐ 2 Sao - Bình thường</option>
                <option value="1">⭐ 1 Sao - Kém</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Nội dung đánh giá</label>
              <textarea name="review" rows="4" required class="form-control shadow-none rounded-3" placeholder="Chia sẻ trải nghiệm dịch vụ phòng của bạn..."></textarea>
            </div>
            
            <input type="hidden" name="booking_id">
            <input type="hidden" name="room_id">

            <div class="text-end">
              <button type="button" class="btn btn-light shadow-none me-2 rounded-3 px-3 py-2" data-bs-dismiss="modal">HỦY BỎ</button>
              <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-send me-1"></i> GỬI ĐÁNH GIÁ
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php 
    if(isset($_GET['cancel_status'])){
      alert('success','Đã gửi yêu cầu hủy đơn đặt phòng thành công!');
    }  
    else if(isset($_GET['review_status'])){
      alert('success','Cảm ơn bạn đã gửi đánh giá & nhận xét!');
    }  
  ?>

  <?php require('inc/footer.php'); ?>

  <script>
    function cancel_booking(id)
    {
      if(confirm('Bạn có chắc chắn muốn hủy đơn đặt phòng này không?'))
      {        
        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/cancel_booking.php",true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function(){
          if(this.responseText==1){
            window.location.href="bookings.php?cancel_status=true";
          }
          else{
            alert('error','Hủy đơn đặt phòng thất bại!');
          }
        }

        xhr.send('cancel_booking&id='+id);
      }
    }

    let review_form = document.getElementById('review-form');

    function review_room(bid,rid){
      review_form.elements['booking_id'].value = bid;
      review_form.elements['room_id'].value = rid;
    }

    if(review_form) {
      review_form.addEventListener('submit',function(e){
        e.preventDefault();

        let data = new FormData();

        data.append('review_form','');
        data.append('rating',review_form.elements['rating'].value);
        data.append('review',review_form.elements['review'].value);
        data.append('booking_id',review_form.elements['booking_id'].value);
        data.append('room_id',review_form.elements['room_id'].value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/review_room.php",true);

        xhr.onload = function()
        {

          if(this.responseText == 1)
          {
            window.location.href = 'bookings.php?review_status=true';
          }
          else{
            var myModal = document.getElementById('reviewModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            if(modal) modal.hide();
    
            alert('error',"Gửi đánh giá thất bại!");
          }
        }

        xhr.send(data);
      });
    }

    // User Guest Declaration JS
    let u_guest_form = document.getElementById('u_add_guest_form');

    function open_user_guest_modal(booking_id, order_id) {
      document.getElementById('u_guest_modal_order_id').innerText = order_id;
      document.getElementById('u_guest_modal_booking_id').value = booking_id;
      u_get_guests(booking_id);

      let modal = new bootstrap.Modal(document.getElementById('userGuestModal'));
      modal.show();
    }

    function u_get_guests(booking_id) {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/guest_declaration.php", true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        document.getElementById('u-guest-table-data').innerHTML = this.responseText;
      };

      xhr.send(`get_guests=1&booking_id=${booking_id}`);
    }

    if(u_guest_form) {
      u_guest_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData(u_guest_form);
        data.append('add_guest', 1);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/guest_declaration.php", true);

        xhr.onload = function() {
          if (this.responseText == 1) {
            alert('success', 'Đã thêm thành viên đi cùng!');
            u_guest_form.reset();
            let b_id = document.getElementById('u_guest_modal_booking_id').value;
            u_get_guests(b_id);
          } else {
            alert('error', 'Thêm thành viên thất bại!');
          }
        };

        xhr.send(data);
      });
    }

    function user_delete_guest(id, booking_id) {
      if (confirm('Bạn có chắc muốn xóa thông tin thành viên này?')) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/guest_declaration.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
          if (this.responseText == 1) {
            alert('success', 'Đã xóa thành viên!');
            u_get_guests(booking_id);
          } else {
            alert('error', 'Xóa thất bại!');
          }
        };

        xhr.send(`delete_guest=1&id=${id}&booking_id=${booking_id}`);
      }
    }

    function u_parse_cccd_qr_input(raw_qr_string = null) {
      let qr_val = raw_qr_string ? raw_qr_string.trim() : document.getElementById('u_qr_string_input').value.trim();
      if (!qr_val) {
        alert('error', 'Vui lòng dán chuỗi dữ liệu mã QR CCCD!');
        return;
      }

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/guest_declaration.php", true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        try {
          let res = JSON.parse(this.responseText);
          if (res.status === 'success') {
            let d = res.data;
            u_guest_form.elements['id_card'].value = d.id_card;
            u_guest_form.elements['name'].value = d.name;
            u_guest_form.elements['dob'].value = d.dob;
            u_guest_form.elements['gender'].value = d.gender;

            alert('success', 'Đã bóc tách & tự động điền thông tin CCCD!');
            document.getElementById('u_qr_string_input').value = "";
          } else {
            alert('error', res.message);
          }
        } catch (e) {
          alert('error', 'Mã QR không đúng định dạng CCCD!');
        }
      };

      xhr.send(`parse_cccd_qr=1&qr_string=${encodeURIComponent(qr_val)}`);
    }

    // Customer Side Image File QR Scanner
    function u_scan_qr_from_file_input(input_element) {
      if (input_element.files.length === 0) return;
      const file = input_element.files[0];

      if (typeof Html5Qrcode === 'undefined') {
        alert('error', 'Thư viện QR Scanner chưa sẵn sàng!');
        return;
      }

      const html5QrCode = new Html5Qrcode("u_qr_file_scanner_temp");
      html5QrCode.scanFile(file, true)
        .then(decodedText => {
          document.getElementById('u_qr_string_input').value = decodedText;
          u_parse_cccd_qr_input(decodedText);
          input_element.value = "";
        })
        .catch(err => {
          alert('error', 'Không thể đọc QR từ ảnh này! Hãy chọn ảnh rõ nét hơn.');
          input_element.value = "";
        });
    }

    // Customer Side Live Camera Scanner Toggle
    let u_html5QrCodeCamera = null;

    function u_toggle_camera_scanner() {
      const container = document.getElementById('u_camera_scanner_container');
      if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';

        if (typeof Html5Qrcode === 'undefined') {
          alert('error', 'Thư viện QR Scanner chưa sẵn sàng!');
          return;
        }

        if (!u_html5QrCodeCamera) {
          u_html5QrCodeCamera = new Html5Qrcode("u-qr-camera-view");
        }

        u_html5QrCodeCamera.start(
          { facingMode: "environment" },
          { fps: 10, qrbox: { width: 220, height: 220 } },
          (decodedText, decodedResult) => {
            document.getElementById('u_qr_string_input').value = decodedText;
            u_parse_cccd_qr_input(decodedText);
            u_stop_camera_scanner();
          },
          (errorMessage) => {}
        ).catch(err => {
          alert('error', 'Không thể bật Camera! Vui lòng cấp quyền camera cho trình duyệt.');
        });
      } else {
        u_stop_camera_scanner();
      }
    }

    function u_stop_camera_scanner() {
      const container = document.getElementById('u_camera_scanner_container');
      if (u_html5QrCodeCamera && u_html5QrCodeCamera.isScanning) {
        u_html5QrCodeCamera.stop().then(() => {
          container.style.display = 'none';
        }).catch(err => console.error(err));
      } else {
        container.style.display = 'none';
      }
    }

  </script>

</body>
</html>