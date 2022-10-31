<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
header('Content-Type: application/json; charset=utf-8');

class Location_ctrl extends REST_Controller {

	function __construct() {
    parent::__construct();
		$this->load->model('Location_model');
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


	function index_get($lid=null){
		if(!is_null($lid)){
			$result = $this->Location_model->location_list($lid);
			if($result != null && count($result)>0){
				$this->response(array('location_detail'=>$result[0],'msg'=>'location detail','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
		else {
			$result = $this->Location_model->location_list();
			if($result != null && count($result)>0){
				$this->response(array('location_list'=>$result,'msg'=>'Location list','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
	}

	function index_post(){
				$this->form_validation->set_rules('name', 'Location name', 'required|trim');
					$this->form_validation->set_rules('cost_center', 'Cost-center', 'required|trim');

				if ($this->form_validation->run()){

					$data['name'] = $this->post('name');
					$data['cost_center_id'] = $this->post('cost_center');
					$data['created_by'] = $this->user_data->user_id;
					$data['created_at'] = date('Y-m-d');

					$result = $this->Location_model->create_location($data);
					if($result){
						$this->response(array('msg'=>'location created.','status'=>'200'),201);
					} else{
						$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
					}
				} else {
					$data = validation_errors();
					$this->response(array('errors'=>$data,'status'=>500),500);
				}
	}

	function index_put($lid){
		$this->form_validation->set_data($this->put());
		$this->form_validation->set_rules('name', 'Location name', 'required|trim');
		$this->form_validation->set_rules('cost_center', 'Cost center', 'required|trim');

		if ($this->form_validation->run()){
			$data['name'] = $this->put('name');
			$data['cost_center_id'] = $this->put('cost_center');
			$data['created_by'] = $this->user_data->user_id;

			$result = $this->Location_model->update_location($data,$lid);
			if($result){
				$this->response(array('msg'=>'Location updated.','status'=>'200'),202);
			} else{
				$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
			}
		} else {
			$data = validation_errors();
			$this->response(array('errors'=>$data,'status'=>500),500);
		}
	}

	function index_delete($lid){
		$result = $this->Location_model->location_delete($lid);
		if($result != null){
			$this->response(array('msg'=>'Location deleted.','status'=>200),200);
		} else {
			$this->response(array('msg'=>'Something went wrong.','status'=>500),500);
		}
	}
}
