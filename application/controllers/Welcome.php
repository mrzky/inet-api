<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper(['jwt', 'authorization']);
        
    }

    public function hello_get()
    {
        $tokenData = 'Hello World!';
        
        $token = AUTHORIZATION::generateToken($tokenData);

        $status = parent::HTTP_OK;

        $response = ['status' => $status, 'token' => $token];

        $this->response($response, $status);

    }

}

/* End of file Api.php */
