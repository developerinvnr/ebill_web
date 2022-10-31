<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Bill_ctrl extends REST_Controller {

	function __construct() {
    parent::__construct();
        $this->load->library('upload');
        $this->load->database();
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

  

  function index_post($bill_id=null){
          $this->form_validation->set_rules('serviceno', 'Service No', 'required|trim');
          $this->form_validation->set_rules('billing_period_from', 'Billing Period From', 'required|trim');
          $this->form_validation->set_rules('billing_period_to', 'Billing Period To', 'required|trim');
          $this->form_validation->set_rules('bill_no', 'Bill No', 'required|trim');
          $this->form_validation->set_rules('bill_date', 'Bill Date', 'required|trim');
          $this->form_validation->set_rules('due_date', 'Due Date', 'required|trim');
          $this->form_validation->set_rules('current_reading', 'Current Reading', 'required|trim');
          $this->form_validation->set_rules('current_reading_date', 'Current Reading Date', 'required|trim');
          $this->form_validation->set_rules('previous_reading', 'Previous Reading', 'required|trim');
          $this->form_validation->set_rules('previous_reading_date', 'Previous Reading Date', 'required|trim');
          $this->form_validation->set_rules('total_consumption', 'Total Consumption', 'required|trim');
          $this->form_validation->set_rules('highest_demand_rating', 'Highest Demand Rating', 'trim');
          $this->form_validation->set_rules('total_charges', 'Sum', 'required|trim');
          $this->form_validation->set_rules('cess', 'cess', 'trim');
          $this->form_validation->set_rules('concession_amount', 'Concession Amount', 'trim');
          $this->form_validation->set_rules('vca', 'VCA', 'trim');
          $this->form_validation->set_rules('past_due', 'Past Due', 'required|trim');
          $this->form_validation->set_rules('payable_amount', 'Payable Amount', 'required|trim');
          $this->form_validation->set_rules('surcharge', 'surcharge', 'required|trim');
          $this->form_validation->set_rules('total_bill', 'Total Bill', 'required|trim');
          
          if ($this->form_validation->run()){
            $config = array(
              'upload_path' => APPPATH."../upload/bills/",
              'allowed_types' => "gif|jpg|png|jpeg|PDF|pdf",
              'file_name' => $this->input->post('bill_no').'_'.date('m_Y',strtotime(str_replace('/', '-', $this->input->post('bill_date')))).'.'.pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION)
            );
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if($this->upload->do_upload('userfile')){
              $fdata = array('upload_data' => $this->upload->data());
              $db_data['image'] = $this->input->post('bill_no').'_'.date('m_Y',strtotime(str_replace('/', '-', $this->input->post('bill_date')))).$fdata['upload_data']['file_ext'];
            }

              $db_data['sno_id'] = $this->input->post('serviceno');
              $db_data['from_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('billing_period_from'))));
              $db_data['to_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('billing_period_to'))));
              $db_data['bill_no'] = $this->post('bill_no');
              $db_data['date_of_bill'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('bill_date'))));
              $db_data['due_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('due_date'))));
              $db_data['reading'] = $this->post('current_reading');
              $db_data['reading_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('current_reading_date'))));
              
              $db_data['previous_reading'] = $this->post('previous_reading');
              $db_data['previous_reading_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $this->post('previous_reading_date'))));
              
              $db_data['power_consumption'] = $this->post('power_consumption');
              $db_data['power_factor'] =   $this->post('power_factor');
              $db_data['total_consumption'] = $this->post('total_consumption');
              $db_data['highest_demand_reading'] = $this->post('highest_demand_rating');
              $db_data['je_ae_name'] = $this->post('je_ae_name');
              $db_data['je_ae_contact_no'] = $this->post('je_ae_contact');
              $db_data['ae_ee_name'] = $this->post('ae_ee_name');
              $db_data['ae_ee_contact_no'] = $this->post('ae_ee_contact');
              $db_data['fixed_demand_charges'] = $this->post('fix_demand');
              $db_data['minimum_charges'] = $this->post('minimum_charge');
              $db_data['energy_charges'] = $this->post('energy_charges');
              $db_data['total_charges'] = $this->post('total_charges');
              $db_data['electricity_duty'] = $this->post('electricity_duty');
              $db_data['cess'] = $this->post('cess');
              $db_data['welding_capacitor_overload'] = $this->post('capacitor_overload');
              $db_data['meter_fare'] = $this->post('meter_fare');
              $db_data['vca_charge'] = $this->post('vca');
              $db_data['security_deposit'] = $this->post('security_deposit');
              $db_data['concession_amount'] = $this->post('concession_amount');
              $db_data['total_bill'] = $this->post('total_bill');
              $db_data['deviation_adjustment'] = $this->post('deviation');
              $db_data['past_dues'] = $this->post('past_due');
              $db_data['security_fund_outstanding'] = $this->post('security_fund_outstanding');
              $db_data['payable_amount'] =$this->post('payable_amount');
              $db_data['extra'] = $this->post('extra');
              $db_data['coefficient'] = $this->post('coefficient');
              $db_data['gross_amount'] = $this->post('surcharge');
              $db_data['overload'] = $this->post('overload');
              $db_data['created_at'] = date('Y-m-d');
              $db_data['created_by'] = $this->jwt->decode($this->header['Token'])->user_id;
              
              
              if(is_null($bill_id)){
                $result = $this->bill_entry($db_data);
                
                if(!is_null($result)){
                  $this->response(array(
                        'msg' => 'Bill entry successfully',
                        'status'=>200),
                    200);
                  
                  } else {
                      $this->response(array(
                        'msg' => 'something went wrong12',
                        'status'=>200),
                    200);
                  }
              } else {
                  $result = $this->bill_entry($db_data,$bill_id);
                  if(!is_null($result)){
                      $this->response(array(
                        'msg' => 'Bill updated successfully',
                        'status'=>200),
                        200);
                  } else {
                      $this->response(array(
                        'msg' => 'something went wrong23',
                        'status'=>200),
                        200);
                  }
              }
              
          } else {
              print_r(validation_errors());
          }
      }
      
  function bill_entry($data,$sno_id=null){
	    if(!is_null($sno_id)){
            $this->db->where('bill_id',$sno_id);
            $this->db->update('bill',$data);
            $result = true;
	           
	    } else{ 
	       $this->db->select('*');
	       $billResult = $this->db->get_where('bill',array(
	           'bill_no' => $data['bill_no'],
	           'status' => 1
	       ))->result_array();
	       
	       if(count($billResult)>0){
	           $this->db->where('bill_no',$data['bill_no']);
	           $this->db->update('bill',$data);
	           $result = true;
	       } else {
	           $result = $this->db->insert('bill',$data);
	       }
	    }
	    
	    if($result){
	     return $result;   
	    } else{
	        return null;
	    }
	}
	
	
 function bill_list_get($bill_id=null){
    $this->db->SELECT('mm.bpno as service_no,b.*,cm.name as company_name,ccm.name as costcenter_name,lm.name as location_name,DATE_FORMAT(b.from_date,"%d-%m-%Y") as from_date,DATE_FORMAT(b.to_date,"%d-%m-%Y") as to_date,b.gross_amount as surcharge');
    $this->db->JOIN('meter_master mm','mm.mid = b.sno_id AND mm.status = 1');
    $this->db->JOIN('company_master cm','cm.cid = mm.cid AND cm.status = 1');
    $this->db->JOIN('cost_center_master ccm','ccm.costc_id = mm.costc_id AND ccm.status = 1');
    $this->db->JOIN('location_master lm','lm.loc_id = mm.loc_id AND lm.status = 1');
    if($bill_id != null){
        $this->db->where('b.bill_id',$bill_id);
    }
    $result = $this->db->get_where('bill b',array('b.status' => 1,'b.created_by'=> $this->jwt->decode($this->header['Token'])->user_id))->result_array();
    
    if(count($result)>0){
        if($bill_id != null){
            $result = $result[0];
        }
        $this->response(array(
                'data'=>$result,
                'msg' => 'All submitted bills',
            'status'=>200),
            200);
    } else {
        $this->response(array(
            'msg' => 'No record found.',
            'status'=>501),
        200);
    }   
 }	
 
 
 
 function new_entry_detail_get($sno){ 
        $this->db->select('bill_id,max(date_of_bill) date_of_bill');
        $result = $this->db->get_where('bill',array('sno_id'=>$sno,'status'=>1))->result_array();
        
        if($result[0]['date_of_bill'] != null){
            $this->db->select('b.*,b.gross_amount as surcharge,mm.bpno as service_no,cm.name as company_name,ccm.name as costcenter_name,lm.name as location_name');
            $this->db->join('meter_master mm','mm.mid = b.sno_id AND mm.status = 1');
            $this->db->join('company_master cm','cm.cid = mm.cid AND cm.status = 1');
            $this->db->join('cost_center_master ccm','ccm.costc_id = mm.costc_id AND ccm.status = 1');
            $this->db->join('location_master lm','lm.loc_id = mm.loc_id AND lm.status = 1');
            $result = $this->db->get_where('bill b',array('b.bill_id'=>$result[0]['bill_id']))->result_array();
            if($result > 0){
                $result[0]['from_date'] = date('Y-m-d', strtotime('+1 day', strtotime($result[0]['to_date'])));
            	$no_of_days = date('t',strtotime($result[0]['from_date']));
            	$result[0]['to_date'] = date('Y-m-d', strtotime('-1 day',strtotime('+'.$no_of_days.' day', strtotime($result[0]['from_date']))));
            	$result[0]['previous_reading'] = $result[0]['reading'];
            	$result[0]['previous_reading_date'] = $result[0]['reading_date'];
            	
            // 	$result[0]['date_of_bill'] =date('d-m-Y',strtotime($result[0]['date_of_bill'])); 
            // 	$result[0]['due_date'] =date('d-m-Y',strtotime($result[0]['due_date']));
            // 	$result[0]['reading_date'] =date('d-m-Y',strtotime($result[0]['reading_date']));
            // 	$result[0]['previous_reading_date'] =date('d-m-Y',strtotime($result[0]['previous_reading_date']));
            	
            	$this->response(array(
                    'data'=>$result[0],
                    'msg' => 'Bill-Upload Task Summary.',
                    'status'=>200),
                200);
                
            } else {
                $this->db->select('*');
                $result = $this->db->get_where('meter_master mm',array('mm.mid'=>$sno,'status'=>1))->result_array();
                
                $this->response(array(
                'data'=>$result[0],
                'msg' => 'Bill-Upload Task Summary.',
                'status'=>200),
                200);
            }
        } else {
            $this->db->select('bpno as service_no,mid as sno_id,cm.name as company_name,ccm.name as costcenter_name,lm.name as location_name');
            $this->db->join('company_master cm','cm.cid = mm.cid AND cm.status = 1');
            $this->db->join('cost_center_master ccm','ccm.costc_id = mm.costc_id AND ccm.status = 1');
            $this->db->join('location_master lm','lm.loc_id = mm.loc_id AND lm.status = 1');
            $result = $this->db->get_where('meter_master mm',array('mm.mid'=>$sno,'mm.status'=>1))->result_array();
            
            $this->response(array(
                'data' => $result[0],
                'status'=>201),
                200);
        } 
    }
}
