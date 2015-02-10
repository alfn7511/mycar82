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
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['bid_state_cnt'] = "";
	$output['head']['js'] = $htmlObj->makeHead("jquery.cookie.js");
	
	if($_COOKIE['bid_code'] == "null"){
		setcookie ( "bid_code", "", time () - 3600, "/", _COOKIE_DOMAIN);
		setcookie ( "bid_code", "", time () - 3600, "/");
		//unset($_COOKIE);		
	}	
	
	if(isset($_GET['bid_code']) || isset($_POST['bid_code'])){
		$bid_code = "";	
		$bid_code = (isset($_GET['bid_code'])) ? trim($_GET['bid_code']) : trim($_POST['bid_code']);	
	}else{
		print $jsObj->msgGoUrl("bid_code error!!", "/apps/bid/list.php");
		exit;
	}
	
	$sql_bid_go = "";
	if(isset($_COOKIE['deal_id'])) {
		$sql_bid_go = "
		     ,(SELECT proc_price FROM bid_process WHERE bid_code =info.bid_code AND deal_id='".$_COOKIE['deal_id']."') AS proc_price
	         ,(SELECT proc_state FROM bid_process WHERE bid_code =info.bid_code AND deal_id='".$_COOKIE['deal_id']."') AS proc_state	
	         ,(SELECT proc_regdate FROM bid_process WHERE bid_code =info.bid_code AND deal_id='".$_COOKIE['deal_id']."') AS proc_regdate	
		";
	}
	
	$sql = "
		SELECT bid_idx, bid_code, bid_name, bid_phone, bid_maker, bid_car_name, bid_car_age, bid_car_num, bid_mileage, bid_capacity, bid_sales_area, bid_transmission, bid_fuel 
			   ,bid_car_img1, bid_car_img2, bid_car_img3, bid_car_img4, bid_car_img5, bid_car_img6, bid_car_img7, bid_car_img8, bid_accident_check, bid_car_owner_check, bid_etc 
			   ,bid_opt_inout, bid_opt_convenience, bid_opt_safety, bid_opt_device, bid_request_time, bid_state, bid_succ_price, bid_succ_bidder, bid_succ_date, bid_regdate 
			   ,IF(isnull(bid_apply_date), 0, TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now()))  AS bid_timer  
			   ,(SELECT max(proc_price) FROM bid_process WHERE bid_code =info.bid_code) AS bid_max_price 
			   ,(SELECT count(*) FROM bid_process WHERE bid_code =info.bid_code) AS bid_go_cnt
			   $sql_bid_go	
		FROM bid_info AS info
		WHERE bid_code='$bid_code' AND bid_del_yn='N';
	";
	
	$rs = $dbc-> getRecord($sql);
	$dbc->__destruct();		
	if(!$rs){
		echo "error!!";
		exit;
	}
	
	$bid_name = $rs['bid_name'];
	$bid_phone = trim($rs['bid_phone']);
	if(strlen($bid_phone)==10){
		$bid_phone1 = substr($bid_phone, 0, 3);
		$bid_phone2 = substr($bid_phone, 3, 3);
		$bid_phone3 = substr($bid_phone, 6, 4);	
	}else{
		$bid_phone1 = substr($bid_phone, 0, 3);
		$bid_phone2 = substr($bid_phone, 3, 4);
		$bid_phone3 = substr($bid_phone, 7, 4);	
	}
	$bid_mobile = $bid_phone1."-".$bid_phone2."-".$bid_phone3;
	
	$bid_maker = $rs['bid_maker'];
	$bid_car_name = $rs['bid_car_name'];
	$bid_car_age = $rs['bid_car_age'];
	$bid_car_num = $rs['bid_car_num'];
	$bid_mileage = number_format($rs['bid_mileage']);
	$bid_capacity = number_format($rs['bid_capacity']);
	$bid_sales_area = $rs['bid_sales_area'];
	$bid_transmission = $rs['bid_transmission'];
	$bid_transmission = $conf['common_code']['transmission'][$bid_transmission];
	
	$bid_fuel = $rs['bid_fuel'];
	$bid_fuel = $conf['common_code']['fuel'][$bid_fuel];
	
	$bid_car_img1 = (isset($rs['bid_car_img1'])) ? trim($rs['bid_car_img1']) : "";	
	$bid_car_img2 = (isset($rs['bid_car_img2'])) ? trim($rs['bid_car_img2']) : "";
	$bid_car_img3 = (isset($rs['bid_car_img3'])) ? trim($rs['bid_car_img3']) : "";
	$bid_car_img4 = (isset($rs['bid_car_img4'])) ? trim($rs['bid_car_img4']) : "";
	$bid_car_img5 = (isset($rs['bid_car_img5'])) ? trim($rs['bid_car_img5']) : "";
	$bid_car_img6 = (isset($rs['bid_car_img6'])) ? trim($rs['bid_car_img6']) : "";
	$bid_car_img7 = (isset($rs['bid_car_img7'])) ? trim($rs['bid_car_img7']) : "";
	$bid_car_img8 = (isset($rs['bid_car_img8'])) ? trim($rs['bid_car_img8']) : "";
	
	$bid_car_img = "";
	if($bid_car_img1) $bid_car_img = "<div class='item'><img src='/data/bid_reg/$bid_car_img1'  alt='앞면과 측면'></div>";
	if($bid_car_img2) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img2'  alt='뒷면과 측면'></div>";
	if($bid_car_img3) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img3'  alt='시트/변속기/네비'></div>";
	if($bid_car_img4) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img4'  alt='주행거리'></div>";
	if($bid_car_img5) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img5'  alt='추가사진1'></div>";
	if($bid_car_img6) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img6'  alt='추가사진2'></div>";
	if($bid_car_img7) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img7'  alt='추가사진3'></div>";
	if($bid_car_img8) $bid_car_img .= "<div class='item'><img src='/data/bid_reg/$bid_car_img8'  alt='추가사진4'></div>";	
	
	$bid_accident_check = $rs['bid_accident_check'];
	$bid_accident_check = $conf['common_code']['accident_check'][$bid_accident_check];
	
	$bid_car_owner_check = $rs['bid_car_owner_check'];
	$bid_car_owner_check = $conf['common_code']['owner_check'][$bid_car_owner_check];
	
	$bid_etc = nl2br($rs['bid_etc']);
		
	$bid_opt_inout = $rs['bid_opt_inout'];
	$bid_opt_convenience = $rs['bid_opt_convenience'];
	$bid_opt_safety = $rs['bid_opt_safety'];
	$bid_opt_device = $rs['bid_opt_device'];
	$bid_request_time = $rs['bid_request_time'];
	
	$bid_state = checkNumeric($rs['bid_state']);
	$bid_state_str = $conf['common_code']['bid_state'][$bid_state];
	$bid_state_img = "<span class='item_state'><img src='/img/main/icon_bid_state_$bid_state.png' alt='$bid_state_str'></span>";
	
	$bid_succ_price = $rs['bid_succ_price'];
	$bid_succ_bidder = $rs['bid_succ_bidder'];
	$bid_succ_date = $rs['bid_succ_date'];
	$bid_regdate  = $rs['bid_regdate'];
	
	$bid_max_price = number_format($rs['bid_max_price']);
	$bid_go_cnt = $rs['bid_go_cnt'];
	
	$bid_timer = (isset($rs['bid_timer'])) ? trim($rs['bid_timer']) : "";
	$bid_timer_arr = explode(":", $bid_timer);
	$bid_timer_hr = (isset($bid_timer_arr[0])) ? checkNumeric($bid_timer_arr[0]) : 0;
	$bid_timer_min = (isset($bid_timer_arr[1])) ? checkNumeric($bid_timer_arr[1]) : 0;
	$bid_timer_sec = (isset($bid_timer_arr[2])) ? checkNumeric($bid_timer_arr[2]) : 0;
	
	//입찰시간종료여부확인
	$flagBidTime = TRUE;
	if(strrpos($bid_timer, "-") !== false && $bid_timer_hr <= 0){
		$bid_timer_hr = 0;
		$bid_timer_min = 0;
		$bid_timer_sec = 0;
		$flagBidTime = FALSE;
	}
	
	//딜러로그인사용자일 경우
	$flagSucc = FALSE;
	if(isset($_COOKIE['deal_id'])) {
		$proc_price = number_format(checkNumeric($rs['proc_price']));
		$proc_state = checkNumeric($rs['proc_state']);
		$proc_regdate = trim($rs['proc_regdate']);
		
		//낙찰된 딜러는 입찰자정보를 확인할 수 있다.
		if($bid_state==3 && $proc_state==1 && ($bid_succ_bidder==trim($_COOKIE['deal_id']))) $flagSucc = TRUE;
	}
	
	/*################################################# 입찰코드가 확인된 입찰등록한 회원일경우 #######################################################*/
	$flagBidgo = TRUE;
	//입찰시간이 남았으면 낙찰자 선택못하게 함
	if($flagBidTime) $flagBidgo = FALSE;
	if($bid_state == 3) $flagBidgo = FALSE;
	
	//관리자 딜러는 언제든지 입찰한다.
	if($_COOKIE['deal_level'] == 9) $flagBidTime = TRUE;
		
	$flagReview = FALSE; //후기권한
	$review  = array();
	if(isset($_COOKIE['bid_code']) && ($bid_code==trim($_COOKIE['bid_code']))) {
		
		/*########################## 입찰하기 - 낙찰자 선택및 확인 #############################################3*/		
		$output['bid_process'] = array();
		$sql = "
			SELECT proc_idx,  proc.deal_id, proc_price, proc_state, proc_regdate, deal_name, deal_level
			FROM bid_process proc inner join dealer_info dinfo on proc.deal_id = dinfo.deal_id
			WHERE bid_code = '$bid_code'
			ORDER BY proc_price DESC
			LIMIT 3;
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
			$__tmp['proc_idx'] = $rs['proc_idx'];
			$__tmp['deal_id'] = $rs['deal_id'];
			$__tmp['deal_name'] = $rs['deal_name'];
			$deal_level = $rs['deal_level'];
			$deal_level_str = $common_code['deal_level'][$deal_level];
			$__tmp['deal_level_tagImg'] = "<span class='flag'><img src='/img/sub/tag_level_".$deal_level.".png' alt='$deal_level_str'></span>";
			$__tmp['proc_price'] = checkNumeric($rs['proc_price']);
			$proc_state = checkNumeric($rs['proc_state']);
			$__tmp['proc_regdate'] = $rs['proc_regdate'];
			
			$__tmp['winner_img'] = "";
			$__tmp['winner_class'] = "";
			$__tmp['deal_sel'] = "";
						
			//입찰완료시 낙찰선택 안되게..
			if($flagBidgo){
				$__tmp['deal_sel'] = "<span class='checkbox'><input type='radio' name='deal_sel' value='".$rs['deal_id']."|".$rs['deal_name']."|".$rs['proc_price']."'></span>";
			}else{
				if($proc_state==1) {
					$flagReview = TRUE;
					$review['deal_id'] = trim($rs['deal_id']);
					$review['deal_name'] = trim($rs['deal_name']);
					$__tmp['winner_img'] = "<img src='/img/sub/img_winner.png' alt='winner' class='winner'>";
					$__tmp['winner_class'] = "class='no1'";
				}
			}
			
			$idx++;
			$output['bid_process']['list'][] = $__tmp;
		}
		/*########################## 입찰하기 - 낙찰자 선택및 확인 //#############################################*/
		
		/*########################## 후기 #############################################*/		
		$output['bid_review'] = array();
		$sql = "
			SELECT r_idx, deal_id, r_mark, r_content, r_regdate, (SELECT bid_name FROM bid_info  WHERE bid_code = '$bid_code') AS bid_name 
			FROM bid_review 
			WHERE bid_code = '$bid_code' AND r_del_yn='N';
		";
		$res = $dbc->getRecordList( $sql );
		$dbc->__destruct();
		$output['bid_review']['cnt'] = sizeof($res);
		$output['bid_review']['list'] = NULL;
		
		$idx = 1;
		$flagReviewWrite = TRUE;
		foreach ( $res as $rs ){
			unset($__tmp);
			$__tmp = array();
			$__tmp['idx'] = $idx;
			$__tmp['r_idx'] = checkNumeric($rs['r_idx']);
			$__tmp['deal_id'] = trim($rs['deal_id']);
			$__tmp['bid_name'] = trim($rs['bid_name']);
			
			$r_mark = checkNumeric($rs['r_mark']);
			$__tmp['r_mark_img'] = "<img src='/img/sub/bul_s$r_mark.png' alt='$r_mark'>";
			
			$__tmp['r_content'] = nl2br($rs['r_content']);
			$__tmp['r_regdate'] = trim($rs['r_regdate']);
			$idx++;
			$output['bid_review']['list'][] = $__tmp;
			$flagReviewWrite = FALSE;
		}
		/*########################## 후기 // #############################################*/
		
	}
	/*################################################# 입찰코드가 확인된 입찰등록한 회원일경우 // #######################################################*/

	if($bid_state==3){
		$head_title  = "입찰완료";
	}else{
		$head_title  = "현재입찰중";	
	}
	$output['page_title'] =  _TITLE ." $head_title";

	include _HTML_BASE . "/bid/view.html";
?>
