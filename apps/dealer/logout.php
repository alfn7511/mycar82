<?php

/** 
 * Logout Process 
 * ------------------------------------
 * @author 	inhye inhye.kim@touchwise.co.kr
 * @desc	2015.01.28 18:44
 * @version	1.0.0
 */

// ######################################################################
// Initial
// ######################################################################

	@header("P3P: CP='ALL IND DSP COR ADM CONo CUR CUSo IVAo IVDo PSA PSD TAI TELo OUR SAMo CNT COM INT NAV ONL PHY PRE PUR UNI'");
	@header("Progma:no-cache");
	@header("Cache-Control:no-cache,must-revalidate");

	define("_LOAD_JAVASCRIPT", true);
	require_once "../init.php";

	
	if(isset($_COOKIE['deal_id'])) {		
		setcookie ( "deal_id", "", time () - 3600, "/", _COOKIE_DOMAIN);
		setcookie ( "deal_id", "", time () - 3600, "/");
		
		setcookie ( "deal_name", "", time () - 3600, "/", _COOKIE_DOMAIN);
		setcookie ( "deal_name", "", time () - 3600, "/");
		
		setcookie ( "deal_level", "", time () - 3600, "/", _COOKIE_DOMAIN);
		setcookie ( "deal_level", "", time () - 3600, "/");
		unset($_COOKIE);
		
		//print $jsObj->msgGoUrl(iconv("UTF-8","EUC-KR","로그아웃되었습니다."), "/apps/dealer/login.php");
	}
	
	print $jsObj->noMsgGoUrl("/apps/dealer/login.php");
	exit;

?>
