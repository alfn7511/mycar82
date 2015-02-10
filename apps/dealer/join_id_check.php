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
	$head_title  = "아이디중복확인";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$deal_id = (isset($_POST['deal_id'])) ? trim($_POST['deal_id']) : "";
	$deal_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_id);
	
	$deal_id_chk_cnt = 0;
	if($deal_id<>""){
		$deal_id_chk_cnt = $dbc->getCount("dealer_info", " deal_id = '".$deal_id."' and deal_del_yn='N' ");
	}
	
	if($deal_id_chk_cnt==0){
		$arr[] = array("result"=>"Y");
	}else{
		$arr[] = array("result"=>"N");
	}
	echo json_encode($arr);
	exit;	
	
	
	

	
?>
