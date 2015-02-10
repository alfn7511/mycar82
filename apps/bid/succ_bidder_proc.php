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
	
	$deal_id = (isset($_POST['deal_id'])) ? trim($_POST['deal_id']) : "";
	$deal_id = preg_replace("/[^a-zA-Z0-9\-_\'\"]/", NULL, $deal_id);
	
	$bid_price = (isset($_POST['bid_price'])) ? trim($_POST['bid_price']) : 0;
	
	$bid_code = (isset($_POST['bid_code'])) ? trim($_POST['bid_code']) : "";
		
	
	if($deal_id && isset($_COOKIE['bid_code']) && ($bid_code==trim($_COOKIE['bid_code']))){
			
		//입찰하기
		$sql = "
			UPDATE bid_process
			SET proc_state = 1
			WHERE deal_id='$deal_id' AND bid_code = '$bid_code';
		";
		$res1 = $dbc->query($sql);
		
		$sql = "
			UPDATE bid_info
			SET  bid_succ_price= '$bid_price' 
				,bid_succ_bidder = '$deal_id'
				,bid_succ_date = now()
				,bid_state = 3
			WHERE bid_code = '$bid_code';
		";
		$res2 = $dbc->query($sql);
		
		$dbc->__destruct();		
		
		if($res1 && $res2){
				
			$sql = "select deal_phone from dealer_info where deal_id='$deal_id'";
			$rs = $dbc-> getRecord($sql);
			$dbc->__destruct();
					
			if(!$rs){
				$arr[] = array("result"=>"N");	
				echo json_encode($arr);
				exit;
			}
			
			$deal_phone = trim($rs['deal_phone']);
			if(!$deal_phone){
				$arr[] = array("result"=>"N");	
				echo json_encode($arr);
				exit;	
			}
				
			if(strrpos($deal_phone, "-") !== false){
				$phone =explode('-' , $deal_phone);
				$phone1 = $strTok[0];
				$phone2 = $strTok[1];
				$phone3 = $strTok[2];
			}else{
				if(strlen($deal_phone)==10){
					$phone1 = substr($deal_phone, 0, 3);
					$phone2 = substr($deal_phone, 3, 3);
					$phone3 = substr($deal_phone, 6, 4);	
				}else{
					$phone1 = substr($deal_phone, 0, 3);
					$phone2 = substr($deal_phone, 3, 4);
					$phone3 = substr($deal_phone, 7, 4);	
				}
			}
				
				/*################################## SMS보내기 ###############################################*/
				$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
				
				$host_info = explode("/", $sms_url);
		    	$host = $host_info[2];
		    	$path = $host_info[3];
				
				$sms = array();
				$sms['user_id'] = base64_encode("mycar8282"); //SMS 아이디.
		    	$sms['secure'] = base64_encode("04ff0fc77bab3fee136e1a3adb8cbfa7") ;//인증키
		    	
		    	$msg = "입찰번호[".$bid_code."]에 낙찰되셨습니다. 마이카82에 로그인하여 확인바랍니다.";
		    	$sms['msg'] = base64_encode(stripslashes($msg));
				
				$sms['rphone'] = base64_encode($deal_phone);
		    	$sms['sphone1'] = base64_encode($phone1);
		    	$sms['sphone2'] = base64_encode($phone2);
		    	$sms['sphone3'] = base64_encode($phone3);
				$sms['rdate'] = "";
			    $sms['rtime'] = "";
			    $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
			    $sms['returnurl'] = "";
			    //$sms['testflag'] = base64_encode("Y");
				$sms['testflag'] = "";
			    $sms['destination'] = "";	    
			    $sms['repeatFlag'] = "";
			    $sms['repeatNum'] = "";
			    $sms['repeatTime'] = "";
			    $sms['smsType'] = ""; // LMS일경우
			    		
				
				srand((double)microtime()*1000000);
		    	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
				 // 헤더 생성
			    $header = "POST /".$path ." HTTP/1.0\r\n";
			    $header .= "Host: ".$host."\r\n";
			    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";
			
			    // 본문 생성
			    $data = "";
			    foreach($sms AS $index => $value){
			        $data .="--$boundary\r\n";
			        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			        $data .= "\r\n".$value."\r\n";
			        $data .="--$boundary\r\n";
			    }
			    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";
				
				$fp = fsockopen($host, 80);
				if ($fp) {
				 	fputs($fp, $header.$data);
				    $rsp = '';
				    while(!feof($fp)) { 
				        $rsp .= fgets($fp,8192); 
				    }
				    fclose($fp);
					
			 		$msg = explode("\r\n\r\n",trim($rsp));
			        $rMsg = explode(",", $msg[1]);
			        $Result= $rMsg[0]; //발송결과
			        $Count= $rMsg[1]; //잔여건수
			
			        //발송결과 알림
			        if($Result=="success" || $Result=="reserved") {
			            $arr[] = array("result"=>"Y");
			        } else {
			        	$arr[] = array("result"=>"N");
			        }
					
				} else {
					$arr[] = array("result"=>"N"); 
		    	}
				/*################################## SMS보내기 // ###############################################*/
				
			}else{
				$arr[] = array("result"=>"N");
			}
	
				
		}else{
			$arr[] = array("result"=>"N");
		} 
		
	}else{
		$arr[] = array("result"=>"N");
	}
	
	echo json_encode($arr);
	exit;
?>
