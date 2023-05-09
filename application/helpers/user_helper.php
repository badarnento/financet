<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('get_all_groups')){
	
	function get_all_groups(){
		
		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_GROUPS", "ID!=1", "NAME");

		return $data;
		
	}
}

if ( ! function_exists('get_all_menus')){
	
	function get_all_menus(){
		
		$CI   = get_instance();
		$data = $CI->crud->read("MASTER_DYN_MENU", FALSE, "SHOWORDER,PARENT_ID");

		return $data;
		
	}
}