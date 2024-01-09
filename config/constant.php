<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();


	date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)

	define("NOW", 			date("Y-m-d H:i:s"));
	define("TODAY", 		date("d-m-Y"));

	
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

		define('DBHOST',				'localhost');
		define('DBUSER',				'medicy');
		define('DBPASS',				'VnmbQ1;89@%?');
		define('DBNAME',				'medicy_app');

	endif;

	//URLS Details 
	$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

	
	define('ROOT_DIR', 			$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR);
	define('SUP_ADM_DIR', 		$_SERVER['DOCUMENT_ROOT'].LOCAL_DIR.'admin/');
	
	define('ROOT_COMPONENT', 	ROOT_DIR.'components/');
	define('SUP_ROOT_COMPONENT', SUP_ADM_DIR.'components/');
	// define('PORTAL_COMPONENT', 	ADM_DIR.'components/');
	// define('ADM_VNDR_CHRT', 	ADM_DIR.'vendor/chart.js/');
	
	define('CLASS_DIR', 		ROOT_DIR.'classes/');
	define('ASST_DIR', 			ROOT_DIR.'uploads/contents/');
	define('PROD_IMG_DIR', 		ROOT_DIR.'images/product-image/');
	define('ADM_IMG_DIR', 		ROOT_DIR.'images/admin-images/');
	define('EMP_IMG_DIR', 		ROOT_DIR.'images/employee-images/');
	
	
	define('PAGE',				$_SERVER['PHP_SELF']);
	define('URL', 				$protocol.$_SERVER['HTTP_HOST'].LOCAL_DIR);
	define('CURRENT_PAGE',		$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	define('CURRENT_URL',		$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	define('ADM_URL',  			URL.'admin/');


	
	//company
	define('SITE_NAME', 						'Medicy');						//company full name
	
	
	define('SITE_ADMIN_NAME',  					"");
	define('SITE_ADMIN_EMAIL', 					"");
	define('SITE_ADMIN_EMAIL_PASS', 				"");
	
	define('SITE_BILLING_EMAIL', 				"");
	define('SITE_BILLING_NAME',  				"");
	define('SITE_EMAIL', 						"admin@fastlinky.com");
	define('SITE_EMAIL_P', 						"=CnBV7Zu)YV}");
	
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
	define("LOGO_ALT",							SITE_NAME);							//alternate text for the logo
	
	define("FOOTER_LOGO",						URL.'/images/logo/footer-logo.png');		//location of the logo
	define("FL_WIDTH",							'180');										//width of the logo
	define("FL_HEIGHT",							'55');										//height of the logo 

	define("LOGO_ADMIN_PATH",					'images/admin/icon/admin-logo.png');		//location of the logo
	define("LOGO_ADMIN_WIDTH",					'200');										//width of the logo
	define("LOGO_ADMIN_HEIGHT",					'55');										//height of the logo 

	define("ADMIN_IMG_PATH",					URL.'/images/admin/user/');					//location of the admin users avatar
	define("USER_IMG_PATH",						URL.'/images/user/');						//location of users avatar
	define("DFAULT_AVATAR_PATH",				URL.'/images/user/default-user-icon.png');	//location of default users avatarassets


	define("PROD_IMG", 							ROOT_DIR.'images/product-image/');
	

	const ASSETS_PATH						=	URL.'assets/';
	const CSS_PATH							=	URL.'assets/css/';
	const JS_PATH							=	URL.'assets/js/';
	const IMG_PATH							=	URL.'assets/img/';
	const PLUGIN_PATH						=	URL.'assets/plugins/';
	const SITE_IMG_PATH						=	IMG_PATH.'site-imgs/';

	const LABTEST_IMG_PATH					= 	URL.'img/lab-tests/';
	const PROD_IMG_PATH						=	URL.'images/product-image/';
	const ADM_IMG_PATH						=	URL.'images/admin-images/';
	const SUP_ADM_IMG_PATH					=	URL.'admin/images/admin-images/';
	const EMPLOYEE_IMG_PATH					=	URL.'images/employee-images/';
	const EMP_PATH							=	URL.'employee/';
	const EMP_ASSETS						=	URL.'employee/assets/';
	const EMP_CSS							=	URL.'employee/assets/css/';


	


	

	
	define('CURRENCY',							'₹');
	define('CURRENCY_CODE',						'INR');
	define('START_YEAR',						'2023');
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