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
        $errors[]="extension not allowed, please choose a PDF file.";
     }

     if(empty($errors)==true) {
     	# make a dir
     	$path = "./processing/".$file_name;
     	mkdir($path, 0777,  $recursive = true);
        move_uploaded_file($file_tmp,$path."/".$file_name);
        # now process the pdf
        passthru("cd ".$path." && ".
        		"pdftoppm -rx 200 -ry 200 ".$file_name." out -png && ".
				"convert out-*.png -channel RGB -negate out-neg.png && ".
				"convert out-neg*.png neg-".$file_name ." && ".
				"mv neg-".$file_name." ../../processed/ && cd ../.. && rm -rf ".$path);
		# remove processing also if faileds
		passthru("rm -rf ./processing/*");
        echo "Success! <a href='./processed/neg-".$file_name."'>here</a>";
     }else{
     	echo "Failed";
        print_r($errors);
     }
  } else {
	print_r($_FILES);
  	echo "this is not an upload?";
  }
?>
