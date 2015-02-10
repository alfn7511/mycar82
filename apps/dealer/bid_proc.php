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
	$head_title  = "입찰하기";
	$output['page_title'] =  _TITLE ." $head_title";
	
	$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "";
	$bid_code = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $bid_code);
	
	$bid_price = (isset($_POST['bid_price'])) ? trim($_POST['bid_price']) : 0;
	
	
	if($bid_code && isset($_COOKIE['deal_id'])){
			
		//입찰하기
		$sql = "
			INSERT INTO bid_process(
			   bid_code
			  ,deal_id
			  ,proc_price
			) VALUES (
			   '$bid_code'
			  ,'".$_COOKIE['deal_id']."'
			  ,'$bid_price'
			);		
		";
		$res = $dbc->query($sql);
		
		
		$sql = "select max(proc_price) AS price, count(*) as cnt from bid_process where bid_code = '$bid_code' ";
		$rs = $dbc-> getRecord($sql);
		
		$bid_max_price = 0;   //입찰최고가
		$bid_go_cnt = 0;      //현재입찰자수
		if($rs) {
			$bid_max_price = $rs['price'];
			$bid_go_cnt = $rs['cnt'];
		}
		
		$dbc->__destruct();		
		
		if($res){
			$arr[] = array("result"=>"Y", "bid_go_cnt"=>$bid_go_cnt, "bid_max_price"=>$bid_max_price);	
		}else{
			$arr[] = array("result"=>"N");
		} 
	}else{
		$arr[] = array("result"=>"N");
	}
	
	echo json_encode($arr);
	exit;	
	
	
	

	
?>
