<?php

//~ error_reporting(E_ALL);
//~ ini_set('display_errors', 1);

class User {
    private $dbHost     = "localhost";
    private $dbUsername = "INFOConnU";
    private $dbPassword = "INFOConnpwd";
    private $dbName     = "InfoCareConnectApp";
    private $userTbl    = 'component_users';
    
    function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
                $checkTable = "SELECT * FROM information_schema.tables WHERE table_schema = 'InfoCareConnectApp' AND table_name = 'component_users' LIMIT 1";
				$table = $this->db->query($checkTable);
				 if($table->num_rows == 0){
                //Create table component_users if not exist 
					$query = "CREATE TABLE `component_users` (`id` int(11) NOT NULL AUTO_INCREMENT,`oauth_provider` enum('','facebook','google','twitter','linkedin') COLLATE utf8_unicode_ci NOT NULL,`oauth_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,`email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,`locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL,`picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,`username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,`link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,`created` datetime NOT NULL,`modified` datetime NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
					$tableCreated = $this->db->query($query);
				}
            }
        }
    }
    
    function checkUser($userData = array()){
        if(!empty($userData)){
          $prevQuery = "SELECT * FROM ".$this->userTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
          $prevResult = $this->db->query($prevQuery);
            if($prevResult->num_rows > 0){
                //Update user data if already exists
                $query = "UPDATE ".$this->userTbl." SET first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', username = '".$userData['username']."', link = '".$userData['link']."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
                $update = $this->db->query($query);
                //Get user data from the database
				$result = $this->db->query($prevQuery);
				$userData = $result->fetch_assoc();				
            }else{
                //Insert user data
                 $query = "INSERT INTO ".$this->userTbl." SET oauth_provider = '".$userData['oauth_provider']."', oauth_uid = '".$userData['oauth_uid']."', first_name = '".$userData['first_name']."', last_name = '".$userData['last_name']."', email = '".$userData['email']."', gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', username = '".$userData['username']."', link = '".$userData['link']."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
                $insert = $this->db->query($query);
            }
           return $userData; 
        
        }        
    }
}
?>
