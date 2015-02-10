<?php 
	// ######################################################################
	// Initial
	// ######################################################################
	define("_CONF_DBCON", true);
	define("_CONF_COMMON_CODE", true);
	define("_LOAD_HTML", true);
	define("_LOAD_JAVASCRIPT", true);
	require_once "./apps/init.php";


	// ######################################################################
	// Main Routine
	// ######################################################################	
	
	// ######################################################################
	// check order variables, require field's
	// ######################################################################

	$sql = " UPDATE bid_info SET bid_state=2 WHERE TIMEDIFF(date_add(bid_apply_date, interval bid_request_time hour), now()) <=0 AND bid_state = 1 AND bid_apply_date IS NOT NULL";
	$res = $dbc->query($sql);
    header("Location:/apps/bid/list.php");	
?>