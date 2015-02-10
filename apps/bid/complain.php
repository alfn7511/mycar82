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
	$output['head'] = array();
	$output['body'] = array();
	$output['head']['js'] = "";
	$output['body']['hidden_cmd'] = "";
	$output['bid_state_cnt'] = "";

	include _HTML_BASE . "/bid/complain.html";
?>
