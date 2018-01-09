<?php
//session_start();
include 'EpiTwitter/EpiCurl.php';
include 'EpiTwitter/EpiOAuth.php';
include 'EpiTwitter/EpiTwitter.php';
include 'EpiTwitter/TwitterConfig.php';
include("../../includes/connect.php");
include("../includes/check_login.php");

/*unset($_SESSION['TwitterUsername']);
unset($_SESSION['twitter_name']);
unset($_SESSION['twitter_aouth_key']);
unset($_SESSION['twitter_aouth_secret']);*/

$doe = date("Y-m-d h:i:s");

$Twitter = new EpiTwitter($consumer_key, $consumer_secret);

if(isset($_GET['oauth_token']) || (isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])))
{

// user accepted access

	if(empty($_SESSION['oauth_token']) && empty($_SESSION['oauth_token_secret']) )
	{
	
	    $Twitter->setToken($_GET['oauth_token']);
		$token = $Twitter->getAccessToken();
		$_SESSION['oauth_token']=$token->oauth_token;
		$_SESSION['oauth_token_secret']= $token->oauth_token_secret;
		$Twitter->setToken($token->oauth_token, $token->oauth_token_secret);   
	}
	else
	{
	 	$Twitter->setToken($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
	}
	$userData= $Twitter->get_accountVerify_credentials();
	echo '<pre>';
	print_r($userData->response);
	echo '<pre/>';
	$TwitterUsername_new="";
	$TwitterUsername_new=$userData->screen_name;
	$TwitterFullname=$userData->name;
	
	$_SESSION['TwitterUsername_new']=$TwitterUsername_new;
	$_SESSION['TwitterFullname']=$TwitterFullname;
	$oauth_token=$_SESSION['oauth_token'];
	$oauth_token_secret=$_SESSION['oauth_token_secret'];
	// Storing token keys
	
	unset($_SESSION['TwitterUsername']);
	unset($_SESSION['twitter_name']);
	unset($_SESSION['twitter_aouth_key']);
	unset($_SESSION['twitter_aouth_secret']);
	
	
	$tw_sql=mysql_query($aa= "SELECT user_id FROM TwitterUpdate WHERE uname='".$TwitterUsername_new."'");
	echo  '<br>A= '.$aa;
	// exit;
	if(mysql_num_rows($tw_sql) == 0)
	{
		$sql=mysql_query($a="INSERT into TwitterUpdate(uname,name,oauth_token,oauth_token_secret,doe) VALUES ('$TwitterUsername_new', '$TwitterFullname', '$oauth_token', '$oauth_token_secret', '$doe');");
		 echo '<br>1= '.$a;
		$twitterId=mysql_insert_id();
	
		 echo '<br>mem= '.$addmem="update sp_members set twitter_id='".$twitterId."',
									  				twitter_username='".$TwitterUsername_new."',
									   				twitter_name='".$TwitterFullname."',
									   				twitter_aouth_key='".$oauth_token."',
									   				twitter_aouth_secret='".$oauth_token_secret."' where pid='".$userid."'";
		$resmem=mysql_query($addmem) or die(mysql_error());
		session_start();
		$_SESSION['TwitterUsername'] = $TwitterUsername_new;
		$_SESSION['twitter_name'] = $TwitterFullname;
		$_SESSION['twitter_aouth_key'] = $oauth_token;
		$_SESSION['twitter_aouth_secret'] = $oauth_token_secret;
		
		
		
		//exit;
    }
	else
	{
		$twitData=mysql_fetch_array($tw_sql);
		 echo '<br>id= '.$twId=$twitData['user_id'];
		
		 echo '<br>updt= '.$updttwt="update TwitterUpdate set uname='".$TwitterUsername_new."',
										   				 name='".$TwitterFullname."',
										   				 oauth_token='".$oauth_token."',
										   				 oauth_token_secret='".$oauth_token_secret."',
										   				 dou='".$doe."' where user_id='".$twId."'";
		$twures=mysql_query($updttwt) or die(mysql_error());
		
		 echo '<br>ff= '.$addmemr="update sp_members set twitter_id='".$twId."',
									   				twitter_username='".$TwitterUsername_new."',
									   				twitter_name='".$TwitterFullname."',
									   				twitter_aouth_key='".$oauth_token."',
									   				twitter_aouth_secret='".$oauth_token_secret."' where pid='".$userid."'";
		$resmemr=mysql_query($addmemr) or die(mysql_error());
		session_start();
		
		
		$_SESSION['TwitterUsername'] = $TwitterUsername_new;
		$_SESSION['twitter_name'] = $TwitterFullname;
		$_SESSION['twitter_aouth_key'] = $oauth_token;
		$_SESSION['twitter_aouth_secret'] = $oauth_token_secret;
		
		
		
	//	exit;
	}
	$url='social-media.php';
	echo "<script>window.location='$url'</script>";
    header('Location: social-media.php'); //Redirecting Page
    
}
else
{
header('Location: social-media.php.php');
}

?>
