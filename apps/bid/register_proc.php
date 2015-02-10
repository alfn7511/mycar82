<?php 
	// ######################################################################
	// Initial
	// ######################################################################
	define("_CONF_DBCON", true);
	define("_LOAD_HTML", true);
	define("_LOAD_JAVASCRIPT", true);
	define("_LOAD_RANDOM", true);
	require_once "../init.php";


	// ######################################################################
	// Main Routine
	// ######################################################################	
	
	// ######################################################################
	// check order variables, require field's
	// ######################################################################
	$mode = (isset($_POST['mode'])) ? trim($_POST['mode']) : "";
	
	$bid_idx = (isset($_POST['bid_idx'])) ? trim($_POST['bid_idx']) : 0;	
	$bid_name = (isset($_POST['bid_name'])) ? trim($_POST['bid_name']) : "" ;	
	$bid_phone1 = (isset($_POST['bid_phone1'])) ? trim($_POST['bid_phone1']) : "" ;
	$bid_phone2 = (isset($_POST['bid_phone2'])) ? trim($_POST['bid_phone2']) : "" ;
	$bid_phone3 = (isset($_POST['bid_phone3'])) ? trim($_POST['bid_phone3']) : "" ;
	if($bid_phone1<>"") $bid_phone = $bid_phone1.$bid_phone2.$bid_phone3;
	
	$bid_maker = (isset($_POST['bid_maker'])) ? trim($_POST['bid_maker']) : "" ;
	$bid_car_name = (isset($_POST['bid_car_name'])) ? trim($_POST['bid_car_name']) : "" ;
	$bid_car_age = (isset($_POST['bid_car_age'])) ? trim($_POST['bid_car_age']) : "" ;
	$bid_car_num = (isset($_POST['bid_car_num'])) ? trim($_POST['bid_car_num']) : "" ;
	$bid_mileage = (isset($_POST['bid_mileage'])) ? trim($_POST['bid_mileage']) : "" ;
	$bid_capacity = (isset($_POST['bid_capacity'])) ? trim($_POST['bid_capacity']) : "" ;
	$bid_sales_area = (isset($_POST['bid_sales_area'])) ? trim($_POST['bid_sales_area']) : "" ;
	$bid_transmission = (isset($_POST['bid_transmission'])) ? trim($_POST['bid_transmission']) : "" ;
	$bid_fuel = (isset($_POST['bid_fuel'])) ? trim($_POST['bid_fuel']) : "" ;
	
	$bid_car_img1 = (isset($_POST['bid_car_img1'])) ? trim($_POST['bid_car_img1']) : "" ;
	$bid_car_img2 = (isset($_POST['bid_car_img2'])) ? trim($_POST['bid_car_img2']) : "" ;
	$bid_car_img3 = (isset($_POST['bid_car_img3'])) ? trim($_POST['bid_car_img3']) : "" ;
	$bid_car_img4 = (isset($_POST['bid_car_img4'])) ? trim($_POST['bid_car_img4']) : "" ;
	$bid_car_img5 = (isset($_POST['bid_car_img5'])) ? trim($_POST['bid_car_img5']) : "" ;
	$bid_car_img6 = (isset($_POST['bid_car_img6'])) ? trim($_POST['bid_car_img6']) : "" ;
	$bid_car_img7 = (isset($_POST['bid_car_img7'])) ? trim($_POST['bid_car_img7']) : "" ;
	$bid_car_img8 = (isset($_POST['bid_car_img8'])) ? trim($_POST['bid_car_img8']) : "" ;
	
	$bid_accident_check = (isset($_POST['bid_accident_check'])) ? trim($_POST['bid_accident_check']) : "" ;
	$bid_car_owner_check = (isset($_POST['bid_car_owner_check'])) ? trim($_POST['bid_car_owner_check']) : "" ;
	
	$bid_etc = (isset($_POST['bid_etc'])) ? trim($_POST['bid_etc']) : "" ;
	
	$bid_opt_inout = (isset($_POST['bid_opt_inout_val'])) ? trim($_POST['bid_opt_inout_val']) : "" ;
	$bid_opt_convenience = (isset($_POST['bid_opt_convenience_val'])) ? trim($_POST['bid_opt_convenience_val']) : "" ;
	$bid_opt_safety = (isset($_POST['bid_opt_safety_val'])) ? trim($_POST['bid_opt_safety_val']) : "" ;
	$bid_opt_device = (isset($_POST['bid_opt_device_val'])) ? trim($_POST['bid_opt_device_val']) : "" ;
	
	$bid_request_time = (isset($_POST['bid_request_time'])) ? trim($_POST['bid_request_time']) : "" ;	
	
	/* ######################### 입찰코드 ##################################### */
	if($mode=="add"){
		$rand = $randObj->getInteger(1000,9999);
		$bid_code = "B".date("YmdHi").$rand;
		$bid_state = 0;
	}else{
		$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "" ;
		$bid_state = (isset($_POST['bid_state'])) ? trim($_POST['bid_state']) : 0 ;
	}
	/* ######################### 입찰코드 // ##################################### */
	
	
	  $bid_car_img_1 = "";
	  $bid_car_img_2 = "";
	  $bid_car_img_3 = "";
	  $bid_car_img_4 = "";
	  $bid_car_img_5 = "";
	  $bid_car_img_6 = "";
	  $bid_car_img_7 = "";
	  $bid_car_img_8 = "";
  	
	/* ############################ 챠량사진업로드 1 ################################*/
	if($_FILES['bid_car_img1']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img1"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_1";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img1']['name'])));
		$bid_car_img_1 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img1"]["tmp_name"], "../../data/bid_reg/$bid_car_img_1");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img1"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.1";
			exit;
		}
	}		
	/* ############################ // 챠량사진업로드 1 ################################*/	
	  
	/* ############################ 챠량사진업로드 2 ################################*/
	if($_FILES['bid_car_img2']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img2"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_2";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img2']['name'])));
		$bid_car_img_2 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img2"]["tmp_name"], "../../data/bid_reg/$bid_car_img_2");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img2"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.2";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 2 ################################*/
	
	/* ############################ 챠량사진업로드 3 ################################*/
	if($_FILES['bid_car_img3']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img3"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_3";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img3']['name'])));
		$bid_car_img_3 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img3"]["tmp_name"], "../../data/bid_reg/$bid_car_img_3");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img3"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.3";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 3 ################################*/	
	
		/* ############################ 챠량사진업로드 4 ################################*/
	if($_FILES['bid_car_img4']['tmp_name']){
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img4"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_4";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img4']['name'])));
		$bid_car_img_4 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img4"]["tmp_name"], "../../data/bid_reg/$bid_car_img_4");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img4"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.4";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 4 ################################*/
	
	/* ############################ 챠량사진업로드 5 ################################*/
	if($_FILES['bid_car_img5']['tmp_name']){
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img5"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_5";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img5']['name'])));
		$bid_car_img_5 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img5"]["tmp_name"], "../../data/bid_reg/$bid_car_img_5");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img5"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.5";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 5 ################################*/
	
	/* ############################ 챠량사진업로드 6 ################################*/
	if($_FILES['bid_car_img6']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img6"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_6";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img6']['name'])));
		$bid_car_img_6 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img6"]["tmp_name"], "../../data/bid_reg/$bid_car_img_6");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img6"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.6";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 6 ################################*/		
	
	/* ############################ 챠량사진업로드 7 ################################*/
	if($_FILES['bid_car_img7']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img7"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_7";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img7']['name'])));
		$bid_car_img_7 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img7"]["tmp_name"], "../../data/bid_reg/$bid_car_img_7");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img7"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.7";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 7 ################################*/	

	/* ############################ 챠량사진업로드 8 ################################*/
	if($_FILES['bid_car_img8']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["bid_car_img8"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $bid_code."_carimg_8";
		$ext = end(explode(".", strtolower($_FILES['bid_car_img8']['name'])));
		$bid_car_img_8 = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["bid_car_img8"]["tmp_name"], "../../data/bid_reg/$bid_car_img_8");
		if(!$tmp_result) {
			echo $_FILES["bid_car_img8"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.8";
			exit;
		}
	}		
	/* ################################### // 챠량사진업로드 #######################################*/
	
	if($mode=="add"){
		
		$sql = "
			INSERT INTO bid_info(
			   bid_code
			  ,bid_name
			  ,bid_phone
			  ,bid_maker
			  ,bid_car_name
			  ,bid_car_age
			  ,bid_car_num
			  ,bid_mileage
			  ,bid_capacity
			  ,bid_sales_area
			  ,bid_transmission
			  ,bid_fuel
			  ,bid_car_img1
			  ,bid_car_img2
			  ,bid_car_img3
			  ,bid_car_img4
			  ,bid_car_img5
			  ,bid_car_img6
			  ,bid_car_img7
			  ,bid_car_img8
			  ,bid_accident_check
			  ,bid_car_owner_check
			  ,bid_etc
			  ,bid_opt_inout
			  ,bid_opt_convenience
			  ,bid_opt_safety
			  ,bid_opt_device
			  ,bid_request_time
			  ,bid_state
			) VALUES (
			  '$bid_code'
			  ,'$bid_name'
			  ,'$bid_phone'
			  ,'$bid_maker'
			  ,'$bid_car_name'
			  ,'$bid_car_age'
			  ,'$bid_car_num'
			  ,'$bid_mileage'
			  ,'$bid_capacity'
			  ,'$bid_sales_area'
			  ,'$bid_transmission'
			  ,'$bid_fuel'
			  ,'$bid_car_img_1'
			  ,'$bid_car_img_2'
			  ,'$bid_car_img_3'
			  ,'$bid_car_img_4'
			  ,'$bid_car_img_5'
			  ,'$bid_car_img_6'
			  ,'$bid_car_img_7'
			  ,'$bid_car_img_8'
			  ,'$bid_accident_check'
			  ,'$bid_car_owner_check'
			  ,'$bid_etc'
			  ,'$bid_opt_inout'
			  ,'$bid_opt_convenience'
			  ,'$bid_opt_safety'
			  ,'$bid_opt_device'
			  ,'$bid_request_time'
			  ,'$bid_state'
			)
		";		
		$res = $dbc->query($sql);
		//$idx = $dbc->getInsertId();
		
		/*################################## SMS보내기 ###############################################*/
		$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		
		$host_info = explode("/", $sms_url);
    	$host = $host_info[2];
    	$path = $host_info[3];
		
		$sms = array();
		$sms['user_id'] = base64_encode("mycar8282"); //SMS 아이디.
    	$sms['secure'] = base64_encode("04ff0fc77bab3fee136e1a3adb8cbfa7") ;//인증키
    	
    	$msg = $bid_name."님의 차량정보를 입찰등록하였습니다. 입찰번호는 [$bid_code]입니다. ";
    	$sms['msg'] = base64_encode(stripslashes($msg));
		
		if($bid_phone1<>"") $bid_phone = $bid_phone1."-".$bid_phone2."-".$bid_phone3;
		$sms['rphone'] = base64_encode($bid_phone);
    	$sms['sphone1'] = base64_encode($bid_phone1);
    	$sms['sphone2'] = base64_encode($bid_phone2);
    	$sms['sphone3'] = base64_encode($bid_phone3);
		$sms['rdate'] = "";
	    $sms['rtime'] = "";
	    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
	    $sms['returnurl'] = "";
	    //ms['testflag'] = base64_encode("Y");
		$sms['testflag'] = "";
	    $sms['destination'] = "";	    
	    $sms['repeatFlag'] = "";
	    $sms['repeatNum'] = "";
	    $sms['repeatTime'] = "";
	    $sms['smsType'] = ""; // LMS일경우
	    		
		
		srand((double)microtime()*1000000);
    	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
		 // 헤더 생성
	    $header = "POST /".$path ." HTTP/1.0\r\n";
	    $header .= "Host: ".$host."\r\n";
	    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
	
	    // 본문 생성
	    $data = "";
	    foreach($sms AS $index => $value){
	        $data .="--$boundary\r\n";
	        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
	        $data .= "\r\n".$value."\r\n";
	        $data .="--$boundary\r\n";
	    }
	    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
		
		$fp = fsockopen($host, 80);
		if ($fp) {
		 	fputs($fp, $header.$data);
		    $rsp = '';
		    while(!feof($fp)) { 
		        $rsp .= fgets($fp,8192); 
		    }
		    fclose($fp);
			
	 		$msg = explode("\r\n\r\n",trim($rsp));
	        $rMsg = explode(",", $msg[1]);
	        $Result= $rMsg[0]; //발송결과
	        $Count= $rMsg[1]; //잔여건수
	
	        //발송결과 알림
	        if($Result=="success") {
	            $alert = "성공";
	            $alert .= " 잔여건수는 ".$Count."건 입니다.";
	        }
	        else if($Result=="reserved") {
	            $alert = "성공적으로 예약되었습니다.";
	            $alert .= " 잔여건수는 ".$Count."건 입니다.";
	        }
	        else if($Result=="3205") {
	            $alert = "잘못된 번호형식입니다.";
				echo $alert;
				exit; 
	        }
			else if($Result=="0044") {
	            $alert = "스팸문자는발송되지 않습니다.";
				echo $alert;
				exit; 
	        }
	        else {
	        	if($Result!="Test Success!"){
	        		$alert = "[Error]".$Result;
					echo $alert;
					exit;	
	        	}
	        }
			
		} else {
        	$alert = "Connection Failed";
			echo $alert;
			exit; 
    	}
		/*################################## SMS보내기 // ###############################################*/
				
		
	}else{
		$sql = "
			UPDATE bid_info
			SET
			   bid_name = '$bid_name'
			  ,bid_phone = '$bid_phone'
			  ,bid_maker = '$bid_maker'
			  ,bid_car_name = '$bid_car_name'
			  ,bid_car_age = '$bid_car_age'
			  ,bid_car_num = '$bid_car_num'
			  ,bid_mileage = '$bid_mileage'
			  ,bid_capacity = '$bid_capacity'
			  ,bid_sales_area = '$bid_sales_area'
			  ,bid_transmission = '$bid_transmission'
			  ,bid_fuel = '$bid_fuel'
		";
		
		if($bid_car_img_1) $sql .= ",bid_car_img1 = '$bid_car_img_1'";
		if($bid_car_img_2) $sql .= ",bid_car_img2 = '$bid_car_img_2'";
		if($bid_car_img_3) $sql .= ",bid_car_img3 = '$bid_car_img_3'";
		if($bid_car_img_4) $sql .= ",bid_car_img4 = '$bid_car_img_4'";
		if($bid_car_img_5) $sql .= ",bid_car_img5 = '$bid_car_img_5'";
		if($bid_car_img_6) $sql .= ",bid_car_img6 = '$bid_car_img_6'";
		if($bid_car_img_7) $sql .= ",bid_car_img7 = '$bid_car_img_7'";
		if($bid_car_img_8) $sql .= ",bid_car_img8 = '$bid_car_img_8'";
		if($bid_state==1) $sql .= ",bid_apply_date = now()";
		
		$sql .= "
			  ,bid_accident_check = '$bid_accident_check'
			  ,bid_car_owner_check = '$bid_car_owner_check'
			  ,bid_etc = '$bid_etc'
			  ,bid_opt_inout = '$bid_opt_inout'
			  ,bid_opt_convenience = '$bid_opt_convenience'
			  ,bid_opt_safety = '$bid_opt_safety'
			  ,bid_opt_device = '$bid_opt_device'
			  ,bid_request_time = '$bid_request_time'
			  ,bid_state = '$bid_state'
			WHERE bid_idx = $bid_idx
		";
		$res = $dbc->query($sql);
	}
	

	
	if(!$res){
		echo "fail : ". $sql;
		exit;
	}
	$dbc->__destruct();
	
	if(isset($_COOKIE['admin_id'])) {
		$return_url = "/apps/adm/bid_info_view.php";
	}else{
		$return_url = "/apps/bid/view.php";
	}
		
	//입찰등록성공!
	$_tmp_ = array();
	$_tmp_[] = "<form name=\"bidReqEndForm\" method=\"post\" action=\"$return_url\" >";
	$_tmp_[] = $htmlObj->makeHidden("bid_name", $bid_name);
	$_tmp_[] = $htmlObj->makeHidden("bid_code", $bid_code);
	$_tmp_[] = "</form>";
	$output['body']['hidden_cmd'] = implode("\n", $_tmp_);
?>
	<html>
	<head>
	<script type="text/javascript">	
		function end() {
			document.bidReqEndForm.submit();
		}
	</script>
	</head>
	<body onload="javascript:end()">
		<?=$output['body']['hidden_cmd']?>
	</body>
	</html>