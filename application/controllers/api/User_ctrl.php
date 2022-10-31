<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
header('Content-Type: application/json; charset=utf-8');

class User_ctrl extends REST_Controller {

	function __construct() {
    parent::__construct();
		$this->load->model('User_model');
		$this->jwt = new JWT();

		$this->header = ($this->input->request_headers());
		if(isset($this->header['token']) &&  $this->header['token'] != null){
			$this->user_data = $this->my_lib->is_valid($this->header['token']);
			if(is_null($this->user_data)){
				http_response_code(401);
				echo json_encode(array('msg'=>'Invalid token.','status'=>'401'));
				die;
			}
		}
		else { //token not set
			http_response_code(400);
			echo json_encode(array('errors'=>'Token not set.','status'=>400));
			die;
		}
  }


	function index_get($uid=null){
		if(!is_null($uid)){
			$result = $this->User_model->user_list($uid);
			if($result != null && count($result)>0){
				$this->response(array('user_detail'=>$result[0],'msg'=>'user detail','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
		else {
			$result = $this->User_model->user_list();
			if($result != null && count($result)>0){
				$this->response(array('user_list'=>$result,'msg'=>'users list','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
	}

	function index_post(){
				$this->form_validation->set_rules('fname', 'First name', 'required|trim');
				$this->form_validation->set_rules('lname', 'Last name', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
				$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|trim');
				$this->form_validation->set_rules('sex', 'Gender', 'required|trim');
				$this->form_validation->set_rules('utype', 'User type', 'required|trim');

				if ($this->form_validation->run()){

					$data['fname'] = $this->post('fname');
					$data['lname'] = $this->post('lname');
					$data['email'] = $this->post('email');
					$data['contact_no'] = $this->post('contact');
					$data['password'] = sha1($this->post('password'));
					$data['sex'] = $this->post('sex');
					$data['utype'] = $this->post('utype');
					$data['created_by'] = $this->user_data->user_id;
					$data['created_at'] = date('Y-m-d');

					$result = $this->User_model->create_user($data);
					if($result){
						$this->response(array('msg'=>'user created.','status'=>'200'),201);
					} else{
						$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
					}
				} else {
					$data = validation_errors();
					$this->response(array('errors'=>$data,'status'=>500),500);
				}
	}

	function index_put($uid){
		$this->form_validation->set_data($this->put());
		$this->form_validation->set_rules('fname', 'First name', 'required|trim');
		$this->form_validation->set_rules('lname', 'Last name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
		$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
		$this->form_validation->set_rules('sex', 'Gender', 'required|trim');
		$this->form_validation->set_rules('utype', 'User type', 'required|trim');

		if ($this->form_validation->run()){
			$data['fname'] = $this->put('fname');
			$data['lname'] = $this->put('lname');
			$data['email'] = $this->put('email');
			$data['contact_no'] = $this->put('contact');
			$data['sex'] = $this->put('sex');
			$data['utype'] = $this->put('utype');
			$data['created_by'] = $this->user_data->user_id;

			$result = $this->User_model->update_user($data,$uid);
			if($result){
				$this->response(array('msg'=>'user updated.','status'=>'200'),202);
			} else{
				$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
			}
		} else {
			$data = validation_errors();
			$this->response(array('errors'=>$data,'status'=>500),500);
		}
	}

	function index_delete($uid){
		$result = $this->User_model->user_delete($uid);
		if($result != null){
			$this->response(array('msg'=>'User deleted.','status'=>200),200);
		} else {
			$this->response(array('msg'=>'Something went wrong.','status'=>500),500);
		}
	}
}
