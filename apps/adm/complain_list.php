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
	$output['co_state_cnt'] = "";
	$output['head']['js'] = $htmlObj->makeHead("date.js");
	
	//$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : date("Y-m-d");
	//$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : date("Y-m-d");
	
	$sdate = (isset($_POST['sdate'])) ? trim($_POST['sdate']) : "";
	$edate = (isset($_POST['edate'])) ? trim($_POST['edate']) : "";
	
	$co_del_yn = (isset($_POST['co_del_yn'])) ? trim($_POST['co_del_yn']) : "";
		
	$skey = (isset($_POST['skey'])) ? trim($_POST['skey']) : "";
	$sword = (isset($_POST['sword'])) ? trim($_POST['sword']) : "";	
	
	//입찰내역
	$where = "1=1";
	
	if($sdate) $where .= " and DATEDIFF(co_regdate, '$sdate')>=0";
	if($edate) $where .= " and DATEDIFF('$edate', co_regdate)>=0";
	
	if($co_del_yn)	$where .= " and co_del_yn = '$co_del_yn'";
	
	if($sword){
		if($skey=="title"){
			$where .= " and co_title like '%$sword%'";	
		}else if($skey=="content"){
			$where .= " and co_content like '%$sword%'";
		}
	}

	// 페이지 Navigation
	$pagesize = (isset($_POST['pagesize'])) ? trim($_POST['pagesize']) : 10;
		
	$gpage = (isset($_GET['gpage'])) ? trim($_GET['gpage']) : 1;
	$gpage = ereg_replace("[^0-9]", NULL, $gpage);
	
	$offset = (($gpage-1) * $pagesize);	
	
	$sql = "
		SELECT co_idx, co_title, co_content, co_del_yn, co_regdate 
		FROM complain_info
		WHERE $where
		ORDER BY co_regdate DESC 
		LIMIT $offset, $pagesize
	";
	//echo $sql;
	$res = $dbc->getRecordList($sql);
	$dbc->__destruct();	
	$tcnt = sizeof($res);
	$output['complain_info']['tcnt'] = number_format($tcnt);
	$output['complain_info']['list'] = "";
	
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
		$__tmp['co_idx']			= $rs['co_idx'];		
		$__tmp['co_title']		= $rs['co_title'];
		$__tmp['co_content']		= nl2br($rs['co_content']);
		$__tmp['co_del_yn']		= $rs['co_del_yn'];
		$__tmp['co_regdate']		= $rs['co_regdate'];
				
		$__tmp['idx'] = (($pagesize*$page->mPageCount) - ($pagesize*$page->mPageCount - $tcnt) - ($pagesize*($gpage-1))) - $i++;
		$output['complain_info']['list'][] = $__tmp;
	}
	
	$output['complain_info']['page'] = $page->printPaging();

	include _HTML_BASE . "/adm/complain_list.html";
?>
