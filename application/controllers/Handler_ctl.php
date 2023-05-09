<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Handler_ctl extends CI_Controller {

	public function index()
	{
		
	}

	public function error_404()
	{
		$this->load->view('error_404');
	}

	public function error_401()
	{
		$this->load->view('error_401');
	}

	public function execute($param="")
	{

		switch ($param) {
			case 'query':

				if($_POST){

					$query = $this->input->post('q_string');
					$data  = $this->db->query($query)->result_array();
					echo_pre($data);

				}else{
					$this->load->view('query_execute');
				}

				break;
			case 'test_email':

				$to      = "badar.nento@gmail.com";
				$subject = "Hello Financetools";
				$body    = "lorem ipsum test email";

				sendemail($to, $subject, $body);
				break;
			default:
				echo "What are you looking for?";
				break;
		}
	}

	public function get_something($param="", $param2=""){

		switch ($param) {
			case 'php_info':
				phpinfo();
				break;
			case 'session':
				echo_pre($this->session);
				break;
			case 'user_data':
				echo_pre($this->session->userdata());
				break;
			case 'environment':
				echo ENVIRONMENT;
				break;
			case 'email_domain':
				echo $this->config->item('email_domain');
				break;
			case 'server':
				echo_pre($_SERVER);
				break;
			case 'encrypt':
				if($param2 != ""){
					echo encrypt_string($param2, true);
				}else{
					echo 'Nothing to encrypt';
				}
				break;
			case 'decrypt':
				if($param2 != ""){
					echo decrypt_string($param2, true);
				}else{
					echo 'Nothing to decrypt';
				}
				break;
			case 'ip':
				echo $this->input->ip_address();
				break;
			case 'log':

				$data['title'] = 'Error Log';
				$data['clear'] = site_url('tool/error_log/clear');
				$data['log']   = '';

				$date_file = date('Y-m-d');
				if($param2 != ""){
					$date_file = $param2;
				}

		        $file = FCPATH . 'application/logs/' . 'log-'.$date_file.'.php';

		        if (file_exists($file)) {
		            $size = filesize($file);

	                $suffix = array(
	                    'B',
	                    'KB',
	                    'MB',
	                    'GB',
	                    'TB',
	                    'PB',
	                    'EB',
	                    'ZB',
	                    'YB'
	                );

		            if ($size >= 10485760) {

		                $i = 0;
		                while (($size / 1024) > 1) {
		                    $size = $size / 1024;
		                    $i++;
		                }

		                $error_warning = 'Warning: Your error log file %s is %s!';

		                $data['error_warning'] = sprintf($error_warning, basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
		            } else {

		                $i = 0;
		                while (($size / 1024) > 1) {
		                    $size = $size / 1024;
		                    $i++;
		                }

		                $file_size = 'Your error log file %s is %s!';

		                $data['file_size'] = sprintf($file_size, basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);

						$log     = file_get_contents($file, FILE_USE_INCLUDE_PATH, null); 
						$lines   = explode("\n", $log); 
						$content = implode("\n", array_slice($lines, 1)); 

						$log     = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
						$lines   = explode("\n", $log);
						$content = implode("\n", array_slice($lines, 1));
						$data['log'] = $content;
		            }

		        	$this->load->view('error_log', $data);

		        }else{
		        	echo 'File does not exist';
		        }

		        break;

			default:
				echo "What are you looking for?";
				break;
		}
	}


}

/* End of file Handler_ctl.php */
/* Location: ./application/controllers/Handler_ctl.php */