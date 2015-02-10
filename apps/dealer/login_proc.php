<?php /* vim: set ts=4 sw=4 syntax=php fdm=marker: */
	// ######################################################################
	// Initial
	// ######################################################################
	@header("P3P: CP='ALL IND DSP COR ADM CONo CUR CUSo IVAo IVDo PSA PSD TAI TELo OUR SAMo CNT COM INT NAV ONL PHY PRE PUR UNI'");
	@header("Progma:no-cache");
	@header("Cache-Control:no-cache,must-revalidate");
	
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
	$head_title  = "아이디중복확인";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$deal_id = (isset($_POST['deal_id'])) ? trim($_POST['deal_id']) : "";
	$deal_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_id);
	
	$deal_pwd = (isset($_POST['deal_pwd'])) ? trim($_POST['deal_pwd']) : "";
	$deal_pwd = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_pwd);
	
	$deal_level = "";
	$deal_name = "";
	
	$deal_login_cnt = 0;
	if($deal_id<>"") {
		//$deal_login_cnt = $dbc->getCount("dealer_info", " deal_id = '".$deal_id."' AND deal_pwd = PASSWORD('".$deal_pwd."') AND deal_del_yn='N' ");
		
		$sql = "SELECT deal_name, deal_level FROM dealer_info WHERE deal_id = '".$deal_id."' AND deal_pwd = PASSWORD('".$deal_pwd."') AND deal_del_yn='N'";	
		$rs = $dbc-> getRecord($sql);
		$dbc->__destruct();		
		if($rs){
			$deal_name = trim($rs['deal_name']);
			$deal_level = trim($rs['deal_level']);
			setcookie("deal_id", $deal_id, 0, "/", _COOKIE_DOMAIN);
			setcookie("deal_level", $deal_level, 0, "/", _COOKIE_DOMAIN);
			setcookie("deal_name", $deal_name, 0, "/", _COOKIE_DOMAIN);
			$arr[] = array("result"=>"Y");
		}else{
			$arr[] = array("result"=>"N");
		}
	}else{
		$arr[] = array("result"=>"N");
	}
	
	echo json_encode($arr);
	exit;	
	
	
	

	
?>
