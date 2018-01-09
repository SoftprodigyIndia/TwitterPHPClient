<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
<style>
.login-div{
	float: left;
    width: 100%;
    text-align: center;
    padding:40px 0 0;
}
.login-div h4 {
    font-size: 20px;
    color: #333;
    font-weight: 400;
	position:relative;
	margin:0 0 20px;
	padding:0 0 10px;
	font-family: 'Open Sans', sans-serif;
}
.login-div h4::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    height: 4px;
    background: #F45D22;
    width: 65px;
    margin: 0 auto;
    right: 0;
}
.welcome_txt {
    text-align: center;
    width: 100%;
    padding: 40px 0 0;
}
.welcome_txt p{
	font-family: 'Open Sans', sans-serif;
	font-size:20px;
	color:#333;
	font-weight:400;	
}
.welcome_txt p a {
    font-weight: 600;
    text-decoration: none;
    color: #F45D22;
}
.welcome_txt ~ .tweet_box {
    float: left;
    width: 100%;
    text-align: center;
}
h5 {
    width: 100%;
    text-align: center;
    font-size: 20px;
    font-weight: 400;
    color: green;
}
.main-div {
    text-align: center;
    float: left;
    width: 100%;
}
.main-div h4 {
    display: inline-block;
    width: 100%;
    margin: 0;
    padding: 0;
}
.main-div h4 p {
    display: inline-block;
}
table {
    display: inline-block;
    width: auto;
}
.tweet_list {
    text-align: center;
}
.tweet_list strong {
    width: 100%;
    display: inline-block;
    text-align: left;
}
.tweet_list ul {
    padding: 0;
    display: inline-block;
    width: 40%;
    text-align:left;
}
.tweet_list ul li {
    display: inline-block;
    margin: 0 0 20px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    padding: 0 0 20px;
    width: 100%;
}
.tweet_list ul li i {
    float: right;
    text-align: right;
}
.tweet-div {
    display: inline-block;
    width: 40%;
    text-align: left;
}
</style>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include 'EpiTwitter/EpiCurl.php';
include 'EpiTwitter/EpiOAuth.php';
include 'EpiTwitter/EpiTwitter.php';
include 'EpiTwitter/TwitterConfig.php';
//Database Class
include 'User.php';

if(isset($_POST['submit']) && $_POST['submit'] == 'Tweet'){
	$Twitter = new EpiTwitter($consumerKey, $consumerSecret);
	$Twitter->setToken($access_token,$access_token_secret);
	//Twitter status update
	$message = $_POST['updateme'];
	try{
		$status=$Twitter->post_statusesUpdate(array('status' => $message));
		$message 	= "Message shared successfully !";
	}
	catch(\Exception $e){
		$e->getMessage();
		$errors 	= $e->getMessage();
		$val = json_decode($errors);
		if(!empty($val)){
		$message = $val->errors[0]->message;
		}
	}
	if(isset($message)){
		echo '<h5>"'.$message.'"</h5>';
	}
}

$twClient = new EpiTwitter($consumerKey, $consumerSecret ,$access_token ,$access_token_secret,$twitter_screen_name);

if(isset($_GET['oauth_token']) || (isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])))
{
	if(empty($_SESSION['oauth_token']) && empty($_SESSION['oauth_token_secret']) )
	{	
	    $twClient->setToken($_GET['oauth_token']);
		$token 							= $twClient->getAccessToken();
		$_SESSION['oauth_token']		= $token->oauth_token;
		$_SESSION['oauth_token_secret']	= $token->oauth_token_secret;
		$twClient->setToken($token->oauth_token, $token->oauth_token_secret);   
	}
	else
	{
	 	$twClient->setToken($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
	}
	$userInfo= $twClient->get_accountVerify_credentials();
	
	$user = new User();
	
	//Insert or update user data to the database
	$name = explode(" ",$userInfo->name);
	$fname = isset($name[0])?$name[0]:'';
	$lname = isset($name[1])?$name[1]:'';
	$profileLink = 'https://twitter.com/'.$userInfo->screen_name;
	$twUserData = array(
		'oauth_provider'=> 'twitter',
		'oauth_uid'     => $userInfo->id,
		'first_name'    => $fname,
		'last_name'     => $lname,
		'email'         => '',
		'gender'        => '',
		'locale'        => $userInfo->lang,
		'picture'       => $userInfo->profile_image_url,
		'link'          => $profileLink,
		'username'      => $userInfo->screen_name
	);
	
	$userData = $user->checkUser($twUserData);
    $_SESSION['userData'] = $userData;   
	//~ echo '<pre>';print_r($userInfo->__obj);echo '<br>';die;
	$output = '<div class="main-div">';   
	$output .= '<div class="welcome_txt"><p>Welcome <strong>'.$userInfo->screen_name.'</strong> (Twitter ID : '.$userInfo->id.'). <a href="logout.php">Logout</a>!</p></div>';   
    //Display profile iamge and tweet form
    $output .= '<h4>Your profile link :<p><a href="'.$profileLink.'">'.$profileLink.'</p></a></h4>';
    $output .= '<h4>Followers :<p>'.$userInfo->followers_count.'</p></h4>';
    $output .= '<h4>Friends Count :<p>'.$userInfo->friends_count.'</p></h4>';
    $output .= '<h4>App Source :<p>'.$userInfo->status->source.'</p></h4>';
    $output .= '<div class="tweet_box">';
    $output .= '<img src="'.$userInfo->profile_image_url.'" width="120" height="110"/>';
    $output .= '</div>';
    $output .= '<div class="message">"'.$message.'"</div>';
    $output .= '<form method="post" action=""><table width="200" border="0" cellpadding="3">';
    $output .= '<tr>';
    $output .= '<td><textarea name="updateme" cols="60" rows="4"></textarea></td>';
    $output .= '</tr>';
    $output .= '<tr>';
    $output .= '<td><input type="submit" name="submit" value="Tweet" /></td>';
    $output .= '</tr></table></form>';

    
    #Get latest tweets
    $myTweets = $twClient->get('/statuses/user_timeline.json', array('screen_name' => $userInfo->screen_name, 'count' => 5));
   
    //Display the latest tweets
    $output .= '<div class="tweet-div">';
    $output .= '<div class="tweet_list"><strong>Latest Tweets : </strong>';
    $output .= '<ul>';
    foreach($myTweets  as $tweet){
        $output .= '<li>'.$tweet->text.'-<i>'.$tweet->created_at.'</i></li>';
    }
    $output .= '</div>';
    $output .= '</ul></div>';
    $output .= '</div>';
    
    
}
else{
	$TwitterLoginUrl =$twClient->getAuthorizationUrl();
	$output = '<div class="login-div">';
	$output .= '<h4>Login with Twitter</h4>';
	$output .= '<a href="'.$TwitterLoginUrl.'"><img src="EpiTwitter/twitter_login.png" width="280" height="45" border="0" /></a>';
	$output .= '</div>';
}
echo $output;
?>
