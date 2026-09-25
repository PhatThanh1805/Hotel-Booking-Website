<?php 

  //frontend purpose data

  if(!defined('SITE_URL')) define('SITE_URL','http://localhost:8000/');
  if(!defined('ABOUT_IMG_PATH')) define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
  if(!defined('CAROUSEL_IMG_PATH')) define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
  if(!defined('FACILITIES_IMG_PATH')) define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/');
  if(!defined('ROOMS_IMG_PATH')) define('ROOMS_IMG_PATH',SITE_URL.'images/rooms/');
  if(!defined('USERS_IMG_PATH')) define('USERS_IMG_PATH',SITE_URL.'images/users/');

  //backend upload process needs this data

  if(!defined('UPLOAD_IMAGE_PATH')) define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/images/');
  if(!defined('ABOUT_FOLDER')) define('ABOUT_FOLDER','about/');
  if(!defined('CAROUSEL_FOLDER')) define('CAROUSEL_FOLDER','carousel/');
  if(!defined('FACILITIES_FOLDER')) define('FACILITIES_FOLDER','facilities/');
  if(!defined('ROOMS_FOLDER')) define('ROOMS_FOLDER','rooms/');
  if(!defined('USERS_FOLDER')) define('USERS_FOLDER','users/');

  // sendgrid api key

  if(!defined('SENDGRID_API_KEY')) define('SENDGRID_API_KEY',"PASTE YOUR API KEY GENERATED FROM SENDGRID WEBSITE");
  if(!defined('SENDGRID_EMAIL')) define('SENDGRID_EMAIL',"PUT YOUR EMAIL");
  if(!defined('SENDGRID_NAME')) define('SENDGRID_NAME',"Get Hotels");

  if(!function_exists('adminLogin'))
  {
    function adminLogin()
    {
      if(session_status() == PHP_SESSION_NONE){
        session_start();
      }
      if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
        echo"<script>
          window.location.href='index.php';
        </script>";
        exit;
      }
    }
  }

  if(!function_exists('redirect'))
  {
    function redirect($url){
      echo"<script>
        window.location.href='$url';
      </script>";
      exit;
    }
  }

  if(!function_exists('alert'))
  {
    function alert($type,$msg){    
      $bs_class = ($type == "success") ? "alert-success" : "alert-danger";

      echo <<<alert
        <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
          <strong class="me-3">$msg</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      alert;
    }
  }

  if(!function_exists('uploadImage'))
  {
    function uploadImage($image,$folder)
    {
      $valid_mime = ['image/jpeg','image/png','image/webp'];
      $img_mime = $image['type'];

      if(!in_array($img_mime,$valid_mime)){
        return 'inv_img'; //invalid image mime or format
      }
      else if(($image['size']/(1024*1024))>2){
        return 'inv_size'; //invalid size greater than 2mb
      }
      else{
        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".$ext";

        $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
        if(move_uploaded_file($image['tmp_name'],$img_path)){
          return $rname;
        }
        else{
          return 'upd_failed';
        }
      }
    }
  }

  if(!function_exists('deleteImage'))
  {
    function deleteImage($image, $folder)
    {
      if(unlink(UPLOAD_IMAGE_PATH.$folder.$image)){
        return true;
      }
      else{
        return false;
      }
    }
  }

  if(!function_exists('uploadSVGImage'))
  {
    function uploadSVGImage($image,$folder)
    {
      $valid_mime = ['image/svg+xml'];
      $img_mime = $image['type'];

      if(!in_array($img_mime,$valid_mime)){
        return 'inv_img'; //invalid image mime or format
      }
      else if(($image['size']/(1024*1024))>1){
        return 'inv_size'; //invalid size greater than 1mb
      }
      else{
        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".$ext";

        $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
        if(move_uploaded_file($image['tmp_name'],$img_path)){
          return $rname;
        }
        else{
          return 'upd_failed';
        }
      }
    }
  }

  if(!function_exists('uploadUserImage'))
  {
    function uploadUserImage($image)
    {
      $valid_mime = ['image/jpeg','image/png','image/webp'];
      $img_mime = $image['type'];

      if(!in_array($img_mime,$valid_mime)){
        return 'inv_img'; //invalid image mime or format
      }
      else
      {
        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".jpeg";

        $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

        if($ext == 'png' || $ext == 'PNG') {
          $img = imagecreatefrompng($image['tmp_name']);
        }
        else if($ext == 'webp' || $ext == 'WEBP') {
          $img = imagecreatefromwebp($image['tmp_name']);
        }
        else{
          $img = imagecreatefromjpeg($image['tmp_name']);
        }


        if(imagejpeg($img,$img_path,75)){
          return $rname;
        }
        else{
          return 'upd_failed';
        }
      }
    }
  }

?>
