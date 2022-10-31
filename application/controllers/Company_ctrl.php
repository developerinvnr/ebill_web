<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model('Company_model');
  }

	function getCompanyById(){
		$cid = $this->input->post('cid');
		$result = $this->Company_model->company_list($cid);
		if(count($result)>0){
			echo json_encode(array('data'=>$result[0],'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getCompanies(){
		$result = $this->Company_model->company_list();
		
		if(count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

  function index(){
		$data['companies'] = $this->Company_model->company_list();	
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			$data['main_content'] = $this->load->view('master/company',$data,true);
	  		$this->load->view('admin_layout',$data);
		} else {
			$this->form_validation->set_rules('cname', 'Company name', 'required|trim');
			$this->form_validation->set_rules('address', 'Compnay address', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|trim');
			$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
			$this->form_validation->set_rules('alternet_no', 'Alternet Contact No', 'min_length[10]|max_length[13]|trim|is_natural');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			if ($this->form_validation->run()){
				$cid = $this->input->post('cid');
				$db_data['name'] = $this->input->post('cname');
				$db_data['address'] = $this->input->post('address');
				$db_data['email'] = $this->input->post('email');
				$db_data['contact_no'] = $this->input->post('contact');
				$db_data['alternet_no'] = $this->input->post('alternet_no');
				$db_data['created_by'] = $this->session->userdata('user_id');
				$db_data['created_at'] = date('Y-m-d');
				if($cid == ''){
					$result = $this->Company_model->create_company($db_data);
				} else {
					$result = $this->Company_model->update_company($db_data,$cid);
				}
				if($result){
					$data['companies'] = $this->Company_model->company_list();
					if($cid == ''){
						$this->session->set_flashdata('msg','Company created successfully.');
					} else {
						$this->session->set_flashdata('msg','Company updated successfully.');
					}
					redirect(current_url());
				} else{
					$this->session->set_flashdata('msg','Something went wrong.');

					$data['main_content'] = $this->load->view('master/company',$data,true);
	  				$this->load->view('admin_layout',$data);
				}
			} else {
			    //echo validation_errors(); die; 
				$data['main_content'] = $this->load->view('master/company',$data,true);
	  			$this->load->view('admin_layout',$data);
			}
		}
  }

  function delete_company(){
	  $cid = $this->input->post('cid');
	  $result = $this->Company_model->company_delete($cid);
	  
	  if($result){
		  echo json_encode(array('msg'=>'Company deleted successfully.','status'=>200));
	  } else {
		echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
	  }
  }

  function logout(){
	session_destroy();
	redirect('/');
  }
}
