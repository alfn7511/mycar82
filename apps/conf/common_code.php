<?php
$common_code = array();
$common_code['email'] = array(
							""		=> "직접입력",
							"nate.com"		=> "nate.com",
							"naver.com"		=> "naver.com",
							"gmail.com"		=> "gmail.com",	
							"dreamwiz.com"	=> "dreamwiz.com",
							"empal.com"		=> "empal.com",
							"hanmir.com"	=> "hanmir.com",
							"hotmail.com"	=> "hotmail.com",
							"lycos.co.kr"	=> "lycos.co.kr",
							"yahoo.co.kr"	=> "yahoo.co.kr",
							"chollian.net"	=> "chollian.net",
							"hitel.net"		=> "hitel.net",
							"freechal.com"	=> "freechal.com",
							"korea.com"		=> "korea.com",
							"daum.net"		=> "daum.net",
							"paran.com"		=> "paran.com"
						);

$common_code['mobile'] = array(
							"010"	=> "010",
							"011"	=> "011",
							"016"	=> "016",
							"017"	=> "017",
							"018"	=> "018",
							"019"	=> "019"
						);
	
$common_code['tel'] = array(
							"02"	=> "02",
							"031"	=> "031",
							"032"	=> "032",
							"033"	=> "033",
							"034"	=> "041",
							"042"	=> "042",
							"043"	=> "043",
							"051"	=> "051",
							"052"	=> "052",
							"053"	=> "053",
							"054"	=> "054",
							"055"	=> "055",
							"061"	=> "061",
							"062"	=> "062",
							"063"	=> "063",
							"064"	=> "064"
						);


$common_code['gender'] = array(
							"M"	=> "남성",
							"F"	=> "여성"
						);

$common_code['fuel'] = array(
							"0"	=> "휘발유",
							"1"	=> "경유"
						);						
						
$common_code['transmission'] = array(
							"0"	=> "자동",
							"1"	=> "수동"
						);
						
$common_code['accident_check'] = array(
							"0"	=> "무사고",
							"1"	=> "사고"
						);	
						
$common_code['owner_check'] = array(
							"0"	=> "없음",
							"1"	=> "1인 신조",
							"2"	=> "2인 신조",
							"3"	=> "3인 신조",
							"4"	=> "4인 신조",
						    "5"	=> "5인 신조"
						);	
						
$common_code['bid_state'] = array(
							"0"	=> "입찰요청",
							"1"	=> "입찰승인",
							"2"	=> "입찰진행",
							"3"	=> "입찰완료"
						);

$common_code['deal_level'] = array(
							"0"	=> "새내기",
							"1"	=> "우수딜러",
							"2"	=> "파워딜러",
							"9"	=> "관리자딜러"
						);								


//$member['BirthY'][0] = "선택";
for ($year=1930; $year <= 2020; $year++) $common_code['BirthY'][$year] = $year;

//$member['BirthM'][0] = "선택";
for ($month=1; $month <= 12; $month++) $common_code['BirthM'][$month] = ($month < 10)? "0".$month : $month;

//$member['BirthD'][0] = "선택";
for ($day=1; $day <= 31; $day++) $common_code['BirthD'][$day] = ($day < 10)? "0".$day : $day;	


$conf['common_code'] = $common_code;
?>