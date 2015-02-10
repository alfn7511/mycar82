<?php
// ######################################################################
//	Function name	: checkNumeric
//	Parameter		:
//			value					= 검사 문자열
//	Return				: Integer
//	Description		:
// ######################################################################
function checkNumeric($value){
	if(is_numeric($value)){
		return (double)$value;
	}else{
		return 0;
	}
}

// ######################################################################
//	Function name	: right
//	Parameter		:
//	Return			: 
//	Description		: 
// ######################################################################
function right($value, $count){
    return substr($value, ($count*-1));
}

// ######################################################################
//	Function name	: left
//	Parameter		:
//	Return			: 
//	Description		: 
// ######################################################################
function left($string, $count){
    return substr($string, 0, $count);
}

// ######################################################################
//	Function name	: isSelected
//	Parameter		:
//			arg1						= 비교값 1
//			arg2						= 비교값 2
//	Return				: String
//	Description		: 값 비교후 selected
// ######################################################################
function isSelected($arg1, $arg2){	
	if($arg1==$arg2){
		return " selected";	
	}
}

// ######################################################################
//	Function name	: isChecked
//	Parameter		:
//			arg1						= 비교값 1
//			arg2						= 비교값 2
//	Return				: String
//	Description		: 값 비교후 selected
// ######################################################################
function isChecked($arg1, $arg2){
	if($arg1==$arg2){		
		return " checked";	
	}
}

// ######################################################################
//	Function name	: getUnixTimeStamp
//	Parameter		:
//	Return				: String
//	Description		: UNIX 타임 추출
// ######################################################################
function getUnixTimeStamp(){
	$baseUTC = mktime(0,0,0,1,1,1970);
	$getUnixTimeStamp = time() - $baseUTC;
	return $getUnixTimeStamp;
}

// ######################################################################
//	Function name	: getGUID
//	Parameter		:
//	Return				: String
//	Description		:
// ######################################################################
function getGUID(){
	if (function_exists('com_create_guid')) {
		return com_create_guid();
	}else {
		mt_srand((double) microtime() * 10000);
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45); // "-"
		$uuid = chr(123) // "{"
		. substr($charid, 0, 8) . $hyphen
		. substr($charid, 8, 4) . $hyphen
		. substr($charid, 12, 4) . $hyphen
		. substr($charid,16, 4) . $hyphen
		. substr($charid,20,12)
		. chr(125); // "}"
		return $uuid;
	}
}

// ######################################################################
//	Function name	: getUserIP
//	Parameter		:
//	Return				: String
//	Description		:
// ######################################################################
function getUserIP(){
	//check ip from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip = $_SERVER['HTTP_CLIENT_IP'];	
	//to check ip is pass from proxy
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


// ######################################################################
//	Function name	: rejectIjt
//	Parameter		:
//			value					= 문자열
//	Return				: String
//	Description		: Injection 방지
// ######################################################################
function rejectIjt($value) {
	$value = str_replace("'","''",$value);
	$value = str_replace(";","",$value);
	$value = str_replace("--","",$value);
	$value = str_replace("select ","",$value);
	$value = str_replace("union ","",$value);
	$value = str_replace("insert ","",$value);
	$value = str_replace("update ","",$value);
	$value = str_replace("delete ","",$value);
	$value = str_replace("create ","",$value);
	$value = str_replace("alter ","",$value);
	$value = str_replace("drop ","",$value);
	$value = str_replace("exec ","",$value);
	return $value; 
}

// ######################################################################
//	Function name	: noCache
//	Parameter		:
//	Return				:
//	Description			:
// ######################################################################
function noCache(){
	header("Expires: Mon 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d, M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

// ######################################################################
//	Function name	: setZeroFill
//	Parameter		:
//			num						= 대상 숫자
//			numLen				= 숫자문자열 자리수
//	Return				: String
//	Description		:
// ######################################################################
function setZeroFill($num, $numLen){
	for($i=0;$i<$numLen;$i++){
		$zeros = $zeros."0";
	}

	return Right($zeros.$num, $numLen);
}
?>