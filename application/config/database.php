<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

require_once BASEPATH . 'dotenv/autoloader.php';
$dotenv = new Dotenv\Dotenv(FCPATH);
$dotenv->load();

$db['default'] = array(
	'dsn'	=> '',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
$db['default']['hostname'] = getenv('DB_HOST');
$db['default']['username'] = getenv('DB_USERNAME');
$db['default']['password'] = getenv('DB_PASSWORD');
$db['default']['database'] = getenv('DB_DATABASE');
$db['default']['dbdriver'] = getenv('DB_CONNECTION');
