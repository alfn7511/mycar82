<?
// ######################################################################
//	Function name	: quote_smart
//	Parameter		:
//			value					= 문자열
//	Return				: String
//	Description		: Injection 방지
// ######################################################################
function quote_smart($value){
	// Stripslashes
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	// Quote if not integer
	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string($value) . "'";
	}
	return $value;
}

// ######################################################################
//	Function name	: checkCSRPToken
//	Parameter		:
//	Return				: String
//	Description		: CSRP 방어 Token 확인
// ######################################################################
function checkCSRPToken($token){
	$checkCSRPToken = False;
	If ($_SESSION['token'] == $token) $checkCSRPToken = True;
	return $checkCSRPToken;
}
?>