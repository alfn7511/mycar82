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
	
	if(!isset($_COOKIE['deal_id'])) {
		print $jsObj->msgGoUrl(iconv("UTF-8","EUC-KR", "로그인을 하십시요."), "/apps/dealer/login.php");
		exit;
	}
	
	$sql = " UPDATE bid_info SET bid_state=2 WHERE TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now()) <=0 AND bid_state = 1 AND bid_apply_date IS NOT NULL";
	$res = $dbc->query($sql);

	$output = array();	
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	
	$bid_state = (isset($_GET['bid_state'])) ? trim($_GET['bid_state']) : 1;
	
	$output['bid_info'] = array();
	$sql = "
		SELECT bid_idx, bid_code, bid_name, bid_phone, bid_maker, bid_car_name, bid_car_age, bid_car_num, bid_mileage, bid_capacity, bid_sales_area, bid_transmission, bid_fuel 
		    ,bid_car_img1, bid_car_img2, bid_car_img3, bid_car_img4, bid_car_img5, bid_car_img6, bid_car_img7, bid_car_img8, bid_accident_check, bid_car_owner_check, bid_etc 
		    ,bid_opt_inout, bid_opt_convenience, bid_opt_safety, bid_opt_device, bid_request_time, bid_succ_price, bid_succ_bidder, bid_succ_date, bid_regdate
		    ,(SELECT max(proc_price) FROM bid_process WHERE bid_code =info.bid_code) AS bid_max_price 
		    ,(SELECT count(*) FROM bid_process WHERE bid_code =info.bid_code) AS bid_go_cnt
		    ,(SELECT proc_price FROM bid_process WHERE bid_code =info.bid_code AND proc_state=1) AS bid_succ_price	  
		    ,IF(isnull(bid_apply_date), 0, TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now()))  AS bid_timer
		    ,CASE bid_state WHEN IF(isnull(bid_apply_date), 0, TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now())) < 0 THEN 2 ELSE bid_state END AS bid_state
		FROM bid_info info
		WHERE bid_state = $bid_state AND bid_del_yn = 'N'
		ORDER BY bid_regdate DESC;
	";
	$res = $dbc->getRecordList( $sql );
	$output['bid_info']['cnt'] = sizeof($res);
	$output['bid_info']['list'] = NULL;
	
	$idx = 0;
	foreach ( $res as $rs ){
		
		unset($__tmp);
		$__tmp = array();
		
		$__tmp['idx'] = $idx;
		$__tmp['bid_code'] = $rs['bid_code'];
		$__tmp['bid_name'] = $rs['bid_name'];
		$__tmp['bid_phone'] = $rs['bid_phone'];
		$__tmp['bid_maker'] = $rs['bid_maker'];
		$__tmp['bid_car_name'] = $rs['bid_car_name'];
		$__tmp['bid_car_age'] = $rs['bid_car_age'];
		$__tmp['bid_car_num'] = $rs['bid_car_num'];
		$__tmp['bid_mileage'] = number_format($rs['bid_mileage']);
		$__tmp['bid_capacity'] = number_format($rs['bid_capacity']);
		$__tmp['bid_sales_area'] = $rs['bid_sales_area'];
		
		$bid_transmission = $rs['bid_transmission'];
		$__tmp['bid_transmission'] = $conf['common_code']['transmission'][$bid_transmission];
		
		$bid_fuel = $rs['bid_fuel'];
		$__tmp['bid_fuel'] = $conf['common_code']['fuel'][$bid_fuel];
		
		$bid_car_img1 = (isset($rs['bid_car_img1'])) ? trim($rs['bid_car_img1']) : "";	
		$bid_car_img2 = (isset($rs['bid_car_img2'])) ? trim($rs['bid_car_img2']) : "";
		$bid_car_img3 = (isset($rs['bid_car_img3'])) ? trim($rs['bid_car_img3']) : "";
		$bid_car_img4 = (isset($rs['bid_car_img4'])) ? trim($rs['bid_car_img4']) : "";
		$bid_car_img5 = (isset($rs['bid_car_img5'])) ? trim($rs['bid_car_img5']) : "";
		$bid_car_img6 = (isset($rs['bid_car_img6'])) ? trim($rs['bid_car_img6']) : "";
		$bid_car_img7 = (isset($rs['bid_car_img7'])) ? trim($rs['bid_car_img7']) : "";
		$bid_car_img8 = (isset($rs['bid_car_img8'])) ? trim($rs['bid_car_img8']) : "";
		
		
		$__tmp['bid_car_img'] = "";
		if($bid_car_img1) $__tmp['bid_car_img'] = "<a href='/apps/bid/view.php?bid_code=".$__tmp['bid_code']."'><img src='/data/bid_reg/$bid_car_img1'  alt='앞면과 측면'></a>";	
		
		$__tmp['bid_view_link'] = "/apps/bid/view.php?bid_code=".$__tmp['bid_code'];
		
		$bid_accident_check = $rs['bid_accident_check'];
		$__tmp['bid_accident_check'] = $conf['common_code']['accident_check'][$bid_accident_check];
		
		$bid_car_owner_check = $rs['bid_car_owner_check'];
		$__tmp['bid_car_owner_check'] = $conf['common_code']['owner_check'][$bid_car_owner_check];
		
		$__tmp['bid_etc'] = nl2br($rs['bid_etc']);
			
		$__tmp['bid_opt_inout'] = $rs['bid_opt_inout'];
		$__tmp['bid_opt_convenience'] = $rs['bid_opt_convenience'];
		$__tmp['bid_opt_safety'] = $rs['bid_opt_safety'];
		$__tmp['bid_opt_device'] = $rs['bid_opt_device'];
		$__tmp['bid_request_time'] = $rs['bid_request_time'];
		
		$bid_state = $rs['bid_state'];
		$__tmp['bid_state_str'] = $conf['common_code']['bid_state'][$bid_state];
		$__tmp['$bid_state_img'] = "<span><img src='/img/main/icon_bid_state_$bid_state.png' alt='".$__tmp['bid_state_str']."'></span>";		
		
		$__tmp['bid_succ_price'] = $rs['bid_succ_price'];
		$__tmp['bid_succ_bidder'] = $rs['bid_succ_bidder'];
		$__tmp['bid_succ_date'] = $rs['bid_succ_date'];
		$__tmp['bid_regdate']  = $rs['bid_regdate'];
		
		$bid_timer = (isset($rs['bid_timer'])) ? trim($rs['bid_timer']) : "";
		$bid_timer_arr = explode(":", $bid_timer);
		$__tmp['bid_timer_hr'] = (isset($bid_timer_arr[0])) ? checkNumeric($bid_timer_arr[0]) : 0;
		$__tmp['bid_timer_min'] = (isset($bid_timer_arr[1])) ? checkNumeric($bid_timer_arr[1]) : 0;
		$__tmp['bid_timer_sec'] = (isset($bid_timer_arr[2])) ? checkNumeric($bid_timer_arr[2]) : 0;
		
		if(strrpos($bid_timer, "-") !== false){
			$__tmp['bid_timer_hr'] = 0;
			$__tmp['bid_timer_min'] = 0;
			$__tmp['bid_timer_sec'] = 0;
		}
		
		$__tmp['bid_max_price'] = (trim($rs['bid_max_price'])) ? number_format($rs['bid_max_price']) : 0;
		$__tmp['bid_go_cnt'] = checkNumeric($rs['bid_go_cnt']);
		$__tmp['bid_succ_price'] = number_format(checkNumeric($rs['bid_succ_price']));
		
		if($bid_state==1){
			$__tmp['bid_result'] = 	"
				<p class=\"time\">
					<strong>
						<span id=\"bid-req-hour$idx\">00</span>:<span id=\"bid-req-min$idx\">00</span>:<span id=\"bid-req-sec$idx\">00</span>
					</strong>
				</p>
				<span class=\"txt\">현재 ".$__tmp['bid_max_price']."만원</span>			
			";	
		}else if($bid_state==2){
			$__tmp['bid_result'] = 	"<p class=\"price\"><strong>".$__tmp['bid_max_price']."</strong><span>만원</span></p><span class='txt'>입찰참여 ".$__tmp['bid_go_cnt']."명</span>";
		}else if($bid_state==3){
			$__tmp['bid_result'] = 	"<p class=\"price\"><strong>".$__tmp['bid_succ_price']."</strong><span>만원</span></p><span class='txt'>최고가 입찰</span>";
		}

		$idx++;
		 
		$output['bid_info']['list'][] = $__tmp;
	}
	
	//입찰상태별 건수
	$sql = "
		SELECT  bid_state, count(bid_state) AS cnt
		FROM (
			SELECT CASE bid_state WHEN IF(isnull(bid_apply_date), 0, TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now())) < 0 THEN 2 ELSE bid_state END AS bid_state 
			FROM bid_info
			WHERE bid_state > 0 AND bid_del_yn = 'N'
		) a
		GROUP BY bid_state;
	";
	$res_state_cnt = $dbc->getRecordList( $sql );
	
	if($res_state_cnt){
		$bid_state_cnt = array(0,0,0,0);
		foreach ( $res_state_cnt as $rs ){
			$rbid_state = $rs['bid_state'];
			$rbid_state_cnt = $rs['cnt'];
			$bid_state_cnt[$rbid_state] = $rbid_state_cnt;
		}
		
		if($bid_state==1){
			$head_title  = "입찰요청";	
		}else if($bid_state==2){
			$head_title  = "입찰중";
		}else if($bid_state==3){
			$head_title  = "입찰완료";
		}
		
		$output['page_title'] =  _TITLE ." $head_title";
		
		$output['bid_state_cnt'] = "
			<div class='state'>
				<span class='tit'>Today</span>
				<a href='?bid_state=1'><span>요청".$bid_state_cnt[1]."건</span></a>
				<a href='?bid_state=2'><span>진행".$bid_state_cnt[2]."건</span></a>
				<a href='?bid_state=3'><span>완료".$bid_state_cnt[3]."건</span></a>
			</div>
		";	
	}
	
	include _HTML_BASE . "/dealer/list.html";
?>
