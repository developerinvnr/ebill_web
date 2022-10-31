<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_lib{

  protected $CI;

  function __construct() {
    $this->CI =& get_instance();
    $this->CI->jwt = new JWT();
  }

  function is_valid($jwt) {
      
    $valid = $this->CI->jwt->decode($jwt,'','HS256');
    
    if(!is_null($valid)){
      return $valid;
    }
    return null;
  }
}
