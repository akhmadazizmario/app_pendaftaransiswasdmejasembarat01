<?php
class Fungsi {
  protected $ci;

  //FUNGSI

  public function __construct()
  {
    $this->ci=& get_instance();
  }

  function user_login(){
    $this->ci->load->model('M_user');
    $userid = $this->ci->session->userdata('id_user');
    $user_data = $this->ci->M_user->get($userid)->row();
    return $user_data;
  }
}
