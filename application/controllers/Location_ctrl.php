<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model(array('Costcenter_model','Company_model','Location_model'));
  }

	function getLocationById(){
		$lid = $this->input->post('lid');
		$result = $this->Location_model->location_list($lid);
		if(count($result)>0){
			echo json_encode(array('data'=>$result[0],'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getLocationByCostcenterId($costc_id){
		$result = $this->Location_model->getLocationByCostcenterId($costc_id);
		if(!is_null($result) && count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}
	
	function getLocationFilter($text=null){
	    $result = $this->Location_model->LocationFilter($text);
	    if(!is_null($result) && count($result)>0){
	        echo json_encode(array('data'=>$result,'status'=>200));
	    } else {
	        echo json_encode(array('msg'=>'No record found.','status'=>500));
	    }
	}

	function getLocations(){
		$result = $this->Location_model->location_list();
		
		if(!is_null($result) && count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

  function index(){
        $data['locations2'] = $this->db->get_where('location',array('status'=>1))->result_array();
	  	$data['locations'] = $this->Location_model->location_list();
		//$data['costceners'] = $this->Costcenter_model->costcenter_list();
		$data['companies'] = $this->Company_model->Company_list();
		
		
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			$data['main_content'] = $this->load->view('master/location',$data,true);
	  		$this->load->view('admin_layout',$data);
		} else {
		    $this->form_validation->set_rules('location', 'Location name', 'required|trim');
			//$this->form_validation->set_rules('lname', 'Location name', 'required|trim');
			$this->form_validation->set_rules('cost_center', 'Cost center', 'required|trim');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			if ($this->form_validation->run()){
				$lid = $this->input->post('lid');
				$db_data['lc_id'] = $this->input->post('location');
				$db_data['name'] = $this->input->post('lname');
				$db_data['cost_center_id'] = $this->input->post('cost_center');
				$db_data['created_by'] = $this->session->userdata('user_id');
				$db_data['created_at'] = date('Y-m-d');
				
				if($lid == ''){
					$result = $this->Location_model->create_location($db_data);
				} else {
					$result = $this->Location_model->update_location($db_data,$lid);
				}
				if($result){
					$data['companies'] = $this->Location_model->location_list();
					if($lid == ''){
						$this->session->set_flashdata('msg','Location created successfully.');
					} else {
						$this->session->set_flashdata('msg','Location updated successfully.');
					}
					redirect(current_url());
				} else{
					$this->session->set_flashdata('msg','Something went wrong.');

					$data['main_content'] = $this->load->view('master/location',$data,true);
	  				$this->load->view('admin_layout',$data);
				}
			} else {
				$data['main_content'] = $this->load->view('master/location',$data,true);
	  			$this->load->view('admin_layout',$data);
			}
		}
  }

  function delete_location(){
	  $lid = $this->input->post('lid');
	  $result = $this->Location_model->location_delete($lid);
	  
	  if($result){
		  echo json_encode(array('msg'=>'Location deleted successfully.','status'=>200));
	  } else {
		echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
	  }
  }
  
  function get_my_location($com_id,$costc_id){
      if($this->session->userdata('role') == 'manager'){
          $uid = $this->session->userdata('user_id');
          
          $this->db->select('uid');
          $users = $this->db->get_where('users',array('reporting_to'=>$uid,'status'=>1))->result_array();
          
          $ulist = '';
          foreach($users as $u){
              $ulist .= $u['uid'].',';
          }
          $ulist = rtrim($ulist, ',');
          
          $query = "select * from location_master WHERE
                    cost_center_id in (SELECT costc_id FROM cost_center_master WHERE
                        company_id in (select mm.cid from meter_master mm JOIN company_master cm on cm.cid = mm.cid AND cm.cid = ".$com_id." WHERE
                            mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign`
                                WHERE user_id in (".$ulist.") AND status = 1) GROUP by mm.cid)
                    )";
          $result = $this->db->query($query)->result_array();
      }
      else if($this->session->userdata('role') != 'super_admin'){
          $query = "select * from location_master WHERE 
                    cost_center_id in (SELECT costc_id FROM cost_center_master WHERE 
                        company_id in (select mm.cid from meter_master mm JOIN company_master cm on cm.cid = mm.cid AND cm.cid = ".$com_id." WHERE 
                            mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign` 
                                WHERE user_id = ".$this->session->userdata('user_id')." AND status = 1) GROUP by mm.cid)
                    )";
         $result = $this->db->query($query)->result_array();
         
      } else {
          $result = $this->Location_model->getLocationByCostcenterId($costc_id);
      }
      
      
      if(!is_null($result) && count($result)>0){
          echo json_encode(array('data'=>$result,'status'=>200));
      } else {
          echo json_encode(array('msg'=>'No record found.','status'=>500));
      }
  }
}
