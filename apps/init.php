<?php /* vim: set ts=4 sw=4 syntax=php fdm=marker: */

###########################################################################
// 브라우저 접속 거절
//$_SERVER['SCRIPT_FILENAME'] == __FILE__ && die("Reject");

// 초기화 파일 로드 여부
// if (defined("_CONF_LOADED")) return; define("_CONF_LOADED", true);

// ###########################################################################
// 전역 변수 초기화
unset($conf);
# unset($obj);

// ###########################################################################
// 기본 경로
define("_BASE", dirname(dirname(__FILE__)));
# define("_PBASE", _BASE);
define("_APP_BASE", _BASE . "/apps");

define("_IS_DEV", ($_SERVER['SERVER_ADDR']=="192.168.1.104") ? TRUE : FALSE);
define("_COOKIE_DOMAIN", (_IS_DEV) ? "192.168.1.104" : "mycar82.co.kr");

// 템플릿 파일 경로
define("_HTML_BASE", _BASE . "/html");
define("_ADMIN_BASE", _BASE . "/Admin");

// ##########################################################################
// global conf
// ##########################################################################

$conf['service_name'] = "mycar82";
$conf['domain'] = (_IS_DEV) ? "192.168.1.104" : "mycar82.co.kr";
$conf['base_domain'] = (_IS_DEV) ? "192.168.1.104" : "mycar82.co.kr";
$conf['admin_mail'] = "alfn7511@gmail.com";

// 시간 정보
date_default_timezone_set('Asia/Seoul');
$conf['microtime'] = explode(" ", microtime());
$conf['now'] = $conf['microtime'][1];

// 전역상수
define("_EXT", ".php");
define("_NOW", date("Y-m-d H:i:s", $conf['now']));
define("_TITLE", "마이카82  ::  ");


// ###########################################################################
// DB 설정
	$conf['dbi']['master']['host'] = "localhost";
	$conf['dbi']['master']['port'] = NULL;
if(_IS_DEV){
	$conf['dbi']['master']['user'] = "root";
	$conf['dbi']['master']['pass'] = "rlalsp";
	$conf['dbi']['master']['name'] = "mycar";
}else{
	$conf['dbi']['master']['user'] = "mycar82";
	$conf['dbi']['master']['pass'] = "sock8282";
	$conf['dbi']['master']['name'] = "mycar82";
}

// 개별 설정 로드
defined("_CONF_COMMON_CODE")	&& include _APP_BASE . "/conf/common_code" . _EXT;
defined("_CONF_DBCON")			&& include _APP_BASE . "/conf/dbcon" . _EXT;

// ###########################################################################
// 기본 모듈 로드
defined("_WITHOUT_DB")			|| Import($dbc,	"MySQL", $conf['dbi']['master']);
defined("_LOAD_HTML")			&& Import($htmlObj, "Html");
defined("_LOAD_JAVASCRIPT")		&& Import($jsObj, "JavaScript");
defined("_LOAD_SMS")			&& Import($smsObj, "sms");
defined("_LOAD_MAIL")			&& Import($mailObj, "phpmailer");
defined("_LOAD_STRING")			&& Import($string, "String");
defined("_LOAD_RANDOM")			&& Import($randObj, "PHPRandom");
defined("_LOAD_PAGE")			&& Import($page, "Page");


// ##########################################################################
// session
// ##########################################################################
if ( defined("_WITHOUT_SESSION") )
{
	$lifetime = 60 * 60 * 6;		// 기본 유효시간

	session_name("mycar82");
	session_set_cookie_params(0, "/", "." . $conf['base_domain']);
	session_start();
}

function Import (&$variable, $class_name, $args=NULL)
{
	FileInclude($class_name);
	if (!is_object($variable))
	{
		//$is_debug && print "Loaded Module : $class_name<BR>\n";
		$variable = new $class_name($args);
		return true;
	}
	return false;
}
function FileInclude ($class_name)
{
	$file = _APP_BASE . "/_lib/class." . $class_name . _EXT;
	// include_once $file;
	in_array($file, get_included_files()) || include $file;
}

include $_SERVER['DOCUMENT_ROOT'] . "/apps/_lib/function.User.php";
include $_SERVER['DOCUMENT_ROOT'] . "/apps/_lib/function.Util.php";
?>
