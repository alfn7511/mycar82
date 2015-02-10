<?php 
	// ######################################################################
	// Initial
	// ######################################################################
	define("_CONF_DBCON", true);
	define("_LOAD_HTML", true);
	define("_LOAD_JAVASCRIPT", true);
	define("_LOAD_RANDOM", true);
	require_once "../init.php";


	// ######################################################################
	// Main Routine
	// ######################################################################	
	
	// ######################################################################
	// check order variables, require field's
	// ######################################################################
	$mode = (isset($_POST['mode'])) ? trim($_POST['mode']) : "";
	
	$deal_idx = (isset($_POST['deal_idx'])) ? trim($_POST['deal_idx']) : 0;	
	$deal_id = (isset($_POST['deal_id'])) ? trim($_POST['deal_id']) : "" ;
	$deal_pwd = (isset($_POST['deal_pwd'])) ? trim($_POST['deal_pwd']) : "" ;
	$deal_name = (isset($_POST['deal_name'])) ? trim($_POST['deal_name']) : "" ;
	$deal_addr = (isset($_POST['deal_addr'])) ? trim($_POST['deal_addr']) : "" ;	
	$deal_boss = (isset($_POST['deal_boss'])) ? trim($_POST['deal_boss']) : "" ;
	$deal_phone = (isset($_POST['deal_phone'])) ? trim($_POST['deal_phone']) : "" ;
	$deal_tel = (isset($_POST['deal_tel'])) ? trim($_POST['deal_tel']) : "" ;
	$deal_office_tel = (isset($_POST['deal_office_tel'])) ? trim($_POST['deal_office_tel']) : "" ;
	$deal_introduce = (isset($_POST['deal_introduce'])) ? trim($_POST['deal_introduce']) : "" ;
	$deal_level = (isset($_POST['deal_level'])) ? trim($_POST['deal_level']) : "" ;
	$deal_del_yn = (isset($_POST['deal_del_yn'])) ? trim($_POST['deal_del_yn']) : "" ;
	
	$deal_pwd_check = 0;
		
	if($mode == "edit" && isset($_COOKIE['deal_id'])) {
		$deal_pwd_check = $dbc->getCount("dealer_info", " deal_id = '$deal_id' and deal_pwd=password('$deal_pwd') ");
		
		if($deal_pwd_check == 0){
			print $jsObj->msgGoUrl(iconv("UTF-8","EUC-KR","패스워드가 틀립니다."), "/apps/dealer/join.php");
			exit;
		}
	}
	
	
	$deal_img = "";
	$deal_attach = "";
	/* ############################ 딜러 본인사진 ################################*/
	if($_FILES['deal_img']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["deal_img"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $deal_id."_img";
		$ext = end(explode(".", strtolower($_FILES['deal_img']['name'])));
		$deal_img = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["deal_img"]["tmp_name"], "../../data/dealer_join/$deal_img");
		if(!$tmp_result) {
			echo $_FILES["deal_img"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.1";
			exit;
		}
	}		
	/* ############################ // 딜러 본인사진 ################################*/	
	  
	/* ############################ 딜러증 첨부 ################################*/
	if($_FILES['deal_attach']['tmp_name']){	
		//제출서류 파일확장자 체크
		$allowedExtensions = array("jpg","jpeg","gif","png");
		if($_FILES["deal_attach"]['tmp_name'] != "") {
			foreach ($_FILES as $file) { 
				if ($file['tmp_name'] > '') { 
	 				if (!in_array(end(explode(".", strtolower($file['name']))), $allowedExtensions)) { 
	 					echo $jsObj->msgBack(iconv("UTF-8","EUC-KR",$file['name']."\\n지원하지 않는 파일 형식입니다."));
	 					exit;
	  				} 
				} 
			}
		}
	
		//파일 네이밍
		$second = explode(" ", microtime()) ;
		$naming = $deal_id."_attach";
		$ext = end(explode(".", strtolower($_FILES['deal_attach']['name'])));
		$deal_attach = $naming.".".$ext;
		
		$tmp_result = move_uploaded_file($_FILES["deal_attach"]["tmp_name"], "../../data/dealer_join/$deal_attach");
		if(!$tmp_result) {
			echo $_FILES["deal_attach"]["tmp_name"]."\\n파일 전송이 실패하였습니다. \\n잠시 후에 다시 올려주세요.2";
			exit;
		}
	}	
	/* ############################ // 챠량사진업로드 2 ################################*/
	
	
	
	if($mode=="add"){
		
		$sql = "
			INSERT INTO dealer_info(
			  deal_id
			  ,deal_pwd
			  ,deal_name
			  ,deal_addr
			  ,deal_boss
			  ,deal_phone
			  ,deal_tel
			  ,deal_office_tel
			  ,deal_img_file
			  ,deal_introduce
			  ,deal_attach_file
			) VALUES (
			  '$deal_id'
			  ,password('$deal_pwd')
			  ,'$deal_name'
			  ,'$deal_addr'
			  ,'$deal_boss'
			  ,'$deal_phone'
			  ,'$deal_tel'
			  ,'$deal_office_tel'
			  ,'$deal_img'
			  ,'$deal_introduce'
			  ,'$deal_attach'
			)
		";		
		$res = $dbc->query($sql);
		//$idx = $dbc->getInsertId();
		
	}else{
		
		$sql = "
			UPDATE dealer_info
			SET
			   deal_name = '$deal_name'
			  ,deal_addr = '$deal_addr'
			  ,deal_boss = '$deal_boss'
			  ,deal_phone = '$deal_phone'
			  ,deal_tel = '$deal_tel'
			  ,deal_office_tel = '$deal_office_tel'
		";
		
		if($deal_pwd) 		$sql .= ",deal_pwd = password('$deal_pwd') ";
		if($deal_attach) 	$sql .= ",deal_attach_file = '$deal_attach'";
		if($deal_img) 		$sql .= ",deal_img_file = '$deal_img'";
		if($deal_level) 	$sql .= ",deal_level = '$deal_level'";
		if($deal_del_yn) 	$sql .= ",deal_del_yn = '$deal_del_yn'";		
		
		$sql .= "
			,deal_introduce = '$deal_introduce'
			WHERE deal_idx = $deal_idx
		";
		$res = $dbc->query($sql);
	}
	
	if(!$res){
		echo "fail : ". $sql;
		exit;
	}
	$dbc->__destruct();
	
	if(isset($_COOKIE['admin_id'])) {
		$return_url = "/apps/adm/dealer_info_view.php?deal_id=".$deal_id;
	}else{
		$return_url = "/apps/dealer/view.php";
	}	
	
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>딜러회원가입</title>
</head>
<body>
<?php if($mode=="add"){ ?>
<script type="text/javascript">	
	if(confirm("회원가입이 완료되었습니다. \n\n 로그인하시겠습니까?")){
		location.href = "/apps/dealer/login.php";
	}else{
		location.href = "/";
	}
</script>
<?php }else{ ?>
<script type="text/javascript">	
	//alert("회원정보가 수정되었습니다.");
	location.href = "<?=$return_url?>";
</script>
<?php } ?>	
</body>
</html>