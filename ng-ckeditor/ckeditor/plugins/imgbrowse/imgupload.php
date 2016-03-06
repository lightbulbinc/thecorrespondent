<?php
// PHP Upload Script for CKEditor:  http://coursesweb.net/

// HERE SET THE PATH TO THE FOLDER WITH IMAGES ON YOUR SERVER (RELATIVE TO THE ROOT OF YOUR WEBSITE ON SERVER)
$upload_dir = '/images/';

// HERE PERMISSIONS FOR IMAGE
$imgsets = array(
 'maxsize' => 2000,          // maximum file size, in KiloBytes (2 MB)
 'maxwidth' => 900,          // maximum allowed width, in pixels
 'maxheight' => 800,         // maximum allowed height, in pixels
 'minwidth' => 10,           // minimum allowed width, in pixels
 'minheight' => 10,          // minimum allowed height, in pixels
 'type' => array('bmp', 'gif', 'jpg', 'jpe', 'png')        // allowed extensions
);

$re = '';

if(isset($_FILES['upload']) && strlen($_FILES['upload']['name']) > 1) {
  $upload_dir = trim($upload_dir, '/') .'/';
  $img_name = basename($_FILES['upload']['name']);

  // get protocol and host name to send the absolute image path to CKEditor
  $protocol = !empty($_SERVER['HTTP']) ? 'http://' : 'http://';
  $site = $protocol. $_SERVER['SERVER_NAME'] .'/';

  $uploadpath = $_SERVER['DOCUMENT_ROOT'] .'/'. $upload_dir . $img_name;       // full file path
  $sepext = explode('.', strtolower($_FILES['upload']['name']));
  $type = end($sepext);       // gets extension
  list($width, $height) = getimagesize($_FILES['upload']['tmp_name']);     // gets image width and height
  $err = '';         // to store the errors

  // Checks if the file has allowed type, size, width and height (for images)
  if(!in_array($type, $imgsets['type'])) $err .= 'The file: '. $_FILES['upload']['name']. ' has not the allowed extension type.';
  if($_FILES['upload']['size'] > $imgsets['maxsize']*1000) $err .= '\\n Maximum file size must be: '. $imgsets['maxsize']. ' KB.';
  // if(isset($width) && isset($height)) {
  //   if($width > $imgsets['maxwidth'] || $height > $imgsets['maxheight']) $err .= '\\n Width x Height = '. $width .' x '. $height .' \\n The maximum Width x Height must be: '. $imgsets['maxwidth']. ' x '. $imgsets['maxheight'];
  //   if($width < $imgsets['minwidth'] || $height < $imgsets['minheight']) $err .= '\\n Width x Height = '. $width .' x '. $height .'\\n The minimum Width x Height must be: '. $imgsets['minwidth']. ' x '. $imgsets['minheight'];
  // }

  // If no errors, upload the image, else, output the errors
  if($err == '') {
    if(move_uploaded_file($_FILES['upload']['tmp_name'], $uploadpath)) {
      $server_name = $_SERVER['SERVER_NAME'] == 'localhost' ? 'localhost' : '182.50.133.83';
      $conn = new mysqli($server_name,'manojkt','krishna@2005','thecorrespondentdotin');
      $imagefilename = basename($_FILES["upload"]["name"]);
      $thumbnailfilename = makeThumbnail($uploadpath,$upload_dir,$imagefilename);
      $conn->query("insert into storyimage (imagefilename,thumbnailfilename) values('$imagefilename','$thumbnailfilename');");
      $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
      $url = $site. $upload_dir . $img_name;
      $message = $img_name .' successfully uploaded: \\n- Size: '. number_format($_FILES['upload']['size']/1024, 3, '.', '') .' KB \\n- Image Width x Height: '. $width. ' x '. $height;
      $re = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')";
    }
    else $re = 'alert("Unable to upload the file")';
  }
  else $re = 'alert("'. $err .'")';
}
function makeThumbnail($uploadpath,$upload_dir,$imagefilename)
{
  $thumbnailfilename = preg_replace('/\.(.+?)$/','.thumb.\1',$imagefilename);
  file_put_contents('a',$thumbnailfilename . "\n");
  file_put_contents('a',$uploadpath . "\n",FILE_APPEND);
  if ($_SERVER['SERVER_NAME'] != 'localhost'){
    $thumbnail_width = 134;
    $thumbnail_height = 189;
    $thumb_beforeword = "thumb";
    $arr_image_details = getimagesize($uploadpath); // pass id to thumb name
    $original_width = $arr_image_details[0];
    $original_height = $arr_image_details[1];
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);

    if ($arr_image_details[2] == 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
      $old_image = $imgcreatefrom($uploadpath);
      $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
      imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
      $imgt($new_image, $_SERVER['DOCUMENT_ROOT'] .'/'. $upload_dir . $thumbnailfilename);
    }
  }
  return $thumbnailfilename;
}
echo "<script>$re;</script>";
