<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company_model extends CI_Model {

	function create_company($data){

		$result = $this->db->insert('company_master',$data);
		if($result){
			return true;
		} else {
			return null;
		}
	}

	function update_company($data,$cid){
		$this->db->select('*');
		$result = $this->db->get_where('company_master',array('cid'=>$cid,'status'=>1))->result_array();
		if(count($result)>0){
			$this->db->where('cid',$cid);
			$result = $this->db->update('company_master',$data);
			if($result){
				return true;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	function company_list($cid=null){
		$this->db->select('*');
		if(!is_null($cid)){
			$this->db->where('cid',$cid);
		}
		$result = $this->db->get_where('company_master',array('status'=>1))->result_array();
		if(count($result)>0){
			return  $result;
		} else {
			return  null;
		}
	}

	function company_delete($cid){
		$this->db->where('cid',$cid);
		$result = $this->db->update('company_master',array('status'=>0));
		//$result = $this->db->delete('company_master',array('cid'=>$cid));
		if($result){
			return true;
		} else {
			return null;
		}
	}
	
	
	function get_my_companies(){
	    if($this->session->userdata('role')== 'super_admin'){
	       $result = $this->db->query("select * from company_master where status = 1")->result_array();
	    }
	    else if($this->session->userdata('role')== 'manager'){
	        $uid = $this->session->userdata('user_id');
	        
	        $this->db->select('uid');
	        $users = $this->db->get_where('users',array('reporting_to'=>$uid,'status'=>1))->result_array();
	        
	        $ulist = '';
	        foreach($users as $u){
	            $ulist .= $u['uid'].',';
	        }
	        $ulist = rtrim($ulist, ',');
	        
	        $result = $this->db->query("select mm.cid,cm.name from meter_master mm
                        JOIN company_master cm on cm.cid = mm.cid
                        WHERE mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign` WHERE user_id in (".$ulist.") AND status = 1)
                        GROUP by mm.cid")->result_array();
	    }
	    else {
	       $result = $this->db->query("select mm.cid,cm.name from meter_master mm
                        JOIN company_master cm on cm.cid = mm.cid
                        WHERE mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign` WHERE user_id = ".$this->session->userdata('user_id')." AND status = 1)
                        GROUP by mm.cid")->result_array();
	    }
	    
	    return $result;
	}
}
