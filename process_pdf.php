<?php
  # php code to invert a pdf with bash cmds
  if(isset($_FILES['fileToUpload'])){
     $errors= array();
     $file_name = $_FILES['fileToUpload']['name'];
     $file_size = $_FILES['fileToUpload']['size'];
     $file_tmp = $_FILES['fileToUpload']['tmp_name'];
     $file_type = $_FILES['fileToUpload']['type'];
     $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));

     $extensions= array("pdf");

     if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
     }

     if(empty($errors)==true) {
        move_uploaded_file($file_tmp,"./processing/".$file_name);
        echo "Success";
     }else{
     	echo "Failed";
        print_r($errors);
     }
  } else {
	print_r($_FILES);
  	echo "this is not an upload?";
  }
?>
