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
	$head_title  = "로그인";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$admin_id = (isset($_POST['admin_id'])) ? trim($_POST['admin_id']) : "";
	$admin_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $admin_id);
	
	$admin_pwd = (isset($_POST['admin_pwd'])) ? trim($_POST['admin_pwd']) : "";
	$admin_pwd = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $admin_pwd);
	
	$admin_level = "";
	$admin_name = "";
	
	$admin_login_cnt = 0;
	
	/*
	if($admin_id<>"") {
		$admin_login_cnt = $dbc->getCount("dealer_info", " admin_id = '".$admin_id."' AND admin_pwd = PASSWORD('".$admin_pwd."') AND admin_del_yn='N' ");
		$dbc->__destruct();
	}
	*/
	
	if($admin_id=="mycar82" && $admin_pwd=="mycar82") $admin_login_cnt = 1;
	
	if($admin_login_cnt>0){
		setcookie("admin_id", $admin_id, 0, "/", _COOKIE_DOMAIN);
		setcookie("admin_name", "마이카82", 0, "/", _COOKIE_DOMAIN);
		$arr[] = array("result"=>"Y");
	}else{
		$arr[] = array("result"=>"N");
	}	
	
	echo json_encode($arr);
	exit;	
	
	
	

	
?>
