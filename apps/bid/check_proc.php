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
	$head_title  = "입찰정보확인";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "";
	$bid_phone = (isset($_POST['bid_phone'])) ? trim($_POST['bid_phone']) : "";
	$bid_phone = str_replace("-","",$bid_phone);
	
	$bid_check = 0;
	if($bid_code) {
		$bid_check = $dbc->getCount("bid_info", " bid_code = '".$bid_code."' and bid_phone='$bid_phone' ");
		$dbc->__destruct();		
	}
	
	if($bid_check > 0){		
		setcookie("bid_code", $bid_code, 0, "/", _COOKIE_DOMAIN);
		$arr[] = array("result"=>"Y");
	}else{
		$arr[] = array("result"=>"N");
	}
	
	echo json_encode($arr);
	exit;
	
	
	

	
?>
