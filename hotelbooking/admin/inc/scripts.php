<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

  function alert(type,msg,position='body')
  {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let element = document.createElement('div');
    element.innerHTML = `
      <div class="alert ${bs_class} alert-dismissible fade show shadow-lg border-0 rounded-3" role="alert">
        <strong class="me-3">${msg}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    if(position=='body'){
      document.body.append(element);
      element.classList.add('custom-alert');
    }
    else{
      let pos_el = document.getElementById(position);
      if(pos_el) pos_el.appendChild(element);
    }
    setTimeout(remAlert, 3000);
  }

  function remAlert(){
    let alerts = document.getElementsByClassName('alert');
    if(alerts.length > 0){
      alerts[0].remove();
    }
  }

    
  function setActive()
  {
    let navbar = document.getElementById('dashboard-menu');
    if(!navbar) return;
    let a_tags = navbar.getElementsByTagName('a');

    for(i=0; i<a_tags.length; i++)
    {
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      if(document.location.href.indexOf(file_name) >= 0){
        a_tags[i].classList.add('active');
        a_tags[i].classList.add('bg-teal');
      }

    }
  }
  setActive();

  // Dark Mode System Logic
  function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const isDark = savedTheme === 'dark';
    if (isDark) {
      document.documentElement.classList.add('dark-mode');
      if (document.body) document.body.classList.add('dark-mode');
    } else {
      document.documentElement.classList.remove('dark-mode');
      if (document.body) document.body.classList.remove('dark-mode');
    }
    updateThemeToggleButtons(isDark);
  }

  function toggleDarkMode() {
    const isDark = document.documentElement.classList.toggle('dark-mode');
    if (document.body) document.body.classList.toggle('dark-mode', isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeToggleButtons(isDark);
  }

  function updateThemeToggleButtons(isDark) {
    const btns = document.querySelectorAll('.btn-theme-toggle');
    btns.forEach(btn => {
      const icon = btn.querySelector('i');
      const text = btn.querySelector('.theme-text');
      if (isDark) {
        if(icon) icon.className = 'bi bi-sun-fill text-warning me-1';
        if(text) text.textContent = 'Sáng';
        btn.title = 'Chuyển sang Chế độ Sáng';
      } else {
        if(icon) icon.className = 'bi bi-moon-stars-fill text-warning me-1';
        if(text) text.textContent = 'Tối';
        btn.title = 'Chuyển sang Chế độ Tối';
      }
    });
  }

  document.addEventListener('DOMContentLoaded', initTheme);
  initTheme();
</script>