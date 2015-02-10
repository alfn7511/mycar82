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
	$head_title  = "신고하기";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$deal_id = (isset($_POST['deal_id'])) ? trim($_POST['deal_id']) : "";
	$deal_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_id);
	
	$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "";
	$bid_code = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $bid_code);
	
	$r_mark = (isset($_POST['r_mark'])) ? trim($_POST['r_mark']) : 0;
	$r_content = (isset($_POST['r_content'])) ? trim($_POST['r_content']) : "";
	
	if($r_content){

		$sql = "
			INSERT INTO bid_review(
			   bid_code
			  ,deal_id
			  ,r_mark
			  ,r_content
			) VALUES (
			   '$bid_code'
			  ,'$deal_id'
			  ,'$r_mark'
			  ,'$r_content'
			)
		";
		$res = $dbc->query($sql);
		
		if($res){
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
