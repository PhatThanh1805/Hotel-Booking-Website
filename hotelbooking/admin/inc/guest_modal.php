<!-- Modal Khai Báo Lưu Trú CCCD (Guest Declaration) -->
<div class="modal fade" id="guestModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">
          <i class="bi bi-person-vcard-fill text-teal me-2"></i>MODULE KHAI BÁO LƯU TRÚ CÔNG AN (ĐƠN: <span id="guest_modal_order_id" class="text-teal"></span>)
        </h5>
        <button type="button" class="btn-close shadow-none" onclick="stop_camera_scanner()" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">

        <!-- QR CCCD Scanner Bar Multi-Method -->
        <div class="card border-0 bg-light p-3 rounded-4 mb-4 border-start border-4 border-teal">
          <h6 class="fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
            <span><i class="bi bi-qr-code-scan me-2 text-teal"></i>QUÉT MÃ QR THẺ CĂN CƯỚC CÔNG DÂN (CCCD GẮN CHIP)</span>
            <span class="badge bg-teal">3 Phương Thức Quét</span>
          </h6>

          <div class="row g-3 align-items-center">
            <!-- Method 1: Upload Image File -->
            <div class="col-md-5">
              <label class="form-label fw-semibold mb-1 small"><i class="bi bi-file-earmark-image me-1 text-primary"></i>1. Chọn ảnh thẻ CCCD / Ảnh mã QR từ máy:</label>
              <input type="file" id="qr_file_input" accept="image/*" class="form-control shadow-none py-1 rounded-3" onchange="scan_qr_from_file_input(this)">
            </div>

            <!-- Method 2: Live Camera Button -->
            <div class="col-md-3">
              <label class="form-label fw-semibold mb-1 small d-block"><i class="bi bi-camera-video me-1 text-danger"></i>2. Hoặc Webcam trực tiếp:</label>
              <button type="button" onclick="toggle_camera_scanner()" class="btn btn-outline-dark w-100 shadow-none rounded-3 py-1 fw-semibold btn-sm">
                <i class="bi bi-camera me-1"></i>Bật Camera Quét
              </button>
            </div>

            <!-- Method 3: Manual Text Input / Barcode Scanner -->
            <div class="col-md-4">
              <label class="form-label fw-semibold mb-1 small"><i class="bi bi-keyboard me-1 text-success"></i>3. Hoặc dán dữ liệu máy quét barcode:</label>
              <div class="input-group">
                <input type="text" id="qr_string_input" class="form-control shadow-none py-1 rounded-3" placeholder="Dán mã QR tại đây...">
                <button type="button" onclick="parse_cccd_qr_input()" class="btn btn-teal shadow-none rounded-3 py-1 fw-semibold btn-sm" style="background-color: #03989e; color:#fff;">
                  <i class="bi bi-lightning-fill"></i> Tách
                </button>
              </div>
            </div>
          </div>

          <!-- Live Camera Stream Box -->
          <div id="camera_scanner_container" style="display:none;" class="mt-3 text-center bg-dark p-3 rounded-4">
            <h6 class="text-white mb-2"><i class="bi bi-webcam me-2 text-teal"></i>Đang bật Camera - Đưa mã QR trên thẻ CCCD vào khung hình</h6>
            <div id="qr-camera-view" style="width:100%; max-width:400px; margin:0 auto;" class="rounded-3 overflow-hidden border border-teal"></div>
            <button type="button" onclick="stop_camera_scanner()" class="btn btn-outline-light btn-sm mt-2 rounded-pill px-3">
              <i class="bi bi-x-circle me-1"></i>Tắt Camera
            </button>
          </div>

          <!-- Hidden div for file scanner -->
          <div id="qr_file_scanner_temp" style="display:none;"></div>

          <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Tự động bóc tách Số CCCD, Họ tên, Ngày sinh, Giới tính, Quê quán điền vào form trong &lt; 1 giây!</small>
        </div>

        <!-- Form Thêm Khách -->
        <form id="add_guest_form" class="mb-4">
          <input type="hidden" name="booking_id" id="guest_modal_booking_id">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold">Số CCCD / CMND / Hộ chiếu <span class="text-danger">*</span></label>
              <input type="text" name="id_card" required class="form-control shadow-none py-2 rounded-3" placeholder="Nhập số CCCD...">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Họ và tên thành viên <span class="text-danger">*</span></label>
              <input type="text" name="name" required class="form-control shadow-none py-2 rounded-3" placeholder="Nhập họ tên đầy đủ...">
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold">Ngày sinh</label>
              <input type="date" name="dob" class="form-control shadow-none py-2 rounded-3">
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold">Giới tính</label>
              <select name="gender" class="form-select shadow-none py-2 rounded-3">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold">Quốc tịch</label>
              <input type="text" name="nationality" value="Việt Nam" class="form-control shadow-none py-2 rounded-3">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Số điện thoại</label>
              <input type="text" name="phonenum" class="form-control shadow-none py-2 rounded-3" placeholder="SĐT liên hệ...">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Địa chỉ thường trú / Quê quán</label>
              <input type="text" name="address" class="form-control shadow-none py-2 rounded-3" placeholder="Tỉnh/Thành phố, Địa chỉ...">
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="is_leader" value="1" id="is_leader_chk">
                <label class="form-check-label fw-semibold" for="is_leader_chk">
                  Là Trưởng đoàn lưu trú
                </label>
              </div>
            </div>
            <div class="col-12 text-end">
              <button type="submit" class="btn custom-bg text-white shadow-none rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-person-plus-fill me-1"></i>LƯU THÔNG TIN THÀNH VIÊN
              </button>
            </div>
          </div>
        </form>

        <!-- Danh Sách Khách Lưu Trú -->
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-bold text-dark m-0"><i class="bi bi-people-fill me-1 text-teal"></i>Danh Sách Khách Hàng Đã Khai Báo Trong Đơn</h6>
          <a href="#" id="report_pdf_link" target="_blank" class="btn btn-outline-danger btn-sm shadow-none rounded-3 fw-semibold">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i>Xuất Mẫu Khai Báo Tạm Trú (Công An PDF)
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover border align-middle text-center">
            <thead>
              <tr class="bg-dark text-light">
                <th scope="col">STT</th>
                <th scope="col">Họ và Tên</th>
                <th scope="col">Số CCCD / CMND</th>
                <th scope="col">Ngày sinh</th>
                <th scope="col">Giới tính</th>
                <th scope="col">SĐT</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">Thao tác</th>
              </tr>
            </thead>
            <tbody id="guest-table-data">
            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary shadow-none rounded-3 px-4 py-2" onclick="stop_camera_scanner()" data-bs-dismiss="modal">ĐÓNG</button>
      </div>
    </div>
  </div>
</div>

<script>
  let guest_form = document.getElementById('add_guest_form');

  function open_guest_modal(booking_id, order_id) {
    document.getElementById('guest_modal_order_id').innerText = order_id;
    document.getElementById('guest_modal_booking_id').value = booking_id;
    document.getElementById('report_pdf_link').href = `generate_guest_report.php?booking_id=${booking_id}`;

    get_guests(booking_id);

    let modal = new bootstrap.Modal(document.getElementById('guestModal'));
    modal.show();
  }

  function get_guests(booking_id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/guest_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
      document.getElementById('guest-table-data').innerHTML = this.responseText;
    };

    xhr.send(`get_guests=1&booking_id=${booking_id}`);
  }

  if (guest_form) {
    guest_form.addEventListener('submit', function(e) {
      e.preventDefault();

      let data = new FormData(guest_form);
      data.append('add_guest', 1);

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/guest_crud.php", true);

      xhr.onload = function() {
        if (this.responseText == 1) {
          alert('success', 'Đã thêm thông tin lưu trú thành công!');
          guest_form.reset();
          let b_id = document.getElementById('guest_modal_booking_id').value;
          get_guests(b_id);
        } else {
          alert('error', 'Thao tác thất bại! Vui lòng kiểm tra dữ liệu.');
        }
      };

      xhr.send(data);
    });
  }

  function delete_guest(id, booking_id) {
    if (confirm('Bạn có chắc muốn xóa thông tin thành viên này khỏi danh sách tạm trú?')) {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/guest_crud.php", true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        if (this.responseText == 1) {
          alert('success', 'Đã xóa thông tin thành viên!');
          get_guests(booking_id);
        } else {
          alert('error', 'Xóa thất bại!');
        }
      };

      xhr.send(`delete_guest=1&id=${id}&booking_id=${booking_id}`);
    }
  }

  function parse_cccd_qr_input(raw_qr_string = null) {
    let qr_val = raw_qr_string ? raw_qr_string.trim() : document.getElementById('qr_string_input').value.trim();
    if (!qr_val) {
      alert('error', 'Vui lòng dán chuỗi mã QR từ máy quét CCCD!');
      return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/guest_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
      try {
        let res = JSON.parse(this.responseText);
        if (res.status === 'success') {
          let d = res.data;
          guest_form.elements['id_card'].value = d.id_card;
          guest_form.elements['name'].value = d.name;
          guest_form.elements['dob'].value = d.dob;
          guest_form.elements['gender'].value = d.gender;
          guest_form.elements['address'].value = d.address;
          guest_form.elements['nationality'].value = d.nationality;

          alert('success', 'Đã quét & tự động điền thông tin CCCD thành công!');
          document.getElementById('qr_string_input').value = "";
        } else {
          alert('error', res.message);
        }
      } catch (e) {
        alert('error', 'Không thể đọc chuỗi QR CCCD này!');
      }
    };

    xhr.send(`parse_cccd_qr=1&qr_string=${encodeURIComponent(qr_val)}`);
  }

  // Scan QR Code from Image File Input
  function scan_qr_from_file_input(input_element) {
    if (input_element.files.length === 0) return;
    const file = input_element.files[0];

    if (typeof Html5Qrcode === 'undefined') {
      alert('error', 'Thư viện QR Scanner chưa tải xong, vui lòng thử lại sau vài giây!');
      return;
    }

    const html5QrCode = new Html5Qrcode("qr_file_scanner_temp");
    html5QrCode.scanFile(file, true)
      .then(decodedText => {
        document.getElementById('qr_string_input').value = decodedText;
        parse_cccd_qr_input(decodedText);
        input_element.value = "";
      })
      .catch(err => {
        alert('error', 'Không thể giải mã QR từ ảnh này! Hãy đảm bảo ảnh thẻ CCCD/QR rõ nét.');
        input_element.value = "";
      });
  }

  // Live Camera Scanner Toggle
  let html5QrCodeCamera = null;

  function toggle_camera_scanner() {
    const container = document.getElementById('camera_scanner_container');
    if (container.style.display === 'none' || container.style.display === '') {
      container.style.display = 'block';

      if (typeof Html5Qrcode === 'undefined') {
        alert('error', 'Thư viện QR Scanner chưa sẵn sàng!');
        return;
      }

      if (!html5QrCodeCamera) {
        html5QrCodeCamera = new Html5Qrcode("qr-camera-view");
      }

      html5QrCodeCamera.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText, decodedResult) => {
          document.getElementById('qr_string_input').value = decodedText;
          parse_cccd_qr_input(decodedText);
          stop_camera_scanner();
        },
        (errorMessage) => {}
      ).catch(err => {
        alert('error', 'Không thể bật Camera! Vui lòng kiểm tra quyền truy cập webcam trên trình duyệt.');
      });
    } else {
      stop_camera_scanner();
    }
  }

  function stop_camera_scanner() {
    const container = document.getElementById('camera_scanner_container');
    if (html5QrCodeCamera && html5QrCodeCamera.isScanning) {
      html5QrCodeCamera.stop().then(() => {
        container.style.display = 'none';
      }).catch(err => console.error(err));
    } else {
      container.style.display = 'none';
    }
  }
</script>
