<?php
/**
 * Name:    Ion Auth
 * Author:  Ben Edmunds
 *           ben.edmunds@gmail.com
 *           @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5.6 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$config['database_group_name'] = '';
$config['tables']['users']           = 'MASTER_USER';
$config['tables']['groups']          = 'MASTER_GROUPS';
$config['tables']['users_groups']    = 'MASTER_USER_GROUP';
$config['tables']['groups_menus']    = 'MASTER_GROUP_MENU';
$config['tables']['login_attempts']  = 'TRX_LOGIN_ATTEMPTS';
$config['tables']['menus']  		 = 'MASTER_DYN_MENU';
$config['join']['users']  = 'USER_ID';
$config['join']['groups'] = 'GROUP_ID';
$config['join']['menus']  = 'ID_MENU';
$config['hash_method']				= 'bcrypt';
$config['bcrypt_default_cost']		= 10;
$config['bcrypt_admin_cost']		= 12;
$config['argon2_default_params']	= [
	'memory_cost'	=> 1 << 12,	// 4MB
	'time_cost'		=> 2,
	'threads'		=> 2
];
$config['argon2_admin_params']		= [
	'memory_cost'	=> 1 << 14,	// 16MB
	'time_cost'		=> 4,
	'threads'		=> 2
];
$config['site_title']                 = "Example.com";
$config['admin_email']                = "admin@example.com";
$config['default_group']              = 'Administrator';
$config['admin_group']                = 'superadmin';
$config['identity']                   = 'USERNAME';
$config['min_password_length']        = 8;
$config['max_password_length']        = 20;
$config['email_activation']           = FALSE;
$config['manual_activation']          = FALSE;
$config['remember_users']             = TRUE;
$config['user_expire']                = 3600;
$config['user_extend_on_login']       = FALSE;
$config['track_login_attempts']       = TRUE;
$config['track_login_ip_address']     = TRUE;
$config['maximum_login_attempts']     = 3;
$config['lockout_time']               = 300;
$config['forgot_password_expiration'] = 1800;
$config['recheck_timer']              = 0;
$config['remember_cookie_name'] = 'remember_code';
$config['use_ci_email'] = FALSE;
$config['email_config'] = [
	'mailtype' => 'html',
];
$config['email_templates'] = 'auth/email/';
$config['email_activate'] = 'activate.tpl.php';
$config['email_forgot_password'] = 'forgot_password.tpl.php';
$config['delimiters_source']       = 'config';
$config['message_start_delimiter'] = '<p>';
$config['message_end_delimiter']   = '</p>';
$config['error_start_delimiter']   = '<p>';
$config['error_end_delimiter']     = '</p>';