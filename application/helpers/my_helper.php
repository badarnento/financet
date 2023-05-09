<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('list_month')){

	function list_month($lang="id"){

		$monthArr = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		if($lang == "eng"){
			$monthArr = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		}

		return $monthArr;

	}
}

if ( ! function_exists('log_to_file')){

	function log_to_file($data){
		file_put_contents("Log_".date('Ymdhis').".txt", $data);
		return true;
	}
}

if ( ! function_exists('log_query')){

	function log_query(){
		$CI = get_instance();
		file_put_contents("Log_Query_".date('Ymdhis').".txt", $CI->db->last_query());
		return true;
	}
}

if ( ! function_exists('echo_pre')){

	function echo_pre($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
}

if ( ! function_exists('encrypt_string')){

	function encrypt_string($string, $for_uri=false) {
		$CI = get_instance();
		$encryption_key = $CI->config->item('encryption_key');
		$ciphering      = $CI->config->item('ciphering');
		$encryption_iv  = $CI->config->item('encryption_iv');
		
		$ivlen = openssl_cipher_iv_length($ciphering);
		$iv    = openssl_random_pseudo_bytes($ivlen);
		  
		$encryption = openssl_encrypt($string, $ciphering, $encryption_key, 0, $encryption_iv);

		if($for_uri){
			$encryption = base64url_encode($encryption);
		}

		return $encryption;
	}
}

if ( ! function_exists('decrypt_string')){
	 
	function decrypt_string($encrypted, $for_uri=false) {

		$CI = get_instance();
		$encryption_key = $CI->config->item('encryption_key');
		$ciphering      = $CI->config->item('ciphering');
		$encryption_iv  = $CI->config->item('encryption_iv');

		if($for_uri){
			$encrypted = base64url_decode($encrypted);
		}

		$ivlen = openssl_cipher_iv_length($ciphering);
		$iv    = openssl_random_pseudo_bytes($ivlen);
		  
		return openssl_decrypt($encrypted, $ciphering, $encryption_key, 0, $encryption_iv);
	}

}

if ( ! function_exists('base64url_encode')){
	 
	function base64url_encode($data) {
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

}

if ( ! function_exists('base64url_decode')){
	 
	function base64url_decode($data) {
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}

}

if ( ! function_exists('randomPassword')){
	 
	function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

}


if ( ! function_exists('dateFormat')){
	function dateFormat($dateTime, $format = "", $ts=true, $lang="eng"){

		if($ts == true){
			$timestamp  = $dateTime;
		}
		else{
			$timestamp  = strtotime($dateTime);
		}

		$date       = date("d", $timestamp);
		$dateTh     = date("jS", $timestamp);
		$time       = date("H:i", $timestamp);
		$day        = date("l", $timestamp);
		$dayShort   = date("D", $timestamp);
		$month      = date("F", $timestamp);
		$monthInt   = date("m", $timestamp);
		$shortMonth = date("M", $timestamp);
		$year       = date("Y", $timestamp);
		$last       = date("t", $timestamp);

		if($lang == "id"){
			$dayArr   = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
			$day      = $dayArr[date("w", $timestamp)];

			$monthArr = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
			$month    = $monthArr[date("n", $timestamp)];

			$shortMonthArr = array("", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
			$shortMonth    = $shortMonthArr[date("n", $timestamp)];
		}

		switch ($format) {
			case '1':
				$dateString = $date.", ".$month." ".$year;
			break;
			case '2':
				$dateString = $day.", ".$month." ".$year;
			break;
			case '3':
				$dateString = $date."-".$month."-".$year;
			break;
			case '4':
				$dateString = $date."/".$monthInt."/".$year;
			break;
			case '5':
				$dateString = $date."-".$monthInt."-".$year;
			break;
			case 'shortmonth':
				$dateString = $date." ".$shortMonth." ".$year;
			break;
			case 'datetime':
				$dateString = $date."/".$monthInt."/".$year." ".$time;
			break;
			case 'date':
				$dateString = $date."/".$monthInt."/".$year;
			break;
			case 'monthonly':
				$dateString = $month;
			break;
			case 'with_day':
				$dateString = $dayShort .", ". $dateTh ." ". $month ." ". $year ." / ". $time;
			break;
			case 'genesa':
				$dateString = $dateTh ." ". $month ." ". $year ." / ". $time;
			break;
			case 'fintool':
				$dateString = $date ." ". $month ." ". $year .", ". $time;
			break;
			case 'bupot':
				$dateString = $last ." ". $shortMonth ." ". $year;
			break;
			case 'month_years':
				$dateString = $month ." ". $year;
			break;
			case 'date_month_year':
				$dateString = $date ." ". $month ." ". $year;
			break;
			default:
				$dateString = $date." ".$monthInt." ".$year;
			break;
		}

		return $dateString;
	}
}

if ( ! function_exists('date_db')){
	
	function date_db($date){
    
	    if (strpos($date, '/') !== false) {
	        $date = explode("/", $date);
	        $date = $date[2]."-".$date[1]."-".$date[0];
	    }
		
		return date("Y-m-d", strtotime($date));
	}
}

if ( ! function_exists('date_unique')){
	
	function date_unique(){		
		return date("Ymdhis");
	}
}

if ( ! function_exists('query_datatable')){
	
	function query_datatable($sql){

		$CI = get_instance();

		$start   = ($CI->input->post('start')) ? $CI->input->post('start') : 0 ;
		$length  = ($CI->input->post('length')) ? $CI->input->post('length') : 10 ;
		$orderBy = $CI->input->post('sortorder');

		$orderby = ($orderBy == "") ? " order by id desc " : " order by ".str_replace("sort_order_", "", $orderBy);

		$query = $sql." limit ".$start.",".$length;

		return $query;
	}
}

if ( ! function_exists('query_datatable_nolimit')){
	
	function query_datatable_nolimit($sql){

		$CI = get_instance();

		$start   = ($CI->input->post('start')) ? $CI->input->post('start') : 0 ;
		$length  = ($CI->input->post('length')) ? $CI->input->post('length') : 10 ;
		$orderBy = $CI->input->post('sortorder');

		$orderby = ($orderBy == "") ? " order by id desc " : " order by ".str_replace("sort_order_", "", $orderBy);

		$query = $sql;

		return $query;
	}
}

if ( ! function_exists('query_datatable_search')){
	
	function query_datatable_search($keywords, $data){

		$CI     = get_instance();
		$search = strtolower($keywords);
		$string = "";
		if(is_array($data)){
			for ($i=0; $i < count($data); $i++) {

				if($i==0){
					$string .= " AND ( ";
					$string .= " LOWER(" .$data[$i] . ") like '%" . $CI->db->escape_like_str($search)."%' ESCAPE '!'";
				}
				else{
					$string .= " or ". $data[$i] . " like '%" . $CI->db->escape_like_str($search)."%' ESCAPE '!'";
				}
			}
			$string .= ")";

		}else{
			$string = " AND LOWER(". $data . ") like '%" . $CI->db->escape_like_str($search)."%' ESCAPE '!'";
		}

		return $string;

	}
}


if ( ! function_exists('get_user_data')){
	
	function get_user_data($id, $field="DISPLAY_NAME"){

		$CI = get_instance();
		$CI->load->model('user_mdl', 'user');

		$data = $CI->user->get_user_by_id($id);

		return $data[$field];

	}
}

if ( ! function_exists('get_group_data')){
	
	function get_group_data($id){

		$CI = get_instance();

		$data = $CI->crud->read_by_id("MASTER_GROUPS", $id);

		return $data;

	}
}

if ( ! function_exists('get_user_group_data')){
	
	function get_user_group_data(){

		$CI = get_instance();
		$groups = $CI->session->userdata('group_id');

		$group_name = array();
		foreach ($groups as $key => $value) {
			$grpName = get_group_data($value);
			$group_name[] = $grpName['NAME'];
		}

		return $group_name;

	}
}

if ( ! function_exists('check_is_bod')){
	
	function check_is_bod(){

		$CI = get_instance();

		$email = $CI->session->userdata('email');
		$group = $CI->session->userdata('group_id');

		foreach ($group as $key => $value) {
			$get_group = get_group_data($value);
			$group_name[] = $get_group['NAME'];
		}

		$directorat = get_all_directorat();

		if(in_array("BOD", $group_name) && $email != "haryati_lawidjaja@linkaja.id"){
			$get_employe = $CI->crud->read_by_param("MASTER_EMPLOYEE", array("ALAMAT_EMAIL" => $email));
			$id_dir_code = $get_employe['ID_DIR_CODE'];

			$dirs = array();

			foreach ($directorat as $key => $value) {
				if($value['ID_DIR_CODE'] == $id_dir_code):
					$dirs[] = array( "ID_DIR_CODE" => $value['ID_DIR_CODE'],
								"DIRECTORAT_NAME" => $value['DIRECTORAT_NAME']
							);
				endif;
			}

			unset($directorat);
			$directorat = $dirs;

		}

		if($email == "ikhsan_ramdan@linkaja.id"){
			$directorat = get_all_directorat();
		}

		return $directorat;

	}
}

if ( ! function_exists('get_shorted_user')){
	
	function get_shorted_user($id){

		$user = strtolower(get_user_data($id));

		if (stripos($user, ' ') !== false){
			$exp_user = explode(" ", $user);
			$user     = substr($exp_user[0], 0, 1).substr($exp_user[1], 0, 1);
		}
		else{
			$user = substr($user, 0, 2);
		}

		return strtoupper($user);
		
	}
}


if ( ! function_exists('horizontal_loop_excel')){
	
	function horizontal_loop_excel($start, $lenght){
		$letter = $start;
		$step   = ($lenght) ? $lenght : 1000;
		for($i = 0; $i < $lenght; $i++) {
			$letters[] = $letter;
		    $letter++;
		}
		return $letters;
	}
}

if ( ! function_exists('generateRandomString')){
	
	function generateRandomString($length = 10, $type="") {
		if($type == "string"){
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}elseif($type == "number"){
			$characters = '0123456789';
		}else{
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}

if ( ! function_exists('trim_string')){

	function trim_string($string, $type=""){

		$replace1 = str_replace(",", "", $string);
		$replace2 = str_replace(".", "", $replace1);
		$replace3 = preg_replace('/\s+/', '', $replace2);
		
		$type     = ($type != "") ? strtolower($type) : "";
		
		switch ($type) {
			case 'comma':
				$replace = str_replace(",", "", $string);
			break;
			case 'point':
				$replace = str_replace(".", "", $string);
			break;
			case 'space':
				$replace = preg_replace('/\s+/', '', $string);
			break;
			default:
				$replace = $replace3;
			break;
		}

		return $replace;

	}
}

if ( ! function_exists('update_history')){

	function update_history($table, $params="", $action="UPDATE"){

		$CI    = get_instance();
		$query = $CI->crud->update_history($table, $params);

	}
}

if ( ! function_exists('penyebut')){
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 100);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
}

if ( ! function_exists('terbilang')){
	function terbilang($nilai) {
		$nilai = trim_string($nilai);
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}
}


if ( ! function_exists('delete_file')){
	
	function delete_file($path){

        $result['status']   = false;
        $result['messages'] = "";
        
        if (!file_exists(FCPATH.$path)){
            $result['messages'] = "File does not exist";
        }
        else{
            if(unlink($path)){
                $result['status']   = true;
            }
            else{
                $result['messages'] = "Failed to delete file";
            }
        }

		return $result;
    }
}


if ( ! function_exists('sendemail')){
	
	function sendemail($recipient, $subject, $body, $cc="", $attachment="", $store_log=true, $id_log=0){

		$CI = get_instance();

		if($recipient == "astri_darmadi@linkaja.id"){
			$cc .= ($cc == "") ? "budget@linkaja.id": ", budget@linkaja.id";
		}
		if($recipient == "shinta_sutoyo@linkaja.id"){
			$recipient = "general_service@linkaja.id";
		}
		if(ENVIRONMENT != "production"){
			$recipient = "badaruddin_nento@linkaja.id";
			$cc = "";
		}

		$config['useragent'] = "CodeIgniter";
		$config['protocol']  = getenv('EMAIL_PROTOCOL');
		$config['smtp_host'] = getenv('EMAIL_HOST');
		$config['smtp_port'] = getenv('EMAIL_PORT');
		$config['mailtype']  = 'html';
		$config['charset']   = 'utf-8';
		$config['newline']   = "\r\n";
		$config['wordwrap']  = TRUE;
		$sender_email        = getenv('EMAIL_SENDER');

		if (strpos($_SERVER['HTTP_HOST'], 'linkaja') !== false) {
			$CI->load->library('email');
			$config['smtp_user'] = "";
			$config['smtp_pass'] = "";
			$CI->email->initialize($config);
		}else{
			$config['smtp_user'] = getenv('EMAIL_USER');
			$config['smtp_pass'] = getenv('EMAIL_PASS');
			$CI->load->library('email', $config);
			$CI->email->set_mailtype('html');
			$CI->email->set_newline("\r\n");
		}

		$send_email = (strpos($_SERVER['HTTP_HOST'], 'local') !== false) ? false : true;

		$CI->email->clear(true);
        $CI->email->from($sender_email, "Finance Tools");
		$CI->email->to($recipient);
		$CI->email->subject($subject);
        $CI->email->message($body);

		$dataLog = array(
						"EMAIL_FROM"       => "Finance Tools",
						"EMAIL_TO"         => $recipient,
						"EMAIL_SUBJECT"    => $subject,
						"EMAIL_BODY"       => $body
					);
        $ccLogAdded = "";
		if($cc != ""){
			$CI->email->cc($cc);
			$ccLogAdded = " (CC: " . $cc . ")";
			$dataLog['EMAIL_CC'] = $cc;
		}
		if($attachment != ""){
			$file_size  = (int) (filesize($attachment) / 1000);
			if($file_size < 7000){
				$CI->email->attach($attachment);
				$dataLog['EMAIL_ATTACHMENT'] = $attachment;
			}
		}

		if ($send_email) {
			$log_desc = $recipient.$ccLogAdded . " (Subject: " . $subject . ")";
			if($CI->email->send()){

				$dataLog['STATUS'] = "Sent";		
				$log_info = "Email sent to " . $log_desc;

				if($store_log){
					$insert_log = $CI->crud->create("TRX_EMAIL_LOG", $dataLog);
					$log_info .= ($insert_log) ? " ID log: " . $insert_log : "";
				}

				if($id_log > 0){
					$CI->crud->update("TRX_EMAIL_LOG", $dataLog, $id_log, false);
					$log_info .= " ID log: " . $id_log;
				}

				log_message('info', $log_info);

        		return true;
			}else{
				$log_info = "Failed to sending email " . $log_desc;

				$dataLog['STATUS'] = "Failed";
				$dataLog['IS_SENT'] = 0;
				$dataLog['ERROR_MESSAGES'] = $CI->email->print_debugger();
				$dataLog['ERROR_DATE'] = date("Y-m-d H:i:s", time());

				if($store_log){
					$insert_log = $CI->crud->create("TRX_EMAIL_LOG", $dataLog);
					$log_info .= ($insert_log) ? " ID log: " . $insert_log : "";
				}

				if($id_log > 0){
					$dataLogUpd['STATUS']         = "Failed";
					$dataLogUpd['IS_SENT']        = 0;
					$dataLogUpd['ERROR_MESSAGES'] = $CI->email->print_debugger();
					$dataLogUpd['ERROR_DATE']     = date("Y-m-d H:i:s", time());
					$dataLogUpd['UPDATED_BY']     = 'System';
					$CI->crud->update("TRX_EMAIL_LOG", $dataLogUpd, $id_log, false);
					$log_info .= " ID log: " . $id_log;
				}

				log_message('info', $log_info);

        		return false;
			}
        }
        else{
        	return true;
        }
    }
}

if ( ! function_exists('subtr_npwp')){

	function subtr_npwp($npwp, $lenght=15){
	    $first  = substr($npwp, 0, 1);
	    
	    if($lenght == 15){
	        $subtr_npwp = substr($npwp, 0, 2).".".substr($npwp, 2, 3).".".substr($npwp, 5, 3).".".substr($npwp, 8, 1)."-".substr($npwp, 9, 3).".".substr($npwp, 12, 3);
	    }
	    elseif($lenght == 16){
	    	if($first != 0){
	        	$subtr_npwp = substr($npwp, 0, 2).".".substr($npwp, 2, 3).".".substr($npwp, 5, 3).".".substr($npwp, 8, 1)."-".substr($npwp, 9, 3).".".substr($npwp, 12, 3);
	    	}
	    	else{
				$npwp = substr($npwp, 1);
				$subtr_npwp = substr($npwp, 0, 2).".".substr($npwp, 2, 3).".".substr($npwp, 5, 3).".".substr($npwp, 8, 1)."-".substr($npwp, 9, 3).".".substr($npwp, 12, 3);
	    	}
	    }
	    else{
	        if($first != "0"){
	            $subtr_npwp = "0".substr($npwp, 0, 1).".".substr($npwp, 1, 3).".".substr($npwp, 4, 3).".".substr($npwp, 7, 1)."-".substr($npwp, 8, 3).".".substr($npwp, 11, 3);
	        }
	        else{
	            $subtr_npwp = substr($npwp, 0, 2).".".substr($npwp, 2, 3).".".substr($npwp, 5, 3).".".substr($npwp, 8, 1)."-".substr($npwp, 9, 3).".".substr($npwp, 12, 2)."0";
	        }
	    }
		return $subtr_npwp;
	}
}

if ( ! function_exists('format_npwp')){

	function format_npwp($npwp, $format=true){
		$exponent = false;
		$null     = false;
		$npwp     = simtax_trim($npwp, "space");
	    
	    if(strpos($npwp, 'E+') !== false){
	        $exponent = true;
	    }	    
	    if($npwp == "-" || $npwp == "0" || $npwp == "000000000000000" || $npwp == "00.000.000.0-000.000"){
	        $null = true;
	    }
	    
	    if($exponent){
    		$result = $npwp;
	        
	    }
	    else{
	        
	        $lenght = strlen($npwp);
	        
	    	if($lenght >= 14 && $lenght <= 16){
	    	    $result = subtr_npwp($npwp, $lenght);
	    	}
	    	else{
	    		if($null){
	    			$result = "00.000.000.0-000.000";
	    		}else{
	    			$result = $npwp;
	    		}
	    	}
	    }
	    
	    if($format == false){
			$fix  = preg_replace("/[^0-9]/", "", $result);
			$npwp = $fix;
	    }
	    else{
	        $npwp = $result;
	    }
	    
		return $npwp;

	}
}

if ( ! function_exists('simtax_trim')){

	function simtax_trim($string, $type=""){

		$replace1 = str_replace(",", "", $string);
		$replace2 = str_replace(".", "", $replace1);
		$replace3 = preg_replace('/\s+/', '', $replace2);
		
		$type     = ($type != "") ? strtolower($type) : "";
		
		switch ($type) {
			case 'comma':
				$replace = str_replace(",", "", $string);
			break;
			case 'point':
				$replace = str_replace(".", "", $string);
			break;
			case 'space':
				$replace = preg_replace('/\s+/', '', $string);
			break;
			default:
				$replace = $replace3;
			break;
		}

		return $replace;

	}
}