<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
header('Content-Type: application/json; charset=utf-8');

class Company_ctrl extends REST_Controller {

	function __construct() {
	   
    parent::__construct();
 
        $this->load->database();
		$this->load->model('Company_model');
		$this->jwt = new JWT();
		$this->header = ($this->input->request_headers());
		if(isset($this->header['Token']) &&  $this->header['Token'] != null){
			$this->user_data = $this->my_lib->is_valid($this->header['Token']);
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
			$result = $this->Company_model->company_list($cid);
			if($result != null && count($result)>0){
				$this->response(array('company_detail'=>$result[0],'msg'=>'company detail','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
		else {
			$result = $this->Company_model->company_list();
			if($result != null && count($result)>0){
				$this->response(array('data'=>array('companies'=>$result),'msg'=>'company list','status'=>200),200);
			} else {
				$this->response(array('msg'=>'no record found.','status'=>500),500);
			}
		}
	}

	function index_post(){
				$this->form_validation->set_rules('name', 'Company name', 'required|trim');
				$this->form_validation->set_rules('address', 'Compnay address', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
				$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
				$this->form_validation->set_rules('alternet_no', 'Alternet Contact No', 'min_length[10]|max_length[13]|trim|is_natural');

				if ($this->form_validation->run()){

					$data['name'] = $this->post('name');
					$data['address'] = $this->post('address');
					$data['email'] = $this->post('email');
					$data['contact_no'] = $this->post('contact');
					$data['alternet_no'] = $this->post('alternet_no');
					$data['created_by'] = $this->user_data->user_id;
					$data['created_at'] = date('Y-m-d');

					$result = $this->Company_model->create_company($data);
					if($result){
						$this->response(array('msg'=>'company created.','status'=>'200'),201);
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
		$this->form_validation->set_rules('name', 'Company name', 'required|trim');
		$this->form_validation->set_rules('address', 'Company address', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
		$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
		$this->form_validation->set_rules('alternet_no', 'Contact No', 'min_length[10]|max_length[13]|trim|is_natural');

		if ($this->form_validation->run()){
			$data['name'] = $this->put('name');
			$data['address'] = $this->put('address');
			$data['email'] = $this->put('email');
			$data['contact_no'] = $this->put('contact');
			$data['alternet_no'] = $this->put('alternet_no');
			$data['created_by'] = $this->user_data->user_id;

			$result = $this->Company_model->update_company($data,$cid);
			if($result){
				$this->response(array('msg'=>'Company updated.','status'=>'200'),202);
			} else{
				$this->response(array('msg'=>'somthing went wrong.','status'=>'500'),500);
			}
		} else {
			$data = validation_errors();
			$this->response(array('errors'=>$data,'status'=>500),500);
		}
	}

	function index_delete($cid){
		$result = $this->Company_model->company_delete($cid);
		if($result != null){
			$this->response(array('msg'=>'Company deleted.','status'=>200),200);
		} else {
			$this->response(array('msg'=>'Something went wrong.','status'=>500),500);
		}
	}
}
