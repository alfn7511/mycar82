<?php 
	// ######################################################################
	// Initial
	// ######################################################################
	define("_CONF_DBCON", true);
	define("_CONF_COMMON_CODE", true);
	define("_LOAD_HTML", true);
	define("_LOAD_JAVASCRIPT", true);
	define("_LOAD_PAGE", true);
	require_once "../init.php";


	// ######################################################################
	// Main Routine
	// ######################################################################	
	
	// ######################################################################
	// check order variables, require field's
	// ######################################################################
	
	if(!isset($_COOKIE['admin_id'])) {
		print $jsObj->msgGoUrl(iconv("UTF-8","EUC-KR", "관리자로 로그인하십시요."), "/apps/adm/login.php");
		exit;
	}	

	$output = array();
	$head_title  = "입찰정보상세보기";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	
	$mode = "add";
	$bid_idx = 0;
	$bid_phone1 = "";
	$bid_car_img = array("","","","","","","","");
	$bid_opt_inout = "";
	$bid_opt_convenience = "";
	$bid_opt_safety = "";
	$bid_opt_device = "";
	$bid_etc = "";
	
	/*#######################################################  입찰정보수정 ##################################################################*/	
	$bid_code = (isset($_GET['bid_code'])) ? trim($_GET['bid_code']) : trim($_POST['bid_code']);
	
	if($bid_code) {
		$sql = "
		SELECT bid_idx, bid_code, bid_name, bid_phone, bid_maker, bid_car_name, bid_car_age, bid_car_num, bid_mileage, bid_capacity, bid_sales_area, bid_transmission, bid_fuel, 
			   bid_car_img1, bid_car_img2, bid_car_img3, bid_car_img4, bid_car_img5, bid_car_img6, bid_car_img7, bid_car_img8, bid_accident_check, bid_car_owner_check, bid_etc, 
			   bid_opt_inout, bid_opt_convenience, bid_opt_safety, bid_opt_device, bid_request_time, bid_state, bid_succ_price, bid_succ_bidder, bid_succ_date, bid_regdate, bid_del_yn
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
			$bid_succ_price = number_format(checkNumeric($rs['bid_succ_price']));
			$bid_succ_bidder = $rs['bid_succ_bidder'];
			$bid_succ_date = $rs['bid_succ_date'];
			$bid_regdate  = $rs['bid_regdate'];
			$bid_del_yn  = $rs['bid_del_yn'];
			
			/*########################## 입찰하기 - 낙찰자 선택및 확인 #############################################3*/		
			$output['bid_process'] = array();
			$sql = "
				SELECT proc_idx,  proc.deal_id, proc_price, proc_state, proc_regdate, deal_name, deal_level
				FROM bid_process proc inner join dealer_info dinfo on proc.deal_id = dinfo.deal_id
				WHERE bid_code = '$bid_code'
				ORDER BY proc_price DESC;
			";
			
			$res = $dbc->getRecordList( $sql );
			$dbc->__destruct();
			$output['bid_process']['cnt'] = sizeof($res);
			$output['bid_process']['list'] = NULL;
			
			$idx = 1;
			foreach ( $res as $rs ){
				unset($__tmp);
				$__tmp = array();
				
				$__tmp['idx'] = $idx;
				$__tmp['ranking_img'] = "<img src='/img/sub/icon_level0".$idx.".png' alt='$idx'>";
				$__tmp['ranking_str'] = $idx."위";
				$__tmp['proc_idx'] = $rs['proc_idx'];
				$__tmp['deal_id'] = $rs['deal_id'];
				$__tmp['deal_name'] = $rs['deal_name'];
				$deal_level = $rs['deal_level'];
				$__tmp['deal_level_str'] = $common_code['deal_level'][$deal_level];
				$__tmp['deal_level_tagImg'] = "<span class='flag'><img src='/img/sub/tag_level_".$deal_level.".png' alt='".$__tmp['deal_level_str']."'></span>";
				$__tmp['proc_price'] = checkNumeric($rs['proc_price']);
				$proc_state = checkNumeric($rs['proc_state']);
				$__tmp['proc_regdate'] = $rs['proc_regdate'];
				
				$__tmp['winner_img'] = "";
				$__tmp['winner_class'] = "";
				$__tmp['deal_sel'] = "";
				
				$__tmp['deal_sel'] = "<span class='checkbox'><input type='radio' name='deal_sel' value='".$rs['deal_id']."|".$rs['deal_name']."|".$rs['proc_price']."'></span>";
				
				if($proc_state==1) {
					$review['deal_id'] = trim($rs['deal_id']);
					$review['deal_name'] = trim($rs['deal_name']);
					$__tmp['winner_img'] = "<img src='/img/sub/img_winner.png' alt='winner' class='winner'>";
					$__tmp['winner_class'] = "class='winner'";
				}
				
				$idx++;
				$output['bid_process']['list'][] = $__tmp;
			}
			/*########################## 입찰하기 - 낙찰자 선택및 확인 //#############################################*/
			
			
			/*########################## 후기 #############################################*/		
			$output['bid_review'] = array();
			$sql = "
				SELECT r_idx, deal_id, r_mark, r_content, r_regdate, r_del_yn, (SELECT bid_name FROM bid_info  WHERE bid_code = '$bid_code') AS bid_name 
				FROM bid_review 
				WHERE bid_code = '$bid_code';
			";
			$res = $dbc->getRecordList( $sql );
			$dbc->__destruct();
			$output['bid_review']['cnt'] = sizeof($res);
			$output['bid_review']['list'] = NULL;
			
			$idx = 1;
			foreach ( $res as $rs ){
				unset($__tmp);
				$__tmp = array();
				$__tmp['idx'] = $idx;
				$__tmp['r_idx'] = checkNumeric($rs['r_idx']);
				$__tmp['deal_id'] = trim($rs['deal_id']);
				$__tmp['bid_name'] = trim($rs['bid_name']);
				
				$r_mark = checkNumeric($rs['r_mark']);
				$__tmp['r_mark_img'] = "<img src='/img/sub/bul_s$r_mark.png' alt='$r_mark'>";
				
				$__tmp['r_content'] = trim($rs['r_content']);
				$__tmp['r_regdate'] = trim($rs['r_regdate']);
				$__tmp['r_del_yn'] = trim($rs['r_del_yn']);
				$idx++;
				$output['bid_review']['list'][] = $__tmp;
			}
			/*########################## 후기 // #############################################*/			
		
			$mode = "edit";
			
		}
	}
	/*########################################### 입찰정보수정 // ########################################################*/
	
	$output['body']['bid_phone1'] = $htmlObj->makeSelect("bid_phone1", $conf['common_code']['mobile'], $bid_phone1, NULL,  NULL, " id=\"bid_phone1\" class=\"select phone\" title=\"휴대폰 통신사번호\" ");
	
	unset($_tmp_);
	$_tmp_[] = $htmlObj->makeHidden("mode", $mode, " id=\"mode\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_idx", $bid_idx, " id=\"bid_idx\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_code", $bid_code, " id=\"bid_code\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_inout_val", $bid_opt_inout, " id=\"bid_opt_inout_val\" ");	
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_convenience_val", $bid_opt_convenience, " id=\"bid_opt_convenience_val\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_safety_val", $bid_opt_safety, " id=\"bid_opt_safety_val\" ");
	$_tmp_[] = $htmlObj->makeHidden("bid_opt_device_val", $bid_opt_device, " id=\"bid_opt_device_val\" ");
	$output['body']['hidden_cmd_reg'] = "\n".implode("\n", $_tmp_);

	include _HTML_BASE . "/adm/bid_info_view.html";
?>
