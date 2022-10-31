<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Auth_model');
        if($this->session->userdata('user_id') != null){
            if($this->session->userdata('role') == 'admin'){
                redirect('/bill-upload');
            } else {
                redirect('dashboard');
            }
        }
    }
    
	
    function index(){
        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->load->view('login');
        } else {
            $this->form_validation->set_rules('identity', 'Identity', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            if ($this->form_validation->run()){
                
                $data['identity'] = $this->input->post('identity');
                $data['password'] = sha1($this->input->post('password'));
                
                $result = $this->Auth_model->login($data);
                if($result){
                    $this->session->set_userdata(array(
                        'user_id' => $result[0]['uid'],
                        'name' => $result[0]['fname'].' '.$result[0]['lname'],
                        'role' => $result[0]['type_name'],
                    ));
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('msg', '<p class="text-danger text-center">Login Failed. Please try again.</p>');
                    $this->load->view('login');
                }
            } else {
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                $this->load->view('login');
            }
        }
    }
    
    function logout(){
        $this->session->sess_destroy();
        echo $this->session->userdata('user_id');
        //rediret('/Auth');
    }
}
