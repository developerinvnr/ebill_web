<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_ctrl extends CI_Controller {

	function __construct() {
    parent::__construct();
		$this->load->database();
		$this->load->model(array('Costcenter_model','Company_model','Location_model','User_model'));
  }

	function getUserById(){
		$uid = $this->input->post('uid');
		$result = $this->User_model->user_list($uid);
		if(count($result)>0){
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

  function index(){
	  	$this->db->where_not_in('type_name','super_admin');
	  	$data['user_types'] = $this->db->get_where('user_type',array('status'=>1))->result_array();
	  	  
	  	$data['users'] = $this->User_model->user_list();
	  	$data['locations'] = $this->Location_model->location_list();
		$data['costceners'] = $this->Costcenter_model->costcenter_list();
		$data['companies'] = $this->Company_model->Company_list();
		
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			$data['main_content'] = $this->load->view('master/user',$data,true);
	  		$this->load->view('admin_layout',$data);
		} else {
			$this->form_validation->set_rules('fname', 'First name', 'required|trim');
				$this->form_validation->set_rules('lname', 'Last name', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
				$this->form_validation->set_rules('contact', 'Contact No', 'required|min_length[10]|max_length[13]|trim|is_natural');
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|trim');
				$this->form_validation->set_rules('sex', 'Gender', 'required|trim');
				$this->form_validation->set_rules('utype', 'User type', 'required|trim');

			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			if ($this->form_validation->run()){
				$uid = $this->input->post('uid');
				$db_data['fname'] = $this->input->post('fname');
				$db_data['lname'] = $this->input->post('lname');
				$db_data['email'] = $this->input->post('email');
				$db_data['contact_no'] = $this->input->post('contact');
				$db_data['password'] = sha1($this->input->post('password'));
				$db_data['sex'] = $this->input->post('sex');
				$db_data['utype'] = $this->input->post('utype');
				$db_data['created_by'] = $this->session->userdata('user_id');
				$db_data['created_at'] = date('Y-m-d');
				if($uid == ''){
					$result = $this->User_model->create_user($db_data);
				} else {
					$result = $this->User_model->update_user($db_data,$uid);
				}
				if($result) {
					$data['users'] = $this->User_model->user_list();
					$data['locations'] = $this->Location_model->location_list();
					$data['costceners'] = $this->Costcenter_model->costcenter_list();
					$data['companies'] = $this->Company_model->Company_list();

					if($uid == '') {
						$this->session->set_flashdata('msg','Location created successfully.');
					} else {
						$this->session->set_flashdata('msg','Location updated successfully.');
					}
					redirect(current_url());
				} else {
					$this->session->set_flashdata('msg','Something went wrong.');

					$data['main_content'] = $this->load->view('master/user',$data,true);
	  				$this->load->view('admin_layout',$data);
				}
			} else {
				$data['main_content'] = $this->load->view('master/user',$data,true);
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

  function check_password(){
	  $password = sha1($this->input->post('password'));
	  $uid = $this->session->userdata('user_id');

	  $this->db->select('*');
	  $result = $this->db->get_where('users',array('uid'=>$uid,'password'=>$password,'status'=>1))->result_array();
	  if(count($result)>0){
		  echo json_encode(array('msg'=>'password is valid.','status'=>200));
	  } else {
		echo json_encode(array('msg'=>'password is not valid.','status'=>500));
	  }

  }
  function change_password(){
	if ($this->input->server('REQUEST_METHOD') === 'GET') {
		$data['main_content'] = $this->load->view('forgot-password','',true);
		$this->load->view('admin_layout',$data);
	} else {
		$this->form_validation->set_rules('old_password', 'Old Password', 'required|trim|min_length[4]');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[4]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[new_password]|min_length[4]');

		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		if ($this->form_validation->run()){
			$uid = $this->session->userdata('user_id');
			$password = sha1($this->input->post('new_password'));
			if($this->User_model->change_password($uid,$password)){
				$this->session->set_flashdata('msg', '<p class="text-success text-center">Password Change successfully.</p>');
				redirect(current_url());
			}
		} else {
			$data['main_content'] = $this->load->view('forgot-password','',true);
			$this->load->view('admin_layout',$data);
		}
	}
  }
  
  function my_user_list($uid){
      $this->db->select('*');
      $result = $this->db->get_where('users',array(
                            'status' => 1,
                            'reporting_to' =>  $uid,
                            'uid <>' => $this->session->userdata('user_id')
      ))->result_array();
      echo json_encode(array('data'=>$result,'status'=>200));
  }
  
  function user_assign(){
      $data['users'] = $this->User_model->get_all_reportings();
      $data['main_content'] = $this->load->view('master/user-assing',$data,true);
      $this->load->view('admin_layout',$data);
  }
  
  function manage_user_assign(){
      $data['type'] = $this->input->post('type');
      $data['reporting_id'] = $this->input->post('reporting_id');
      $data['user_id'] = $this->input->post('user_id');
      
      if($data['type'] == 'remove'){
          $data['reporting_id'] = null;
      }
      
      $this->db->where('uid',$data['user_id']);
      $this->db->update('users',array('reporting_to'=>$data['reporting_id']));
      return true;
  }
}
