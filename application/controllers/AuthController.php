<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	public function index()
	{
        echo 'Auth Controller';
	}

    public function token()
    {
        $jwt = new JWT();

        $JwtSecretKey = "Mysecretwordshere";
        $data = array(
            'userId' => 145,
            'email' => 'admin@yahoo.com',
            'userType' => 'admin'
        );

        $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
        echo $token;
    }

    public function decode_token()
    {
        $token = $this->uri->segment(3);
        $jwt = new JWT();

        $JwtSecretKey = "Mysecretwordshere";
        $decoded_token = $jwt->decode($token, $JwtSecretKey, 'HS256');
        
        // echo '<pre>';
        // print_r($decoded_token);
        
        $token1 = $jwt->jsonEncode($decoded_token);
        echo $token1;
    }
}
