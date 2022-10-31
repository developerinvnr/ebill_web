<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Costcenter_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model(array('Costcenter_model','Company_model'));
  }

	function getCostCenterById(){
		$cid = $this->input->post('cid');
		$result = $this->Costcenter_model->costcenter_list($cid);
		if(count($result)>0){
			echo json_encode(array('data'=>$result[0],'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getCostcenters(){
		$result = $this->Costcenter_model->costcenter_list();
		
		if(!is_null($result) && count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getCostcenterByCompnayId($cid=null){
	   // $search = $_REQUEST['search'];
	    $search = '';
	    $result = $this->Costcenter_model->getCostcenterByCompnayId($cid,$search);
		
		if(!is_null($result) && count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

  function index(){
        $data['ccids'] = $this->db->get_where('cost_center',array('status'=>1))->result_array();
		$data['costceners'] = $this->Costcenter_model->costcenter_list();
		$farray = array();
		foreach($data['costceners'] as $cc){
		  $temp = $cc;
		  $temp['created_at'] = date('d/m/Y',strtotime($temp['created_at']));
		  $farray[] = $temp;
		}
		$data['costceners'] = $farray;
		$data['companies'] = $this->Company_model->Company_list();
		
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			$data['main_content'] = $this->load->view('master/cost-center',$data,true);
	  		$this->load->view('admin_layout',$data);
		} else {
		    $this->form_validation->set_rules('cost_center', 'Select Cost Center', 'required|trim');
			$this->form_validation->set_rules('cname', 'Cost-Center name', 'required|trim');
			$this->form_validation->set_rules('company', 'Compnay', 'required|trim');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			if ($this->form_validation->run()){
				$cid = $this->input->post('cid');
				$db_data['name'] = $this->input->post('cname');
				$db_data['cc_id'] = $this->input->post('cost_center');
				$db_data['company_id'] = $this->input->post('company');
				$db_data['created_by'] = $this->session->userdata('user_id');
				$db_data['created_at'] = date('Y-m-d');
				if($cid == ''){
					$result = $this->Costcenter_model->create_costcenter($db_data);
				} else {
					$result = $this->Costcenter_model->update_costcenter($db_data,$cid);
				}
				if($result){
					$data['companies'] = $this->Costcenter_model->costcenter_list();
					if($cid == ''){
						$this->session->set_flashdata('msg','Costcenter created successfully.');
					} else {
						$this->session->set_flashdata('msg','Costcenter updated successfully.');
					}
					redirect(current_url());
				} else{
					$this->session->set_flashdata('msg','Something went wrong.');

					$data['main_content'] = $this->load->view('master/cost-center',$data,true);
	  				$this->load->view('admin_layout',$data);
				}
			} else {
				$data['main_content'] = $this->load->view('master/cost-center',$data,true);
	  			$this->load->view('admin_layout',$data);
			}
		}
  }

  function delete_costcenter(){
	  $cid = $this->input->post('cid');
	  $result = $this->Costcenter_model->costcenter_delete($cid);
	  
	  if($result){
		  echo json_encode(array('msg'=>'Costcenter deleted successfully.','status'=>200));
	  } else {
		echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
	  }
  }
}
