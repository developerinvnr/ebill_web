<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assigntask_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model(array('Costcenter_model','Company_model','Location_model','User_model','Assigntask_model','Meter_model'));
  }

	function getUserById(){
		$uid = $this->input->post('uid');
		$result = $this->User_model->user_list($uid);
		if(count($result)>0) {
			echo json_encode(array('data'=>$result[0],'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getUsers(){
		$result = $this->User_model->user_list();
		
		if(!is_null($result) && count($result)>0){
			echo json_encode(array('data'=>$result,'status'=>200));
		} else {
			echo json_encode(array('msg'=>'No record found.','status'=>500));
		}
	}

	function getSubMeterDetail($mid){
		$this->db->select('*');
		$result = $this->db->get_where('meter_master',array('parent_meter'=>$mid,'status'=>1))->result_array();
		return $result;
	}

  function index(){
		$data['companies'] = $this->Company_model->Company_list();
		$data['users'] = $this->User_model->user_list();
		$data['meters'] = $this->Meter_model->Meter_list();   
	
		$totalLength = is_null($data['meters']) ? null : count($data['meters']);

		$main_meters = array();
		
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			$data['main_content'] = $this->load->view('assigntask',$data,true);
	  		$this->load->view('admin_layout',$data);
		} else {
			$this->form_validation->set_rules('company', 'Company', 'required|trim');
			$this->form_validation->set_rules('costc_id', 'Cost Center', 'required|trim');
			$this->form_validation->set_rules('loc_id', 'Location', 'required|trim');
			$this->form_validation->set_rules('meter', 'Service No', 'required|trim|is_natural');
			$this->form_validation->set_rules('sub-meter', 'Sub Meter', 'trim');
			$this->form_validation->set_rules('user', 'Employee', 'required|trim|is_natural');
			
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			if ($this->form_validation->run()){
				$utid = $this->input->post('utid');
				$db_data['sno_id'] = $this->input->post('meter');
				$db_data['sub_meter_id'] = $this->input->post('sub-meter') == '' ? null : $this->input->post('sub-meter');
				$db_data['user_id'] = $this->input->post('user');
				$db_data['meter_reading'] = $this->input->post('meter_reading') == 'on' ? '1' : '0';
				$db_data['reading_frq'] = $this->input->post('reading_frq');
				$db_data['bill_upload'] = $this->input->post('bill_upload') == 'on' ? '1' : '0';
				$db_data['upload_frq'] = $this->input->post('upload_frq');
				$db_data['created_by'] = $this->session->userdata('user_id');
				$db_data['created_at'] = date('Y-m-d');
				if($utid == ''){
					$result = $this->Assigntask_model->create_task($db_data);
				} else {
					$result = $this->Assigntask_model->update_task($db_data,$utid);
				}
				if($result){
					$data['companies'] = $this->Company_model->Company_list();
					$data['users'] = $this->User_model->user_list();
					$data['tasks'] = $this->Assigntask_model->task_list();
					if($utid == ''){
						$this->session->set_flashdata('msg','<div class="alert alert-success" role="alert">
                            task created successfully.
                          </div>');
					} else {
						$this->session->set_flashdata('msg','<div class="alert alert-warning" role="alert">
                            task updated successfully.
                          </div>');
					}
					redirect(current_url());
				} else{
					$this->session->set_flashdata('msg','<div class="alert alert-danger" role="alert">
                        Something went wrong.
                      </div>');

					$data['main_content'] = $this->load->view('assigntask',$data,true);
	  				$this->load->view('admin_layout',$data);
				}
			} else {
				//echo validation_errors(); die;
				$data['main_content'] = $this->load->view('assigntask',$data,true);
	  			$this->load->view('admin_layout',$data);
			}
		}
  }

  function delete_user(){
	  $uid = $this->input->post('uid');
	  $result = $this->User_model->user_delete($uid);
	  
	  if($result){
		  echo json_encode(array('msg'=>'Location deleted successfully.','status'=>200));
	  } else {
		echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
	  }
  }
  
  function assign_user_remove(){
      $meter = $this->input->post('meter') == '0' ? $this->input->post('sub-meter') : $this->input->post('meter');
      
      $this->db->query("UPDATE task_assign set status = 0 where sub_meter_id =". $this->input->post('sub-meter')." AND sno_id =". $meter);
      echo json_encode(array('msg'=>'Remove permissions.','status'=>200));       
  }

  function assign_user_list_submit(){
      $meter = $this->input->post('meter') == '0' ? $this->input->post('sub-meter') : $this->input->post('meter');
      
      if($this->input->post('meter') == '0'){
          $sub_meter = NULL;
      } else {
          $sub_meter = $this->input->post('sub-meter');
      }
      
      $this->db->select('task_id');
      $result = $this->db->get_where('task_assign',array(
          'sno_id' => $meter,
          'sub_meter_id' => $sub_meter,
          'status' => 1
      ))->result_array();
      
      if(count($result)>0){
          $taskIds = '';
          foreach($result as $r){
              $taskIds .= $r['task_id'].',';
          }
          $taskIds = rtrim($taskIds, ", ");
          
          $this->db->query("UPDATE `task_assign` SET `status` = 0
          WHERE `task_id` IN($taskIds)");
      }
      
      
          if($this->input->post('reading-user') == $this->input->post('billupload-user')){
              $this->db->insert('task_assign',array(
                  'sno_id' => $meter,
                  'sub_meter_id' => $sub_meter,
                  'user_id' => $this->input->post('reading-user'),
                  'meter_reading' => $this->input->post('meter_reading'),
                  'reading_frq' => $this->input->post('reading_frq'),
                  'bill_upload' => $this->input->post('bill_upload'),
                  'upload_frq' => $this->input->post('upload_frq'),
                  'created_by' => $this->session->userdata('user_id'),
                  'created_at' => date('Y-m-d')
              ));
          } else {
              $this->db->insert('task_assign',array(
                  'sno_id' => $meter,
                  'sub_meter_id' => $sub_meter,
                  'user_id' => $this->input->post('reading-user'),
                  'meter_reading' => $this->input->post('meter_reading'),
                  'reading_frq' => $this->input->post('reading_frq'),
                  'created_by' => $this->session->userdata('user_id'),
                  'created_at' => date('Y-m-d')
              )); 
              $this->db->insert('task_assign',array(
                  'sno_id' => $meter,
                  'sub_meter_id' => $sub_meter,
                  'user_id' => $this->input->post('billupload-user'),
                  'bill_upload' => $this->input->post('bill_upload'),
                  'upload_frq' => $this->input->post('upload_frq'),
                  'created_by' => $this->session->userdata('user_id'),
                  'created_at' => date('Y-m-d')
              ));
          }
      echo json_encode(array('msg'=>'User Assign successfully.','status'=>200));
  }
  function assign_user_list($companyId=null,$costCenterId=null,$location=null){
      $data['companies'] = $this->Company_model->company_list();
      $data['cost_centers'] = $this->Costcenter_model->costcenter_list($companyId);
      
      $data['locations'] = $this->Location_model->getLocationByCostcenterId($costCenterId);
      
      
        $query = "SELECT u.fname,u.lname,mm.mid,if(isnull(mm.parent_meter),'main-meter','sub-meter') as mtype,mm.bpno,mm.parent_meter,
								cm.cid,cm.name as company,ccm.costc_id,ccm.name as cost_center_name,lm.loc_id,lm.name as location,ta.user_id,ta.meter_reading,ta.reading_frq,
								ta.bill_upload,ta.upload_frq
								FROM meter_master mm
								left JOIN meter_master mm2 on mm2.parent_meter = mm.mid";
                                if(is_null($companyId)){
								    $query.= " JOIN company_master cm on cm.cid = mm.cid";
                                } else {
                                    $query.= " JOIN company_master cm on cm.cid = mm.cid AND cm.cid = ".$companyId;
                                }
                                
                                if(is_null($costCenterId)){
                                    $query.= " JOIN cost_center_master ccm on ccm.costc_id = mm.costc_id";
                                } else {
                                    $query.= " JOIN cost_center_master ccm on ccm.costc_id = mm.costc_id AND ccm.costc_id = ".$costCenterId;
                                }
                                
                                if(is_null($location)){
                                    $query.= " JOIN location_master lm on lm.loc_id = mm.loc_id";
                                } else {
                                    $query.= " JOIN location_master lm on lm.loc_id = mm.loc_id AND lm.loc_id = ".$location;
                                }
                                
                                $query .= " join users u on u.uid = mm.created_by
								left JOIN (SELECT task_id,if(isnull(sub_meter_id),sno_id,sub_meter_id) as meter_id,user_id,meter_reading,reading_frq,bill_upload,upload_frq FROM task_assign WHERE status = 1) as ta on ta.meter_id = mm.mid
								group by mm.bpno
								order by mm.mid";
   // echo $query; die;
      
                                $data['records'] = $this->db->query($query)->result_array();
    //print_r($data['records']); die; 
	$this->db->select('*');
	$data['users'] = $this->db->get_where('users',array('status'=>1))->result_array();
	
	$data['main_content'] = $this->load->view('assigntask-show',$data,true);
	$this->load->view('admin_layout',$data);
  }
}
