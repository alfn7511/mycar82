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
	$head_title  = "후기리스트";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['r_state_cnt'] = "";
	$output['head']['js'] = $htmlObj->makeHead("date.js");
	
	//$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : date("Y-m-d");
	//$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : date("Y-m-d");
	
	$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : "";
	$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : "";
	
	$r_del_yn = (isset($_POST['r_del_yn'])) ? trim($_POST['r_del_yn']) : "";
	
	
	$r_state = (isset($_POST['r_state'])) ? trim($_POST['r_state']) : "";
		
	$skey = (isset($_POST['skey'])) ? trim($_POST['skey']) : "";
	$sword = (isset($_POST['sword'])) ? trim($_POST['sword']) : "";	
	
	//입찰내역
	$where = "1=1";
	
	if($sdate) $where .= " and DATEDIFF(r_regdate, '$sdate')>=0";
	if($edate) $where .= " and DATEDIFF('$edate', r_regdate)>=0";
	
	if($r_del_yn)	$where .= " and r_del_yn = '$r_del_yn'";
	
	if($sword){
		if($skey=="name"){
			$where .= " and bid_name like '%$sword%'";	
		}else if($skey=="tel"){
			$where .= " and bid_phone like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and bid_car_name like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and re.bid_code like '%$sword%'";
		}else if($skey=="deal_id"){
			$where .= " and re.deal_id like '%$sword%'";
		}else if($skey=="deal_name"){
			$where .= " and re.deal_name like '%$sword%'";
		}
	}

	// 페이지 Navigation
	$pagesize = (isset($_POST['pagesize'])) ? trim($_POST['pagesize']) : 10;
		
	$gpage = (isset($_GET['gpage'])) ? trim($_GET['gpage']) : 1;
	$gpage = ereg_replace("[^0-9]", NULL, $gpage);
	
	$offset = (($gpage-1) * $pagesize);	
	
	$sql = "
		SELECT r_idx, re.bid_code, re.deal_id, r_mark, r_content, r_del_yn, r_regdate, bid_name, bid_phone, bid_car_name, deal_name, bid_car_num
		FROM bid_review re inner join bid_info info on info.bid_code = re.bid_code inner join dealer_info deal on deal.deal_id = re.deal_id
		WHERE $where
		ORDER BY r_regdate DESC 
		LIMIT $offset, $pagesize
	";
	//echo $sql;
	$res = $dbc->getRecordList($sql);
	$dbc->__destruct();	
	$tcnt = sizeof($res);
	$output['review_info']['tcnt'] = number_format($tcnt);
	$output['review_info']['list'] = "";
	
	//paging
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
	
	$i = 0;
	foreach ( $res as $rs ){
		unset($__tmp);
		$__tmp = array();
		$__tmp['r_idx']			= $rs['r_idx'];		
		$__tmp['bid_code']		= $rs['bid_code'];
		$__tmp['deal_id']		= $rs['deal_id'];
		$__tmp['r_content']		= nl2br($rs['r_content']);
		$__tmp['r_del_yn']		= $rs['r_del_yn'];
		$__tmp['r_regdate']		= $rs['r_regdate'];
		$__tmp['bid_name']		= $rs['bid_name'];
		$__tmp['bid_phone']		= $rs['bid_phone'];
		$__tmp['bid_car_name']	= $rs['bid_car_name'];
		$__tmp['bid_car_num']	= $rs['bid_car_num'];
		$__tmp['deal_name']		= $rs['deal_name'];		
		
		//평가점수
		$r_mark = checkNumeric($rs['r_mark']);
		$__tmp['r_mark_img'] = "<img src='/img/sub/bul_s$r_mark.png' alt='$r_mark'>";
		
		$__tmp['idx'] = (($pagesize*$page->mPageCount) - ($pagesize*$page->mPageCount - $tcnt) - ($pagesize*($gpage-1))) - $i++;
		$output['review_info']['list'][] = $__tmp;
	}
	
	$output['review_info']['page'] = $page->printPaging();

	include _HTML_BASE . "/adm/review_list.html";
?>
