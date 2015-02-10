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
	
	if(!isset($_COOKIE['deal_id'])) {
		$arr[] = array("result"=>"login");	
		echo json_encode($arr);
		exit;
	}
	
	$deal_id = (isset($_COOKIE['deal_id'])) ? trim($_COOKIE['deal_id']) : "";
	$deal_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_id);
	
	$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "";
	$bid_code = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $bid_code);
	
	if($bid_code){
		$zzim_cnt = $dbc->getCount("bid_zzim", " deal_id = '".$deal_id."' AND bid_code = '$bid_code' AND z_del_yn='N' ");
		
		if($zzim_cnt>0){
			$arr[] = array("result"=>"D");
		}else{
			$sql = "
				INSERT INTO bid_zzim(
				  deal_id
				  ,bid_code
				) VALUES (
				  '$deal_id'
				  ,'$bid_code'
				)
			";
			$res = $dbc->query($sql);			
			if($res){
				$arr[] = array("result"=>"Y");	
			}else{
				$arr[] = array("result"=>"N");
			}	
		}
	}else{
		$arr[] = array("result"=>"N");
	}
	
	echo json_encode($arr);
	exit;
	
?>
