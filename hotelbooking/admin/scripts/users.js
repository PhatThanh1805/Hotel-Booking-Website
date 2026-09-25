function get_users()
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/users.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('users-data').innerHTML = this.responseText;
  }

  xhr.send('get_users');
}

function toggle_status(id,val)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/users.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    if(this.responseText==1){
      alert('success','Thay đổi trạng thái tài khoản thành công!');
      get_users();
    }
    else{
      alert('error','Lỗi máy chủ! Không thể cập nhật trạng thái.');
    }
  }

  xhr.send('toggle_status='+id+'&value='+val);
}

function toggle_role(id, role_val)
{
  let role_title = (role_val === 'staff') ? 'Nhân viên' : 'Khách hàng';
  if(confirm("Bạn có chắc chắn muốn thay đổi vai trò tài khoản này thành " + role_title + " không?"))
  {
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/users.php",true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
      if(this.responseText==1){
        alert('success','Đã cập nhật vai trò tài khoản thành: ' + role_title);
        get_users();
      }
      else{
        alert('error','Cập nhật vai trò thất bại!');
      }
    }

    xhr.send('toggle_role='+id+'&role_val='+role_val);
  }
}

function remove_user(user_id)
{
  if(confirm("Bạn có chắc chắn muốn xóa tài khoản chưa xác thực này không?"))
  {
    let data = new FormData();
    data.append('user_id',user_id);
    data.append('remove_user','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/users.php",true);

    xhr.onload = function()
    {
      if(this.responseText == 1){
        alert('success','Đã xóa tài khoản thành công!');
        get_users();
      }
      else{
        alert('error','Xóa tài khoản thất bại!');
      }
    }
    xhr.send(data);
  }
}

function search_user(username){
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/users.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    document.getElementById('users-data').innerHTML = this.responseText;
  }

  xhr.send('search_user&name='+username);
}

window.onload = function(){
  get_users();
}