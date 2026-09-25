let current_month = new Date().getMonth() + 1;
let current_year = new Date().getFullYear();

function change_month(offset) {
  current_month += offset;
  if (current_month > 12) {
    current_month = 1;
    current_year++;
  } else if (current_month < 1) {
    current_month = 12;
    current_year--;
  }
  document.getElementById('select_month').value = current_month;
  document.getElementById('select_year').value = current_year;
  load_calendar();
}

function load_calendar() {
  let month = document.getElementById('select_month').value;
  let year = document.getElementById('select_year').value;

  current_month = parseInt(month);
  current_year = parseInt(year);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/booking_calendar.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    try {
      let res = JSON.parse(this.responseText);
      if (res.status === 'success') {
        document.getElementById('stat_total_rooms').innerText = res.total_rooms;
        document.getElementById('stat_booked_nights').innerText = res.total_month_booked_nights;
        document.getElementById('stat_occ_rate').innerText = res.avg_occupancy + '%';
        document.getElementById('calendar_title').innerText = `THÁNG ${res.month}/${res.year}`;

        render_calendar_grid(res);
      }
    } catch (e) {
      console.error(e);
    }
  };

  xhr.send(`get_calendar=1&month=${month}&year=${year}`);
}

function render_calendar_grid(data) {
  let grid = document.getElementById('calendar_grid');
  grid.innerHTML = "";

  // Headings for Weekdays (T2 - CN)
  const weekdays = ['T2 (Thứ 2)', 'T3 (Thứ 3)', 'T4 (Thứ 4)', 'T5 (Thứ 5)', 'T6 (Thứ 6)', 'T7 (Thứ 7)', 'CN (Chủ Nhật)'];
  let headerHtml = "";
  weekdays.forEach(w => {
    headerHtml += `<div class="p-2 text-center fw-bold bg-dark text-white rounded-3 small">${w}</div>`;
  });
  grid.innerHTML = headerHtml;

  // Empty slots before 1st day of month
  let start_slot = data.first_weekday; // 1 to 7
  for (let i = 1; i < start_slot; i++) {
    grid.innerHTML += `<div class="p-3 bg-light rounded-3 opacity-25 border"></div>`;
  }

  // Render days
  let today_str = new Date().toISOString().split('T')[0];

  data.days.forEach(d => {
    let is_today = (d.date === today_str) ? 'border-3 border-teal shadow' : 'border-secondary border-opacity-25';

    let dayCard = `
      <div onclick="view_day_bookings('${d.date}')" class="calendar-day-card bg-white p-3 rounded-4 border ${is_today} pop cursor-pointer d-flex flex-column justify-content-between h-100" style="min-height: 110px;">
        <div class="d-flex align-items-center justify-content-between">
          <span class="fs-5 fw-bold text-dark">${d.day}</span>
          <span class="badge ${d.badge_class} rounded-pill px-2 py-1 small">${d.status_text}</span>
        </div>
        <div class="mt-2">
          <div class="d-flex justify-content-between align-items-center small text-secondary">
            <span>Đã đặt:</span>
            <span class="fw-bold text-teal fs-6">${d.booked} phòng</span>
          </div>
          <div class="d-flex justify-content-between align-items-center small text-secondary">
            <span>Còn trống:</span>
            <span class="fw-bold text-success">${d.available} phòng</span>
          </div>
          <div class="progress mt-2" style="height: 5px;">
            <div class="progress-bar ${d.rate >= 80 ? 'bg-danger' : (d.rate >= 50 ? 'bg-warning' : 'bg-teal')}" role="progressbar" style="width: ${d.rate}%"></div>
          </div>
        </div>
      </div>
    `;
    grid.innerHTML += dayCard;
  });
}

function view_day_bookings(date_str) {
  let parts = date_str.split('-');
  let formatted_date = `${parts[2]}/${parts[1]}/${parts[0]}`;
  document.getElementById('day_modal_title').innerText = `LỊCH CÔNG SUẤT & ĐƠN ĐẶT PHÒNG NGÀY ${formatted_date}`;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/booking_calendar.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    document.getElementById('day-bookings-data').innerHTML = this.responseText;
    let modal = new bootstrap.Modal(document.getElementById('dayBookingsModal'));
    modal.show();
  };

  xhr.send(`get_day_bookings=1&date=${date_str}`);
}

function view_booking_modal(booking_id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/booking_calendar.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    document.getElementById('booking-detail-content').innerHTML = this.responseText;
    let modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
    modal.show();
  };

  xhr.send(`get_booking_details=1&booking_id=${booking_id}`);
}

window.onload = function() {
  load_calendar();
};

