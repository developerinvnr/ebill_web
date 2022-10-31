<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller {

	function __construct() {
        parent::__construct();
		$this->load->database();
		$this->load->model('Auth_model');
		$this->jwt = new JWT();
    }

		function index_get(){
			$this->db->select('*');
			$result = $this->db->get_where('users',array('status'=>1))->result_array();
			$this->response($result,200);
		}

	function index_post(){
		$header = ($this->input->request_headers());
		if(isset($header['token']) &&  $header['token'] != null){
			$udata = $this->my_lib->is_valid($header['token']);
			if(!is_null($udata)){
					$this->db->select('*');
					$result = $this->db->get_where('users',array('status'=>1))->result_array();
					$this->response($result,200);
			}
			else {
				$this->response(array('msg'=>'Invalid token.','status'=>'401'),401);
			}
		} else { //token not set
			$this->response(array('msg'=>'Token not set.','status'=>'400'),400);
		}
	}

    function app_version_get(){
        $this->response(array(
            'android_version'=>'1.0.0',
            'ios_version'=>'1.0.0',
            'playstore_path' => 'http://vnrseeds.co.in/hrims/index',
            'appstore_path' => 'www.apple.com',
            'status'=>'200'),200);
    }

	function login_post(){
		$data['identity'] = trim($this->post('identity'));
		$data['password'] = sha1(trim($this->post('password')));

		$result = $this->Auth_model->login($data);
		if(!is_null($result)){
			$response = array(
				'user_id' => $result[0]['uid'],
				'role' => $result[0]['type_name']
			);
			$token =$this->jwt->encode($response,'','HS256');
			$this->response(
			    array(
			        'data'=>array(
			            'uid'=>$result[0]['uid'],
			            'name' => $result[0]['fname'].''.$result[0]['lname'],
			            'role'  => $result[0]['type_name'],
			            'token'=>$token
			        ),
			        'msg'=>'login successfully.',
			        'status'=>200),
			    200);
		}
		else {
			$this->response(array('msg'=>'Login failed.','status'=>'400'),400);
		}
	}
}
