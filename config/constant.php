<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

	date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)

	define("NOW", 			date("Y-m-d H:i:s"));

	
	function is_localhost() {

		$whitelist = array( '127.0.0.1', '::1' );
		
		if ( in_array( $_SERVER['REMOTE_ADDR'], $whitelist ) ) {
			
			return true;

		}
	}

	if (is_localhost()):
		define('LOCAL_DIR',				'/medicy.in/');

		define('DBHOST',				'localhost');
		define('DBUSER',				'root');
		define('DBPASS',				'');
		define('DBNAME',				'medicy_db');

	else:
		define('LOCAL_DIR',				'/');

		define('DBHOST',				'');
		define('DBUSER',				'');
		define('DBPASS',				'');
		define('DBNAME',				'');

	endif;

	//URLS Details 
	$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

	
	define('ROOT_DIR', 			$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR);
	define('ADM_DIR', 			$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR.'admin/');
	define('CLASS_DIR', 		$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR.'classes/');
	define('ASST_DIR', 			$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR.'uploads/contents/');
	
	
	define('PAGE',				$_SERVER['PHP_SELF']);
	define('URL', 				$protocol.$_SERVER['HTTP_HOST'].LOCAL_DIR);
	define('ADM_URL',  			URL.'/admin/');
	define('IMG_PATH',  		URL."/assets/images/");


	
	//company
	define('COMPANY_FULL_NAME', 				'Fast Linky');						//company full name
	define('COMPANY_S', 						'Fast Linky');						//company short name
	define('COMPANY_L', 						'LL');								//company short name
	
	
	define('SITE_ADMIN_NAME',  					"");
	define('SITE_ADMIN_EMAIL', 					"");
	define('SITE_ADMIN_EMAIL_PASS', 				"");
	
	define('SITE_BILLING_EMAIL', 				"");
	define('SITE_BILLING_NAME',  				"");
	define('SITE_EMAIL', 						"");
	define('SITE_EMAIL_P', 						"");
	
	define('CONTACT_MAIL', 						"");
	define('INFO_MAIL', 						"");
	
	define('MARKETING_MAIL', 					"");
	define('MARKETING_MAIL_P', 					"");
	
	define('SITE_CONTACT_NO',  					"+91 874224523");
	define('SITE_BILLING_CONTACT_NO',  			"+91 874224523");

	
	//define company logo
	define("LOGO_WITH_PATH",					URL."/images/logo/logo.png");				//location of the logo
	define("LOGO_WIDTH",						'180');										//width of the logo
	define("LOGO_HEIGHT",						'50');										//height of the logo

	define("FAVCON_PATH",						URL."/images/logo/favicon.png");			//location of the favcon
	define("LOGO_ALT",							COMPANY_FULL_NAME);							//alternate text for the logo
	
	define("FOOTER_LOGO",						URL.'/images/logo/footer-logo.png');		//location of the logo
	define("FL_WIDTH",							'180');										//width of the logo
	define("FL_HEIGHT",							'55');										//height of the logo 

	define("LOGO_ADMIN_PATH",					'images/admin/icon/admin-logo.png');		//location of the logo
	define("LOGO_ADMIN_WIDTH",					'200');										//width of the logo
	define("LOGO_ADMIN_HEIGHT",					'55');										//height of the logo 

	define("ADMIN_IMG_PATH",					URL.'/images/admin/user/');					//location of the admin users avatar
	define("USER_IMG_PATH",						URL.'/images/user/');						//location of users avatar
	define("DFAULT_AVATAR_PATH",				URL.'/images/user/default-user-icon.png');	//location of default users avatar

	

	
	define('CURRENCY',							'$');
	define('CURRENCY_CODE',						'USD');
	define('START_YEAR',						'2022');
	define('HOME',								'Home');
	define('END_YEAR',  						date('Y')); 


	//session constant
	define('ADM_SESS',   						"ADMINLOGGEDIN"); 		//admin session var
	// define('STAFF_SESS',   						"EMPLOGGEDIN");					//user session var

	
	//display style constant
	define('ER', 								'Error: ');
	define('SU', 								'Success !!! ');


	// Social Media Handles
	define("FB_LINK", 							"https://www.facebook.com/leelijaweb/");
	define("INSTA_LINK", 						"#");
	define("TWITTER_LINK", 						"https://twitter.com/lee_lija");
	define("PINTEREST_LINK", 					"https://in.pinterest.com/leelijaa/");
	define("LINKDIN_LINK", 						"https://www.linkedin.com/in/leelija/");
?>