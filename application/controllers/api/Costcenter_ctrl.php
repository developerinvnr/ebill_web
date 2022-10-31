<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
header('Content-Type: application/json; charset=utf-8');

class Costcenter_ctrl extends REST_Controller {

	function __construct() {
    parent::__construct();
		$this->load->model('Costcenter_model');
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


	function index_get($cid=null){
		if(!is_null($cid)){
			$result = $this->Costcenter_model->costcenter_list($cid);
			if($result != null && count($result)>0){
				$this->response(array('costcenter_detail'=>$result[0],'msg'=>'cost-center detail','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
		else {
			$result = $this->Costcenter_model->costcenter_list();
			if($result != null && count($result)>0){
				$this->response(array('costcenter_list'=>$result,'msg'=>'Cost-center list','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
	}

	function index_post(){
				$this->form_validation->set_rules('name', 'Cost-center name', 'required|trim');
				$this->form_validation->set_rules('company_id', 'Compnay', 'required|trim');

				if ($this->form_validation->run()){

					$data['name'] = $this->post('name');
					$data['company_id'] = $this->post('company_id');
					$data['created_by'] = $this->user_data->user_id;
					$data['created_at'] = date('Y-m-d');

					$result = $this->Costcenter_model->create_costcenter($data);
					if($result){
						$this->response(array('msg'=>'cost-center created.','status'=>'200'),201);
					} else{
						$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
					}
				} else {
					$data = validation_errors();
					$this->response(array('errors'=>$data,'status'=>500),500);
				}
	}

	function index_put($cid){
		$this->form_validation->set_data($this->put());
		$this->form_validation->set_rules('name', 'Cost-center name', 'required|trim');
		$this->form_validation->set_rules('company_id', 'Company', 'required|trim');

		if ($this->form_validation->run()){
			$data['name'] = $this->put('name');
			$data['company_id'] = $this->put('company_id');
			$data['created_by'] = $this->user_data->user_id;

			$result = $this->Costcenter_model->update_costcenter($data,$cid);
			if($result){
				$this->response(array('msg'=>'Costcenter updated.','status'=>'200'),202);
			} else{
				$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
			}
		} else {
			$data = validation_errors();
			$this->response(array('errors'=>$data,'status'=>500),500);
		}
	}

	function index_delete($cid){
		$result = $this->Costcenter_model->costcenter_delete($cid);
		if($result != null){
			$this->response(array('msg'=>'Costcenter deleted.','status'=>200),200);
		} else {
			$this->response(array('msg'=>'Something went wrong.','status'=>500),500);
		}
	}
}
