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
	$head_title  = "딜러회원리스트";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['deal_state_cnt'] = "";
	$output['head']['js'] = $htmlObj->makeHead("date.js");
	
	//$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : date("Y-m-d");
	//$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : date("Y-m-d");
	
	$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : "";
	$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : "";
	
	$deal_del_yn = (isset($_POST['deal_del_yn'])) ? trim($_POST['deal_del_yn']) : "";
	
	
	$deal_state = (isset($_POST['deal_state'])) ? trim($_POST['deal_state']) : "";
		
	$skey = (isset($_POST['skey'])) ? trim($_POST['skey']) : "";
	$sword = (isset($_POST['sword'])) ? trim($_POST['sword']) : "";	
	
	//입찰내역
	$where = "1=1";
	
	if($sdate) $where .= " and DATEDIFF(deal_regdate, '$sdate')>=0";
	if($edate) $where .= " and DATEDIFF('$edate', deal_regdate)>=0";
	
	if($deal_del_yn)	$where .= " and deal_del_yn = '$deal_del_yn'";
	if($deal_state<>"")	$where .= " and deal_state = $deal_state";
	
	if($sword){
		if($skey=="name"){
			$where .= " and deal_name like '%$sword%'";	
		}else if($skey=="tel"){
			$where .= " and deal_phone like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and deal_car_name like '%$sword%'";
		}else if($skey=="car_name"){
			$where .= " and deal_code like '%$sword%'";
		}
	}

	// 페이지 Navigation
	$pagesize = (isset($_POST['pagesize'])) ? trim($_POST['pagesize']) : 10;
	$tcnt = $dbc->getCount("dealer_info",$where);
	$output['deal_info']['tcnt'] = 0;
	$output['deal_info']['tcnt'] = number_format($tcnt);
	
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
		SELECT deal_idx, deal_level, deal_id, deal_pwd, deal_name, deal_addr, deal_boss, deal_phone, deal_tel, deal_office_tel
		      , deal_img_file, deal_introduce, deal_attach_file, deal_del_yn, deal_regdate 
		FROM dealer_info
		WHERE $where
		ORDER BY deal_regdate DESC 
		LIMIT $offset, $pagesize
	";
	//echo $sql;
	$res = $dbc->getRecordList($sql);
	$dbc->__destruct();	
	$output['deal_info']['cnt'] = sizeof($res);
	$output['deal_info']['list'] = "";
	
	$i = 0;
	foreach ( $res as $rs ){
		unset($__tmp);
		$__tmp = array();
		$__tmp['deal_idx']		= $rs['deal_idx'];		
		$__tmp['deal_id']		= $rs['deal_id'];		
		$__tmp['deal_name']		= $rs['deal_name'];
		$__tmp['deal_addr']		= $rs['deal_addr'];
		$__tmp['deal_boss']		= $rs['deal_boss'];
		$__tmp['deal_phone']		= $rs['deal_phone'];
		$__tmp['deal_tel']		= $rs['deal_tel'];
		$__tmp['deal_office_tel']		= $rs['deal_office_tel'];
		
		$__tmp['deal_introduce']		= $rs['deal_introduce'];
		$__tmp['deal_attach_file']		= $rs['deal_attach_file'];
		$__tmp['deal_del_yn']		= $rs['deal_del_yn'];
		$__tmp['deal_regdate']		= $rs['deal_regdate'];
		
		//회원등급
		$deal_level = checkNumeric($rs['deal_level']);
		$__tmp['deal_level'] = $deal_level;
		$__tmp['deal_level_str'] = $conf['common_code']['deal_level'][$deal_level];
		
		//회원사진
		$__tmp['deal_img'] = "<img src='/data/dealer_join/".$rs['deal_img_file']."' alt='".$rs['deal_name']."' />";
		
		
		$__tmp['idx'] = (($pagesize*$page->mPageCount) - ($pagesize*$page->mPageCount - $tcnt) - ($pagesize*($gpage-1))) - $i++;
		$output['deal_info']['list'][] = $__tmp;
	}
	
	$output['deal_info']['page'] = $page->printPaging();

	include _HTML_BASE . "/adm/dealer_info_list.html";
?>
