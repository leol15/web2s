<?php
  # php code to invert a pdf with bash cmds
  if(isset($_FILES['file'])){
     $errors= array();
     $file_name = $_FILES['file']['name'];
     $file_size = $_FILES['file']['size'];
     $file_tmp = $_FILES['file']['tmp_name'];
     $file_type = $_FILES['file']['type'];
     $file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));

     $extensions= array("pdf");

     if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
     }

     if(empty($errors)==true) {
        move_uploaded_file($file_tmp,"./processing/".$file_name);
        echo "Success";
     }else{
        print_r($errors);
     }
  }
?>
