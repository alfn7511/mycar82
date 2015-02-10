<?php 
	// ######################################################################
	// Initial
	// ######################################################################
	define("_CONF_DBCON", true);
	define("_CONF_COMMON_CODE", true);
	define("_LOAD_HTML", true);
	define("_LOAD_JAVASCRIPT", true);
	require_once "../init.php";


	// ######################################################################
	// Main Routine
	// ######################################################################	
	
	// ######################################################################
	// check order variables, require field's
	// ######################################################################

	$output = array();
	$head_title  = "입찰요청";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['bid_state_cnt'] = "";
	
	$mode = "add";
	$bid_idx = 0;
	$bid_phone1 = "";
	$bid_car_img = array();
	$bid_opt_inout = "";
	$bid_opt_convenience = "";
	$bid_opt_safety = "";
	$bid_opt_device = "";
	$bid_etc = "";
	
	/*#######################################################  입찰정보수정 ##################################################################*/	
	$bid_code = (isset($_COOKIE['bid_code'])) ? trim($_COOKIE['bid_code']) : "";
	if(isset($_COOKIE['bid_code'])) {
		$sql = "
		SELECT bid_idx, bid_code, bid_name, bid_phone, bid_maker, bid_car_name, bid_car_age, bid_car_num, bid_mileage, bid_capacity, bid_sales_area, bid_transmission, bid_fuel, 
			   bid_car_img1, bid_car_img2, bid_car_img3, bid_car_img4, bid_car_img5, bid_car_img6, bid_car_img7, bid_car_img8, bid_accident_check, bid_car_owner_check, bid_etc, 
			   bid_opt_inout, bid_opt_convenience, bid_opt_safety, bid_opt_device, bid_request_time, bid_state, bid_succ_price, bid_succ_bidder, bid_succ_date, bid_regdate
				FROM bid_info WHERE bid_code='$bid_code';
		";
		
		$rs = $dbc-> getRecord($sql);
		
		if($rs){
			$bid_idx = $rs['bid_idx'];			
			$bid_name = $rs['bid_name'];
			$bid_phone = $rs['bid_phone'];
			if(strlen($bid_phone)==10){
				$bid_phone1 = substr($bid_phone, 0, 3);
				$bid_phone2 = substr($bid_phone, 3, 3);
				$bid_phone3 = substr($bid_phone, 6, 4);	
			}else{
				$bid_phone1 = substr($bid_phone, 0, 3);
				$bid_phone2 = substr($bid_phone, 3, 4);
				$bid_phone3 = substr($bid_phone, 7, 4);	
			}
			
			$bid_maker = $rs['bid_maker'];
			$bid_car_name = $rs['bid_car_name'];
			$bid_car_age = $rs['bid_car_age'];
			$bid_car_num = $rs['bid_car_num'];
			$bid_mileage = $rs['bid_mileage'];
			$bid_capacity = $rs['bid_capacity'];
			$bid_sales_area = $rs['bid_sales_area'];
			$bid_transmission = $rs['bid_transmission'];
			$bid_fuel = $rs['bid_fuel'];
			
			$bid_car_img1 = (isset($rs['bid_car_img1'])) ? trim($rs['bid_car_img1']) : "";	
			$bid_car_img2 = (isset($rs['bid_car_img2'])) ? trim($rs['bid_car_img2']) : "";
			$bid_car_img3 = (isset($rs['bid_car_img3'])) ? trim($rs['bid_car_img3']) : "";
			$bid_car_img4 = (isset($rs['bid_car_img4'])) ? trim($rs['bid_car_img4']) : "";
			$bid_car_img5 = (isset($rs['bid_car_img5'])) ? trim($rs['bid_car_img5']) : "";
			$bid_car_img6 = (isset($rs['bid_car_img6'])) ? trim($rs['bid_car_img6']) : "";
			$bid_car_img7 = (isset($rs['bid_car_img7'])) ? trim($rs['bid_car_img7']) : "";
			$bid_car_img8 = (isset($rs['bid_car_img8'])) ? trim($rs['bid_car_img8']) : "";
			
			if($bid_car_img1) $bid_car_img[0] = "/data/bid_reg/$bid_car_img1";
			if($bid_car_img2) $bid_car_img[1] = "/data/bid_reg/$bid_car_img2";
			if($bid_car_img3) $bid_car_img[2] = "/data/bid_reg/$bid_car_img3";
			if($bid_car_img4) $bid_car_img[3] = "/data/bid_reg/$bid_car_img4";
			if($bid_car_img5) $bid_car_img[4] = "/data/bid_reg/$bid_car_img5";
			if($bid_car_img6) $bid_car_img[5] = "/data/bid_reg/$bid_car_img6";
			if($bid_car_img7) $bid_car_img[6] = "/data/bid_reg/$bid_car_img7";
			if($bid_car_img8) $bid_car_img[7] = "/data/bid_reg/$bid_car_img8";	
			
			$bid_accident_check = $rs['bid_accident_check'];
			$bid_car_owner_check = $rs['bid_car_owner_check'];
			
			$bid_etc = $rs['bid_etc'];
				
			$bid_opt_inout = $rs['bid_opt_inout'];
			$bid_opt_convenience = $rs['bid_opt_convenience'];
			$bid_opt_safety = $rs['bid_opt_safety'];
			$bid_opt_device = $rs['bid_opt_device'];
			$bid_request_time = $rs['bid_request_time'];
			$bid_state = $rs['bid_state'];
			$bid_succ_price = $rs['bid_succ_price'];
			$bid_succ_bidder = $rs['bid_succ_bidder'];
			$bid_succ_date = $rs['bid_succ_date'];
			$bid_regdate  = $rs['bid_regdate'];
			
			$mode = "edit";
		}
	}
	/*########################################### 입찰정보수정 // ########################################################*/		
		
	$output['body']['bid_phone1'] = $htmlObj->makeSelect("bid_phone1", $conf['common_code']['mobile'], $bid_phone1, NULL,  NULL, " id=\"bid_phone1\" title=\"휴대폰 통신사번호\" ");
	
	unset($_tmp_);
	$_tmp_[] = $htmlObj->makeHidden("mode", $mode, " id=\"mode\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_idx", $bid_idx, " id=\"bid_idx\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_code", $bid_code, " id=\"bid_code\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_inout_val", $bid_opt_inout, " id=\"bid_opt_inout_val\" ");	
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_convenience_val", $bid_opt_convenience, " id=\"bid_opt_convenience_val\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_safety_val", $bid_opt_safety, " id=\"bid_opt_safety_val\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_device_val", $bid_opt_device, " id=\"bid_opt_device_val\" ");
	$output['body']['hidden_cmd_reg'] = "\n".implode("\n", $_tmp_);

	include _HTML_BASE . "/bid/register.html";
?>
