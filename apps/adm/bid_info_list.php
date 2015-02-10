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
	$head_title  = "입찰정보리스트";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['bid_state_cnt'] = "";
	$output['head']['js'] = $htmlObj->makeHead("date.js");
	
	//$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : date("Y-m-d");
	//$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : date("Y-m-d");
	
	$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : "";
	$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : "";
	
	$bid_del_yn = (isset($_POST['bid_del_yn'])) ? trim($_POST['bid_del_yn']) : "";
	
	
	$bid_state = (isset($_POST['bid_state'])) ? trim($_POST['bid_state']) : "";
		
	$skey = (isset($_POST['skey'])) ? trim($_POST['skey']) : "";
	$sword = (isset($_POST['sword'])) ? trim($_POST['sword']) : "";
	
	$output['body']['bid_state'] = $htmlObj->makeSelect("bid_state", $conf['common_code']['bid_state'], $bid_state, "선택",  NULL, " id=\"bid_state\" title=\"입찰상태\" class=\"select\" ");
	
	//입찰내역
	$where = "1=1";
	
	if($sdate) $where .= " and DATEDIFF(bid_regdate, '$sdate')>=0";
	if($edate) $where .= " and DATEDIFF('$edate', bid_regdate)>=0";
	
	if($bid_del_yn)	$where .= " and bid_del_yn = '$bid_del_yn'";
	if($bid_state<>"")	$where .= " and bid_state = $bid_state";
	
	if($sword){
		if($skey=="name"){
			$where .= " and bid_name like '%$sword%'";	
		}else if($skey=="tel"){
			$where .= " and bid_phone like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and bid_car_name like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and bid_code like '%$sword%'";
		}
	}

	// 페이지 Navigation
	$pagesize = (isset($_POST['pagesize'])) ? trim($_POST['pagesize']) : 10;
	$tcnt = $dbc->getCount("bid_info",$where);
	$output['bid_info']['tcnt'] = 0;
	$output['bid_info']['tcnt'] = number_format($tcnt);
	
	$gpage = (isset($_GET['gpage'])) ? trim($_GET['gpage']) : 1;
	$gpage = ereg_replace("[^0-9]", NULL, $gpage);

	$params = array(
					'curPageNum' => $gpage,
					'pageVar' => 'gpage',
					'extraVar' => NULL,
					'totalItem' => $tcnt,
					'perPage' => 5,
					'perItem' => $pagesize,
					'prevPage' => "",
					'nextPage' => "",
					'prevPerPage' => '[이전5페이지]',
					'nextPerPage' => '[다음5페이지]',
					'firstPage' => '[처음]',
					'lastPage' => '[끝]',
					'pageCss' => '',
					'curPageCss' => ''
					);
					
	$page->makePageValue($params);

	$offset = (($gpage-1) * $pagesize);	
	
	$sql = "
		SELECT bid_idx, bid_code, bid_name, bid_phone, bid_maker, bid_car_name, bid_car_age, bid_car_num, bid_mileage, bid_capacity, bid_sales_area, bid_transmission, bid_fuel
			, bid_car_img1, bid_car_img2, bid_car_img3, bid_car_img4, bid_car_img5, bid_car_img6, bid_car_img7, bid_car_img8, bid_accident_check, bid_car_owner_check, bid_etc
			, bid_opt_inout, bid_opt_convenience, bid_opt_safety, bid_opt_device, bid_request_time, bid_state, bid_succ_price, bid_succ_bidder, bid_succ_date, bid_regdate, bid_del_yn 
		FROM bid_info
		WHERE $where
		ORDER BY bid_regdate DESC 
		LIMIT $offset, $pagesize
	";
	//echo $sql;
	$res = $dbc->getRecordList($sql);
	$dbc->__destruct();	
	$output['bid_info']['cnt'] = sizeof($res);
	$output['bid_info']['list'] = "";
	
	$i = 0;
	foreach ( $res as $rs ){
		unset($__tmp);
		$__tmp = array();
		$__tmp['bid_idx']		= $rs['bid_idx'];
		$__tmp['bid_code']		= $rs['bid_code'];
		$__tmp['bid_car_name']		= $rs['bid_car_name'];
		$__tmp['bid_car_num']		= $rs['bid_car_num'];
		$__tmp['bid_name']		= $rs['bid_name'];
		$__tmp['bid_phone']		= $rs['bid_phone'];
		$__tmp['bid_sales_area']		= $rs['bid_sales_area'];	
		$__tmp['bid_regdate']		= $rs['bid_regdate'];
		
		$bid_state = checkNumeric($rs['bid_state']);
		$__tmp['bid_state'] = $conf['common_code']['bid_state'][$bid_state];
		
		$__tmp['idx'] = (($pagesize*$page->mPageCount) - ($pagesize*$page->mPageCount - $tcnt) - ($pagesize*($gpage-1))) - $i++;
		$output['bid_info']['list'][] = $__tmp;
	}
	
	$output['bid_info']['page'] = $page->printPaging();

	include _HTML_BASE . "/adm/bid_info_list.html";
?>
