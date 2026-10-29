<?php

// Parameters
$type = $_GET['type'];
$CKEditor = $_GET['CKEditor'];
$funcNum = $_GET['CKEditorFuncNum'];

// Image upload
if($type == 'image'){

    $allowed_extension = array(
      "png","jpg","jpeg"
    );

    // Get image file extension
    $file_extension = pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);

    if(in_array(strtolower($file_extension),$allowed_extension)){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
         }
         else{
            $protocol = 'http';
         }
        $external_link = "/public/storage/ckeditor/".date('Y').'/'.date('m').'/'.date('d').'/';
        $save_folder_1 = "../../../../../../../../public/storage/ckeditor/".date('Y').'/';
        if (!file_exists($save_folder_1)) {
            mkdir($save_folder_1, 0777, true);
        }

        $save_folder_2 = $save_folder_1.date('m').'/';
        if (!file_exists($save_folder_2)) {
            mkdir($save_folder_2, 0777, true);
        }

        $save_folder_3 = $save_folder_2.date('d').'/';
        if (!file_exists($save_folder_3)) {
            mkdir($save_folder_3, 0777, true);
        }
        $save_folder = $save_folder_3;
        $id = uniqid();
       if(move_uploaded_file($_FILES['upload']['tmp_name'], $save_folder.$id.$_FILES['upload']['name'])){
          // File path
          if(isset($_SERVER['HTTPS'])){
             $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
          }
          else{
             $protocol = 'http';
          }
          $url = $protocol."://".$_SERVER['SERVER_NAME'] .$external_link.$id.$_FILES['upload']['name'];
          // Render HTML output 
         @header('Content-type: text/html; charset=utf-8'); 
          echo '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
       }
    }

    exit;
}

if($type == 'clipboard'){
    $allowed_extension = array(
      "png","jpg","jpeg"
    );

    // Get image file extension
    $file_extension = pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);

    if(in_array(strtolower($file_extension),$allowed_extension)){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
         }
         else{
            $protocol = 'http';
         }
        $external_link = "/public/storage/ckeditor/".date('Y').'/'.date('m').'/'.date('d').'/';
        $save_folder_1 = "../../../../../../../../public/storage/ckeditor/".date('Y').'/';
        if (!file_exists($save_folder_1)) {
            $continue = mkdir($save_folder_1, 0777, true);
            die('result: ' .$continue);
        }

        $save_folder_2 = $save_folder_1.date('m').'/';
        if (!file_exists($save_folder_2)) {
            mkdir($save_folder_2, 0777, true);
        }

        $save_folder_3 = $save_folder_2.date('d').'/';
        if (!file_exists($save_folder_3)) {
            mkdir($save_folder_3, 0777,true);
        }
        $save_folder = $save_folder_3;
        $id = uniqid();
       if(move_uploaded_file($_FILES['upload']['tmp_name'], $save_folder.$id.$_FILES['upload']['name'])){
          // File path
          if(isset($_SERVER['HTTPS'])){
             $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
          }
          else{
             $protocol = 'http';
          }
          $url = $protocol."://".$_SERVER['SERVER_NAME'] .$external_link.$id.$_FILES['upload']['name'];
       
          echo json_encode(["uploaded" => 1,"fileName"=> $id.$_FILES['upload']['name'],"url" => $url]);
       }
    }

    exit;
}

// File upload
if($type == 'file'){

    $allowed_extension = array(
       "doc","pdf","docx"
    );

    // Get image file extension
    $file_extension = pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);

    if(in_array(strtolower($file_extension),$allowed_extension)){

       if(move_uploaded_file($_FILES['upload']['tmp_name'], "uploads/".$_FILES['upload']['name'])){
          // File path
          if(isset($_SERVER['HTTPS'])){
              $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
          }
          else{
              $protocol = 'http';
          }

          $url = $protocol."://".$_SERVER['SERVER_NAME'] ."/ckeditor_fileupload/uploads/".$_FILES['upload']['name'];
         
          echo '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
       }

    }

    exit;
}