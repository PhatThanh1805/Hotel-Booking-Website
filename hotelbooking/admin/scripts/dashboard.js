let revenueChartInstance = null;
let occupancyChartInstance = null;
let statusChartInstance = null;

function formatVNNDur(amount) {
  return new Intl.NumberFormat('vi-VN').format(amount || 0) + ' VNĐ';
}

function booking_analytics(period = 1) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    let data = JSON.parse(this.responseText);

    // Dynamic Metric Cards
    document.getElementById('total_bookings').textContent = new Intl.NumberFormat('vi-VN').format(data.total_bookings);
    document.getElementById('total_amt').textContent = formatVNNDur(data.total_amt * 1000);

    document.getElementById('active_bookings').textContent = new Intl.NumberFormat('vi-VN').format(data.active_bookings);
    document.getElementById('active_amt').textContent = formatVNNDur(data.active_amt * 1000);

    document.getElementById('cancelled_bookings').textContent = new Intl.NumberFormat('vi-VN').format(data.cancelled_bookings);
    document.getElementById('cancelled_amt').textContent = formatVNNDur(data.cancelled_amt * 1000);

    if (document.getElementById('avg_amt')) {
      document.getElementById('avg_amt').textContent = formatVNNDur(data.avg_amt);
    }
    if (document.getElementById('cancel_rate')) {
      document.getElementById('cancel_rate').textContent = data.cancel_rate + '%';
    }

    // Update Revenue & Booking Trend Chart
    renderRevenueChart(data.chart_labels, data.chart_revenue, data.chart_bookings);

    // Update Booking Status Breakdown Chart
    renderStatusChart(data.status_counts);
  };

  xhr.send('booking_analytics=1&period=' + period);
}

function room_analytics() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    let data = JSON.parse(this.responseText);

    if (document.getElementById('occupancy_rate_val')) {
      document.getElementById('occupancy_rate_val').textContent = data.occupancy_rate + '%';
    }
    if (document.getElementById('occupancy_bar')) {
      document.getElementById('occupancy_bar').style.width = Math.min(100, Math.max(0, data.occupancy_rate)) + '%';
    }
    if (document.getElementById('room_stat_total')) document.getElementById('room_stat_total').textContent = data.total;
    if (document.getElementById('room_stat_avail')) document.getElementById('room_stat_avail').textContent = data.available;
    if (document.getElementById('room_stat_occup')) document.getElementById('room_stat_occup').textContent = data.occupied;
    if (document.getElementById('room_stat_bookd')) document.getElementById('room_stat_bookd').textContent = data.booked;
    if (document.getElementById('room_stat_clean')) document.getElementById('room_stat_clean').textContent = data.cleaning;
    if (document.getElementById('room_stat_maint')) document.getElementById('room_stat_maint').textContent = data.maintenance;

    // Render Room Occupancy Doughnut Chart
    renderOccupancyChart([data.available, data.occupied, data.booked, data.cleaning, data.maintenance]);
  };

  xhr.send('room_analytics=1');
}

function user_analytics(period = 1) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    let data = JSON.parse(this.responseText);

    document.getElementById('total_new_reg').textContent = new Intl.NumberFormat('vi-VN').format(data.total_new_reg);
    document.getElementById('total_queries').textContent = new Intl.NumberFormat('vi-VN').format(data.total_queries);
    document.getElementById('total_reviews').textContent = new Intl.NumberFormat('vi-VN').format(data.total_reviews);
  };

  xhr.send('user_analytics=1&period=' + period);
}

// Render Revenue Chart
function renderRevenueChart(labels, revenueData, bookingsData) {
  const ctx = document.getElementById('revenueChart');
  if (!ctx) return;

  if (revenueChartInstance) {
    revenueChartInstance.destroy();
  }

  revenueChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels && labels.length ? labels : ['Chưa có dữ liệu'],
      datasets: [
        {
          label: 'Doanh Thu (VNĐ)',
          data: revenueData && revenueData.length ? revenueData : [0],
          type: 'line',
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          borderWidth: 3,
          fill: true,
          tension: 0.3,
          yAxisID: 'y'
        },
        {
          label: 'Số Đơn Đặt Phòng',
          data: bookingsData && bookingsData.length ? bookingsData : [0],
          type: 'bar',
          backgroundColor: 'rgba(32, 201, 151, 0.75)',
          borderColor: '#20c997',
          borderWidth: 1,
          borderRadius: 6,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: { family: "'Be Vietnam Pro', sans-serif", weight: '600' }
          }
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              if (context.dataset.label.includes('Doanh Thu')) {
                return ' ' + context.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.raw) + ' VNĐ';
              }
              return ' ' + context.dataset.label + ': ' + context.raw + ' đơn';
            }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: "'Be Vietnam Pro', sans-serif" } }
        },
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: { display: true, text: 'Doanh Thu (VNĐ)', font: { family: "'Be Vietnam Pro', sans-serif", weight: '600' } },
          ticks: {
            callback: function (value) {
              if (value >= 1000000) return (value / 1000000).toFixed(1) + ' tr';
              if (value >= 1000) return (value / 1000).toFixed(0) + ' k';
              return value;
            }
          }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          grid: { drawOnChartArea: false },
          title: { display: true, text: 'Số Đơn Đặt', font: { family: "'Be Vietnam Pro', sans-serif", weight: '600' } },
          ticks: { precision: 0 }
        }
      }
    }
  });
}

// Render Room Occupancy Doughnut Chart
function renderOccupancyChart(counts) {
  const ctx = document.getElementById('occupancyChart');
  if (!ctx) return;

  if (occupancyChartInstance) {
    occupancyChartInstance.destroy();
  }

  occupancyChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Đang Trống', 'Đang Có Khách', 'Đã Được Đặt', 'Đang Dọn Dẹp', 'Bảo Trì'],
      datasets: [{
        data: counts,
        backgroundColor: [
          '#198754', // Green - Available
          '#dc3545', // Red - Occupied
          '#0d6efd', // Blue - Booked
          '#ffc107', // Yellow - Cleaning
          '#6c757d'  // Gray - Maintenance
        ],
        borderWidth: 2,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { font: { family: "'Be Vietnam Pro', sans-serif", weight: '500' } }
        }
      },
      cutout: '70%'
    }
  });
}

// Render Booking Status Doughnut Chart
function renderStatusChart(counts) {
  const ctx = document.getElementById('statusChart');
  if (!ctx) return;

  if (statusChartInstance) {
    statusChartInstance.destroy();
  }

  statusChartInstance = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Thành Công / Hoạt Động', 'Đã Hủy', 'Thanh Toán Thất Bại', 'Chờ Xử Lý'],
      datasets: [{
        data: counts,
        backgroundColor: [
          '#20c997', // Teal
          '#dc3545', // Red
          '#fd7e14', // Orange
          '#6f42c1'  // Purple
        ],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { font: { family: "'Be Vietnam Pro', sans-serif", weight: '500' } }
        }
      }
    }
  });
}

window.onload = function () {
  booking_analytics(1);
  room_analytics();
  user_analytics(1);
};