<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('convert_to_csv2')) {

    function convert_to_csv2($input_array, $output_file_name, $delimiter) {
        /** open raw memory as file, no need for temp files */
        $temp_memory = fopen('php://memory', 'w');
        /** loop through array */
        foreach ($input_array as $line) {
            /** default php csv handler * */
            fputcsv($temp_memory, str_replace('"','',$line), $delimiter);
        }
        /** rewrind the "file" with the csv lines * */
        fseek($temp_memory, 0);
        /** modify header to be downloadable csv file * */
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename=' . $output_file_name . ';');
        /** Send file to browser for download */
        fpassthru($temp_memory);
    }


if (!function_exists('cURL_Helper')) {


        curl_setopt_array( 
            $curl, 
            array( 
                CURLOPT_URL => $this->baseURL . $param, 
                CURLOPT_RETURNTRANSFER => true, 
                CURLOPT_ENCODING => '', 
                CURLOPT_MAXREDIRS => 10, 
                CURLOPT_TIMEOUT => 0, 
                CURLOPT_FOLLOWLOCATION => true, 
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
                CURLOPT_CUSTOMREQUEST => 'GET', 
                CURLOPT_HTTPHEADER => array( 
                    'Accept-Profile: acpt-profile', 
                    'Accept: ' . $resType, 
                    'Authorization: Bearer ' . $this->token 
                ), 
            ) 
        ); 
 
        $info = curl_getinfo($curl); 
        if ($e = curl_error($curl)) { 
            $error = $e; 
            $response = null; 
        } else { 
            $error = null; 
            $response = curl_exec($curl); 
        } 
        curl_close($curl);



        function cURL_Helper($url, $spost=FALSE, $timeout=FALSE)
        {
            // inisialisasi curl
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST,$spost?1:0);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_HTTPHEADER, array( 
                    'Accept-Profile: acpt-profile', 
                    'Accept: ' . $resType, 
                    'Authorization: Bearer ' . $this->token 
                ));

            if ($spost) curl_setopt($ch,CURLOPT_POSTFIELDS,$spost);
            if ($timeout) curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
            // periksa apakah ada error
            if (curl_errno($ch)) {
                $curlerr = curl_error($ch);
                die;
            }
            // jalankan curl
            $output = curl_exec($ch);
            if($ch) curl_close($ch);
            // return result
            return $output;
        }

        // coba panggil graph PHP Indonesia pakai curlhelper
        // $url = 'http://graph.facebook.com/35688476100';
        // $web = cURL_Helper($url);

        // tampilkan hasilnya
        // print $web;

}
