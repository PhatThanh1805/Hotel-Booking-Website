let current_status_filter = 'all';
let current_search_query = '';

let add_room_form = document.getElementById('add_room_form');
    
add_room_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_room();
});

function add_room()
{
  let data = new FormData();
  data.append('add_room','');
  data.append('name',add_room_form.elements['name'].value);
  data.append('area',add_room_form.elements['area'].value);
  data.append('price',add_room_form.elements['price'].value);
  data.append('quantity',add_room_form.elements['quantity'].value);
  data.append('adult',add_room_form.elements['adult'].value);
  data.append('children',add_room_form.elements['children'].value);
  data.append('desc',add_room_form.elements['desc'].value);

  let features = [];
  add_room_form.elements['features'].forEach(el =>{
    if(el.checked){
      features.push(el.value);
    }
  });

  let facilities = [];
  add_room_form.elements['facilities'].forEach(el =>{
    if(el.checked){
      facilities.push(el.value);
    }
  });

  data.append('features',JSON.stringify(features));
  data.append('facilities',JSON.stringify(facilities));

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('add-room');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Đã thêm loại phòng mới!');
      add_room_form.reset();
      get_all_rooms();
      get_room_stats();
    }
    else{
      alert('error','Lỗi hệ thống!');
    }
  }

  xhr.send(data);
}

function get_all_rooms()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('room-data').innerHTML = this.responseText;
  }

  let params = 'get_all_rooms=1' +
               '&status_filter=' + encodeURIComponent(current_status_filter) +
               '&search_query=' + encodeURIComponent(current_search_query);

  xhr.send(params);
}

function get_room_stats()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    try {
      let data = JSON.parse(this.responseText);
      if(document.getElementById('stat_available')) document.getElementById('stat_available').innerText = data.available || 0;
      if(document.getElementById('stat_cleaning')) document.getElementById('stat_cleaning').innerText = data.cleaning || 0;
      if(document.getElementById('stat_occupied')) document.getElementById('stat_occupied').innerText = data.occupied || 0;
      if(document.getElementById('stat_booked')) document.getElementById('stat_booked').innerText = data.booked || 0;
      if(document.getElementById('stat_maintenance')) document.getElementById('stat_maintenance').innerText = data.maintenance || 0;
      if(document.getElementById('stat_total')) document.getElementById('stat_total').innerText = data.total || 0;
    } catch(e) {
      console.error("Error parsing stats data", e);
    }
  }

  xhr.send('get_room_stats=1');
}

function filterByStatus(status, btnElement)
{
  current_status_filter = status;
  
  // Highlight active tab
  let tabs = document.querySelectorAll('#status-filter-tabs .filter-tab');
  tabs.forEach(tab => {
    tab.classList.remove('active', 'btn-dark', 'btn-success', 'btn-warning', 'btn-danger', 'btn-primary', 'btn-secondary');
    if(!tab.className.includes('btn-outline-')) {
      // Restore outline default
    }
  });

  if(btnElement) {
    btnElement.classList.add('active');
  }

  get_all_rooms();
}

function onSearchInput()
{
  let input = document.getElementById('search_room_input');
  current_search_query = input ? input.value : '';
  get_all_rooms();
}

let edit_room_form = document.getElementById('edit_room_form');

function edit_details(id)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);

    edit_room_form.elements['name'].value = data.roomdata.name;
    edit_room_form.elements['area'].value = data.roomdata.area;
    edit_room_form.elements['price'].value = data.roomdata.price;
    edit_room_form.elements['quantity'].value = data.roomdata.quantity;
    edit_room_form.elements['adult'].value = data.roomdata.adult;
    edit_room_form.elements['children'].value = data.roomdata.children;
    edit_room_form.elements['desc'].value = data.roomdata.description;
    edit_room_form.elements['room_id'].value = data.roomdata.id;

    edit_room_form.elements['features'].forEach(el =>{
      if(data.features.includes(Number(el.value))){
        el.checked = true;
      }
    });

    edit_room_form.elements['facilities'].forEach(el =>{
      if(data.facilities.includes(Number(el.value))){
        el.checked = true;
      }
    });
  }

  xhr.send('get_room='+id);
}

edit_room_form.addEventListener('submit',function(e){
  e.preventDefault();
  submit_edit_room();
});

function submit_edit_room()
{
  let data = new FormData();
  data.append('edit_room','');
  data.append('room_id',edit_room_form.elements['room_id'].value);
  data.append('name',edit_room_form.elements['name'].value);
  data.append('area',edit_room_form.elements['area'].value);
  data.append('price',edit_room_form.elements['price'].value);
  data.append('quantity',edit_room_form.elements['quantity'].value);
  data.append('adult',edit_room_form.elements['adult'].value);
  data.append('children',edit_room_form.elements['children'].value);
  data.append('desc',edit_room_form.elements['desc'].value);

  let features = [];
  edit_room_form.elements['features'].forEach(el =>{
    if(el.checked){
      features.push(el.value);
    }
  });

  let facilities = [];
  edit_room_form.elements['facilities'].forEach(el =>{
    if(el.checked){
      facilities.push(el.value);
    }
  });

  data.append('features',JSON.stringify(features));
  data.append('facilities',JSON.stringify(facilities));

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload = function(){
    var myModal = document.getElementById('edit-room');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 1){
      alert('success','Cập nhật thông tin phòng thành công!');
      edit_room_form.reset();
      get_all_rooms();
    }
    else{
      alert('error','Lỗi hệ thống!');
    }
  }

  xhr.send(data);
}

function change_status(room_id, val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText == 1){
      let statusNames = {
        1: 'Phòng Đang Trống (Available)',
        2: 'Đang Dọn Dẹp (Cleaning)',
        3: 'Đang Có Khách (Occupied)',
        4: 'Đã Được Đặt (Booked)',
        0: 'Tạm Dừng / Bảo Trì'
      };
      alert('success', 'Đã chuyển trạng thái phòng sang: ' + (statusNames[val] || val));
      get_all_rooms();
      get_room_stats();
    }
    else{
      alert('error','Cập nhật trạng thái phòng thất bại!');
    }
  }

  xhr.send('change_status=1&room_id='+room_id+'&val='+val);
}

function toggle_status(id,val)
{
  change_status(id, val);
}

let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit',function(e){
  e.preventDefault();
  add_image();
});

function add_image()
{
  let data = new FormData();
  data.append('image',add_image_form.elements['image'].files[0]);
  data.append('room_id',add_image_form.elements['room_id'].value);
  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 'inv_img'){
      alert('error','Chỉ chấp nhận định dạng JPG, WEBP hoặc PNG!','image-alert');
    }
    else if(this.responseText == 'inv_size'){
      alert('error','Kích thước ảnh phải nhỏ hơn 2MB!','image-alert');
    }
    else if(this.responseText == 'upd_failed'){
      alert('error','Tải ảnh lên thất bại. Lỗi máy chủ!','image-alert');
    }
    else{
      alert('success','Đã thêm ảnh mới cho phòng!','image-alert');
      room_images(add_image_form.elements['room_id'].value,document.querySelector("#room-images .modal-title").innerText)
      add_image_form.reset();
    }
  }
  xhr.send(data);
}

function room_images(id,rname)
{
  document.querySelector("#room-images .modal-title").innerText = rname;
  add_image_form.elements['room_id'].value = id;
  add_image_form.elements['image'].value = '';

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('room-image-data').innerHTML = this.responseText;
  }

  xhr.send('get_room_images='+id);
}

function rem_image(img_id,room_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('room_id',room_id);
  data.append('rem_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Đã xóa ảnh thành công!','image-alert');
      room_images(room_id,document.querySelector("#room-images .modal-title").innerText);
    }
    else{
      alert('error','Xóa ảnh thất bại!','image-alert');
    }
  }
  xhr.send(data);  
}

function thumb_image(img_id,room_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('room_id',room_id);
  data.append('thumb_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload = function()
  {
    if(this.responseText == 1){
      alert('success','Đã thay đổi ảnh đại diện!','image-alert');
      room_images(room_id,document.querySelector("#room-images .modal-title").innerText);
    }
    else{
      alert('error','Cập nhật ảnh đại diện thất bại!','image-alert');
    }
  }
  xhr.send(data);  
}

function remove_room(room_id)
{
  if(confirm("Bạn có chắc chắn muốn xóa phòng này không?"))
  {
    let data = new FormData();
    data.append('room_id',room_id);
    data.append('remove_room','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/rooms.php",true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success','Đã xóa phòng thành công!');
        get_all_rooms();
        get_room_stats();
      }
      else{
        alert('error','Xóa phòng thất bại!');
      }
    }
    xhr.send(data);
  }

}

window.onload = function(){
  get_all_rooms();
  get_room_stats();
}