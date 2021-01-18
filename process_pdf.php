<?php
  # php code to invert a pdf with bash cmds
 // header( 'Content-type: text/html; charset=utf-8' ); // said to resolve flush()
  header('Content-Encoding: none');
  if(isset($_FILES['fileToUpload'])){
     $errors= array();
     $file_name = $_FILES['fileToUpload']['name'];
     $file_size = $_FILES['fileToUpload']['size'];
     $file_tmp = $_FILES['fileToUpload']['tmp_name'];
     $file_type = $_FILES['fileToUpload']['type'];
     $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));

     $extensions= array("pdf");

     # escape shell
     $file_name = escapeshellcmd($file_name);
     $file_name = str_replace(" ", "_", $file_name);

     if(in_array($file_ext,$extensions) === false){
        $errors[]="extension not allowed, please choose a PDF file.";
     }
     
     //ob_end_flush(); // to get php's internal buffers out into the operating system
     //flush();
     if(empty($errors)==true) {
     	# make a dir
		echo "<p>file received</p>";
     	$path = "./pdf_processing/".$file_name;
     	mkdir($path, 0777,  $recursive = true);
        move_uploaded_file($file_tmp,$path."/".$file_name);
        # now process the pdf
        echo "<p>converting to pngs</p>";
        passthru("cd ".$path." && pdftoppm -rx 200 -ry 200 ".$file_name." tmp-1 -png");
        echo "<p>inverting colors</p>";
		passthru("cd ".$path." && ls -1 tmp-1*.png | xargs ".
				"-P10 -i convert {} -channel RGB -negate tmp-2-{}");
		usleep(500000); // 0.5 sec
		echo "<p>assembling to pdf</p>";
		passthru("cd ".$path." && convert tmp-2-*.png neg-".$file_name);
		usleep(500000); // 0.5 sec
		echo "<p>moving file</p>";
		passthru("mv ".$path."/neg-".$file_name." pdf_processed/ && rm -rf ".$path);
		usleep(500000); // 0.5 sec
		echo "<p>removing build specs</p>";
		# remove processing also if faileds
		passthru("rm -rf ./processing/*");
		usleep(500000); // 0.5 sec
        echo "<p>Success! <a href='./pdf_processed/neg-".$file_name."'>download here</a></p>";
     }else{
     	echo "Failed";
        print_r($errors);
     }
  } else {
	print_r($_FILES);
  	echo "this is not an upload?";
  }
?>
