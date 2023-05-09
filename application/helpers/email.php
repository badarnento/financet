<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('sendemail')){
	
	function sendemail($recipient, $subject, $body, $cc="", $attachment=""){

		$CI = get_instance();

		$config = array(
				"protocol"    => "smtp",
				"smtp_host"   => "mail.financetools.id",
				"smtp_user"   => "support@financetools.id",
				"smtp_pass"   => "H5zs@Y^MtR?}sGE7",
				"smtp_port"   => 465,
				"smtp_crypto" => "ssl",
				"charset"     => "UTF-8",
				"wordwrap"    => TRUE
          );

        $CI->load->library('email', $config);
		$CI->email->set_mailtype('html');
		$CI->email->set_newline("\r\n");
        $CI->email->from('support@financetools.id', "Finance Tools");
		$CI->email->to($recipient);
		$CI->email->subject($subject);
        $CI->email->message($body);

		if($cc != ""){
			$CI->email->cc($cc);
		}
		if($attachment != ""){
			$CI->email->attach($attachment);
		}
        if(base_url() == "https://financetools.id/apps/dev/"){
	        if ($CI->email->send()) {
	        	return true;
	        }
	        else{
	            return $CI->email->print_debugger();
	        }
        }
        else{
        	return true;
        }
    }
}