<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Assigntask_model extends CI_Model {

	function create_task($data){

		$result = $this->db->insert('task_assign',$data);
		if($result){
			return true;
		} else {
			return null;
		}
	}


	function update_user($data,$uid){
		$this->db->where('uid',$uid);
		$result = $this->db->update('users',$data);

		if($result){
			return true;
		} else {
			return null;
		}
	}

	function task_list($uid=null){
		$this->db->SELECT("t.task_id,
			if(t.sub_meter_id is null,m1.bpno,m2.bpno) bpno,
			if(t.sub_meter_id is null,(CONCAT(u1.fname,' ', u1.lname)),(CONCAT(u2.fname,' ', u2.lname))) as empoyee,
			if(t.meter_reading = 1,'yes','no') as meter_reading,
			if(t.bill_upload = 1,'yes','no') as bill_upload,
			t.reading_frq,
			t.upload_frq,
			cm.name as company_name,
			ccm.name as cost_center,
			lm.name as location,
			CONCAT(u2.fname,u2.lname) as user_name");
		$this->db->join('meter_master m1','m1.mid = t.sno_id AND m1.status = 1','left');
		$this->db->join('meter_master m2','m2.mid = t.sub_meter_id AND m2.status = 1','left');
		$this->db->join('users u1','u1.uid = t.user_id AND u1.status = 1');
		$this->db->join('users u2','u2.uid = t.created_by AND u2.status = 1');
		$this->db->join('company_master cm','cm.cid = m1.cid and cm.status = 1');
		$this->db->join('cost_center_master ccm','ccm.costc_id = m1.costc_id and ccm.status = 1');
		$this->db->join('location_master lm','lm.loc_id = m1.loc_id AND lm.status = 1');
		$result = $this->db->get_where('task_assign t',array('t.status'=>1))->result_array();

		if(count($result)>0){
			return  $result;
		} else {
			return  null;
		}
	}

	function user_delete($uid){
		$this->db->where('uid',$uid);
		$result = $this->db->update('users',array('status'=>0));

		//$result = $this->db->delete('users',array('uid'=>$uid));
		if($result){
			return true;
		} else {
			return null;
		}
	}
}
