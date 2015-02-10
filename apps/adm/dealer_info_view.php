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
	$head_title  = "딜러회원정보-상세보기";
	$output['page_title'] =  _TITLE ." $head_title";
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$mode="add";
	$deal_idx = "";
	$deal_introduce = "";
	
	$deal_id = (isset($_GET['deal_id'])) ? trim($_GET['deal_id']) : trim($_POST['deal_id']);
	
	$sql = "
		SELECT deal_idx, deal_level, deal_id, deal_pwd, deal_name, deal_addr, deal_boss, deal_phone, deal_tel, deal_office_tel, deal_img_file, deal_introduce, deal_attach_file, deal_del_yn, deal_regdate  
		FROM dealer_info 
		WHERE deal_id = '".$deal_id."'
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
		
		$deal_img_file = trim($rs['deal_img_file']);		
		if($deal_img_file) $deal_img = "<img src=\"/data/dealer_join/$deal_img_file\" alt=\"$deal_name 이미지\" style=\"width:30%;\" /><br><br>";
		
		$deal_attach_file = trim($rs['deal_attach_file']);
		if($deal_attach_file) $deal_attach = "<img src=\"/data/dealer_join/$deal_attach_file\" alt=\"$deal_name 딜러증\" style=\"width:30%;\" /><br><br>";
		
		$deal_introduce = $rs['deal_introduce'];
		$deal_del_yn = $rs['deal_del_yn'];
		$deal_regdate = $rs['deal_regdate'];
		
		/*########################## 후기 #############################################*/		
		$output['bid_review'] = array();
		$sql = "
			SELECT r_idx, bid_code, r_mark, r_content, r_regdate, r_del_yn, (SELECT bid_name FROM bid_info  WHERE bid_code = re.bid_code) AS bid_name 
			FROM bid_review re 
			WHERE deal_id = '$deal_id';
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
			$__tmp['bid_code'] = trim($rs['bid_code']);
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
			
					
		$mode="edit";		
	}
	
	$output['body']['deal_level'] = $htmlObj->makeSelect("deal_level", $conf['common_code']['deal_level'], $deal_level, NULL,  NULL, " id=\"deal_level\" class=\"select\" title=\"회원등급\" ");	
	
	unset($_tmp_);
	$_tmp_[] = $htmlObj->makeHidden("mode", $mode, " id=\"mode\" ");
	$_tmp_[] = $htmlObj->makeHidden("deal_idx", $deal_idx, " id=\"deal_idx\" ");	
	if($mode=="edit") $_tmp_[] = $htmlObj->makeHidden("deal_id", $deal_id, " id=\"deal_id\" ");
	
	$output['body']['hidden_cmd_join'] = "\n".implode("\n", $_tmp_);	
		

	include _HTML_BASE . "/adm/dealer_info_view.html";
?>
