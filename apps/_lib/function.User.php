<?php
// ######################################################################
//	Function name	: getMemberLevel
//	Parameter		:
//			value					= 검사 문자열
//	Return				: String
//	Description		:
// ######################################################################
function getMemberLevelName($type){
	switch($type){
		case 0 :
			$type_name = "일반회원";
			break;
		case 1 :
			$type_name = "부동산AGENT회원";
			break;
		case 2 :
			$type_name = "개업공인중개사회원";
			break;
		case 3 :
			$type_name = "법인회원";
			break;
		default : 
			$type_name = "";
			break;
	}
	return $type_name;
}

// ######################################################################
//	Function name	: getPayMethodName
//	Parameter		:
//			value					= 검사 문자열
//	Return				: String
//	Description		:
// ######################################################################
function getPayMethodName($pay_method){
	switch($pay_method){
		case "100000000000" :
			$pay_method_name = "신용카드";
			break;
		case "010000000000" :
			$pay_method_name = "계좌이체";
			break;
		case "001000000000" :
			$pay_method_name = "가상계좌";
			break;
		case "000010000000" :
			$pay_method_name = "휴대폰";
			break;
		default : 
			$pay_method_name = "온라인입금";
			break;
	}
	return $pay_method_name;
}

// ######################################################################
//	Function name	: getOrderStateName
//	Parameter		:
//			value					= 검사 문자열
//	Return				: String
//	Description		:
// ######################################################################
function getOrderStateName($pay_state){
	switch($pay_state){
		case 0 :
			$pay_state_name = "입금확인중";
			break;
		case 1 :
			$pay_state_name = "결제완료";
			break;
		case 2 :
			$pay_state_name = "취소";
			break;
		default : 
			$pay_state_name = "-";
			break;
	}
	return $pay_state_name;
}
?>