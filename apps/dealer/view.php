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
	
	if(!isset($_COOKIE['deal_id']) && !isset($_COOKIE['bid_code'])) {
		print $jsObj->msgGoUrl(iconv("UTF-8","EUC-KR", "딜러로그인하시거나 입찰로그인을 하셔야합니다."), "/");
		exit;
	}
	
	$deal_id = (isset($_COOKIE['deal_id'])) ? trim($_COOKIE['deal_id']) : trim($_GET['deal_id']);	
	
	$output = array();
	$head_title  = "내정보";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['bid_state_cnt'] = "";
		
	$sql = "
		SELECT deal_idx, deal_level, deal_id, deal_pwd, deal_name, deal_addr, deal_boss, deal_phone, deal_tel, deal_office_tel, deal_img_file, deal_introduce, deal_attach_file, deal_del_yn, deal_regdate  
		FROM dealer_info 
		WHERE deal_id = '$deal_id'
	";
	$rs = $dbc-> getRecord($sql);
	$dbc->__destruct();		
	
	if($rs){
		$deal_idx = $rs['deal_idx'];
		$deal_level = $rs['deal_level'];
		$deal_level_str = $common_code['deal_level'][$deal_level];
		$deal_level_tagImg = "<span class='flag'><img src='/img/sub/tag_level_".$deal_level.".png' alt='$deal_level_str'></span>";
		
		$deal_pwd = $rs['deal_pwd'];
		$deal_name = $rs['deal_name'];
		$deal_addr = $rs['deal_addr'];
		$deal_boss = $rs['deal_boss'];
		$deal_phone = $rs['deal_phone'];
		$deal_tel = $rs['deal_tel'];
		$deal_office_tel = $rs['deal_office_tel'];
		$deal_img_file = $rs['deal_img_file'];
		//$deal_img = $rs['deal_img_file'];
		
		$deal_introduce = nl2br($rs['deal_introduce']);
		$deal_attach_file = $rs['deal_attach_file'];
		$deal_del_yn = $rs['deal_del_yn'];
		$deal_regdate = $rs['deal_regdate'];		
	}
	
	include _HTML_BASE . "/dealer/view.html";
?>
