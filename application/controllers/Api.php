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
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
        $this->load->model('Api_model', 'dbObject');  
        $this->load->library('Uuid');
    }

    private function verify_request($token)
    {
        // Get all the headers
        $headers = $this->input->request_headers();

        // Extract the token
        // $token = $headers['Authorization'];

        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'error' => true, 'data' => $data, 'message' => 'Unauthorized Access!'];
                $this->response($response, $status);

                exit();
            } else {
                // return $data;
                return true;
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'error' => true, 'data' => false, 'msg' => 'Unauthorized Access! '];
            $this->response($response, $status);
        }
    }

    public function login_post()
    {
        $response = $this->dbObject->login(
            $this->input->post('username'),
            md5($this->input->post('password'))
        );
        $token = AUTHORIZATION::generateToken([
            'uid' => $response['user']->uid,
            'username' => $response['user']->username,
        ]);
        $response['token'] = $token;
        $this->response($response);
    }

    public function create_account_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->create_account(
                $this->input->post('nama'),
                $this->input->post('username'),
                md5($this->input->post('password')),
                $this->input->post('role')
              );
            $this->response($response);
        }

    }

    public function update_account_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->update_account(
                $this->input->post('uid'),
                $this->input->post('nama'),
                $this->input->post('username'),
                $this->input->post('username_baru')
              );
            $this->response($response);
        }

    }

    public function read_account_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->read_account(
                $this->input->post('uid')
              );
            $this->response($response);
        }

    }

    public function list_account_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->list_account();
            $this->response($response);
        }

    }

    public function delete_account_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->delete_account(
                $this->input->post('uid')
              );
            $this->response($response);
        }

    }

    public function create_paket_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->create_paket(
                $this->input->post('nama_paket'),
                $this->input->post('harga')
              );
            $this->response($response);
        }

    }

    public function read_paket_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->read_paket(
                $this->input->post('id')
              );
            $this->response($response);
        }

    }

    public function list_paket_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->list_paket();
            $this->response($response);
        }

    }

    public function update_paket_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->update_paket(
                $this->input->post('id'),
                $this->input->post('nama_paket'),
                $this->input->post('harga')
              );
            $this->response($response);
        }

    }

    public function delete_paket_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->delete_paket(
                $this->input->post('id')
              );
            $this->response($response);
        }

    }

    public function create_transaksi_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->create_transaksi(
                $this->input->post('kode_user'),
                $this->input->post('jumlah_unit'),
                $this->input->post('uid_kades'),
                $this->input->post('total_bayar')
              );
            $this->response($response);
        }

    }

    public function read_transaksi_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->read_transaksi(
                $this->input->post('uid')
              );
            $this->response($response);
        }

    }

    public function list_transaksi_post() {
        $data = $this->verify_request($this->input->post('token'));

        if ($data) {
            $response = $this->dbObject->list_transaksi();
            $this->response($response);
        }

    }

    public function get_my_data_post()
    {

        $token = $this->input->post('token');
        // Call the verification method and store the return value in the variable
        $data = $this->verify_request($token);

        // Send the return data as reponse
        $status = parent::HTTP_OK;

        $response = ['status' => $status, 'data' => $data, 'msg' => 'Loaded!'];

        $this->response($response, $status);
    }


}

/* End of file Api.php */
