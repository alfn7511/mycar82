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
	
	$co_title = (isset($_POST['co_title'])) ? trim($_POST['co_title']) : "";
	$co_content = (isset($_POST['co_content'])) ? trim($_POST['co_content']) : "";
	
	if($co_title){

		$sql = "
			INSERT INTO complain_info(
			   co_title
			  ,co_content
			) VALUES (
			  '$co_title'
			  ,'$co_content'
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
