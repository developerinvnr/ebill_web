<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Costcenter_model extends CI_Model {

	function create_costcenter($data){

	    $this->db->select('*');
	    $result = $this->db->get_where('cost_center_master',array('company_id'=>$data['company_id'],'cc_id'=>$data['cc_id'],'status'=>1))->result_array();
	    if(count($result)>0){
	        $result = true;
	    } else {
		  $result = $this->db->insert('cost_center_master',$data);
	    }

		if($result){
			return true;
		} else {
			return null;
		}
	}

	function update_costcenter($data,$cid){
		$this->db->where('costc_id',$cid);
		$result = $this->db->update('cost_center_master',$data);

		if($result){
			return true;
		} else {
			return null;
		}
	}

	function costcenter_list($cid=null){
		$this->db->select('ccm.*,u.fname,u.lname,ut.type_name,cm.name as company_name');
		if(!is_null($cid)){
			$this->db->where('ccm.costc_id',$cid);
		}
		$this->db->join('company_master cm','cm.cid = ccm.company_id AND cm.status = 1');
		$this->db->join('users u','u.uid = cm.created_by');
		$this->db->join('user_type ut','ut.utype_id = u.utype');
		$result = $this->db->get_where('cost_center_master ccm',array('ccm.status'=>1))->result_array();
		if(count($result)>0){
			return  $result;
		} else {
			return  null;
		}
	}

	function costcenter_delete($cid){
		$this->db->where('costc_id',$cid);
		$result = $this->db->update('cost_center_master',array('status'=>0));

		if($result){
			return true;
		} else {
			return null;
		}
	}

	function getCostcenterByCompnayId($cid,$search){
	    $uid = $this->session->userdata('user_id');
	    if($this->session->userdata('role') == 'manager'){
	        $this->db->select('uid');
	        $users = $this->db->get_where('users',array('reporting_to'=>$uid,'status'=>1))->result_array();
	        
	        $ulist = '';
	        foreach($users as $u){
	            $ulist .= $u['uid'].',';
	        }
	        $ulist = rtrim($ulist, ',');
	        
	        $query = "SELECT * FROM cost_center_master WHERE company_id in (select mm.cid from meter_master mm
                      JOIN company_master cm on cm.cid = mm.cid";
	        if(!is_null($cid)){
	            $query .= " AND cm.cid = ".$cid;
	        }
	        if(!is_null($search)  && $search != ''){
	            $query .= " AND ccm.name LIKE '".$search."%'";
	        }
	        
	        $query .= " WHERE mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign`
                                     WHERE user_id in (".$ulist.") AND status = 1)
                       GROUP by mm.cid)";
	        $result = $this->db->query($query)->result_array();
	    }
	    else if($this->session->userdata('role') != 'super_admin'){
	        $query = "SELECT * FROM cost_center_master WHERE company_id in (select mm.cid from meter_master mm
                      JOIN company_master cm on cm.cid = mm.cid";
	        if(!is_null($cid)){
	            $query .= " AND cm.cid = ".$cid; 
	        }
	        if(!is_null($search)  && $search != ''){
	            $query .= " AND ccm.name LIKE '".$search."%'";
	        }
	           
            $query .= " WHERE mid in (SELECT if(ISNULL(sub_meter_id),sno_id,sub_meter_id) as meters FROM `task_assign`
                                     WHERE user_id = ".$this->session->userdata('user_id')." AND status = 1)
                       GROUP by mm.cid)";
	        $result = $this->db->query($query)->result_array();
	    }
	    else {
    		$this->db->select('ccm.*,cm.name as companyname,u.fname,u.lname');
    		$this->db->join('company_master cm','cm.cid = ccm.company_id AND cm.status = 1');
    		$this->db->join('users u','u.uid = cm.created_by');
    		if(!is_null($cid)){
    		    $this->db->where('ccm.company_id',$cid);
    		}
    		if(!is_null($search) && $search != ''){
    		    $this->db->like('ccm.name',$search,'right');
    		}
    		$result = $this->db->get_where('cost_center_master ccm',array('ccm.status'=>1))->result_array();
	    }
		return $result;
	}
}
