<?php
	include $_SERVER['DOCUMENT_ROOT'] . "/apps/_lib/_autoloader.php";
	
	$auth_config = array(
	    'client_id'         => 'ce31452f7c7d4861b187fd7a285d9e62',
	    'client_secret'     => '6352b3da22ed4f6595e7aff261541568',
	    'redirect_uri'      => 'http://patio9.qlinesoft.com/main.php',
	    'scope'             => array( 'likes', 'comments', 'relationships' )
	);	
	$auth = new Instagram\Auth( $auth_config );
	$auth->authorize();
			
	$instagram_access_token = $auth->getAccessToken('5e0ab6c35d0e41a2b0633da851ff9a9f');
	//echo $instagram_access_token;
	//$instagram = new Instagram\Instagram($instagram_access_token);
	
	/*
	$instagram = new Instagram\Instagram;
	$user = $instagram->getUser('alfn7511');
	$media = $user->getMedia();
	foreach( $media as $photo ) {
		echo $media[0];
	}
	 * /

	/*
    // instagram 으로부터 REST 호출
    $instaContents = file_get_contents("https://api.instagram.com/v1/users/self/media/recent?client_id=5d6dac219186472c9f69b5a5bec9e6d8&access_token=0d2dc336761547a383ac693a7c612b4d&count=5");
    $instObj = json_decode($instaContents)->data;
    // 받아온 json data 재가공
    for($i = 0; $i < count($instObj); $i++){
        $json_data[$i] = array($instObj[$i]->images->low_resolution->url, str_replace("\n"," ",$instObj[$i]->caption->text));
    }
    // 새로운 json data encode
    $json = json_encode($json_data);
    echo($json);
	 */
?>