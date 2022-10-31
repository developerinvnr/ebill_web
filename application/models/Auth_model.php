<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth_model extends CI_Model {

	function login($data){

		$this->db->select('u.uid,ut.type_name,u.fname,u.lname');
		$this->db->join('user_type ut','ut.utype_id = u.utype AND ut.status = 1');
		$result = $this->db->get_where('users u',array('u.email'=>$data['identity'],'u.password'=>$data['password'],'u.status'=>1))->result_array();

		if(count($result)>0){
			return $result;
		}
		return null;
	}
}
