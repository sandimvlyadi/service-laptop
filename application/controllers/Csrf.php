<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csrf extends CI_Controller {

	private $userData;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model', 'login');

		$this->userData = array(
			'session'	=> $this->session->userdata('userSession'),
			'host'		=> $this->input->get_request_header('Host', TRUE),
			'referer'	=> $this->input->get_request_header('Referer', TRUE),
			'agent'		=> $this->input->get_request_header('User-Agent', TRUE),
			'ipaddr'	=> $this->input->ip_address()
		);
	}

	public function index()
	{
    redirect('dashboard/');
	}

  public function get()
  {
    $data = array('result' => false);

    $auth = $this->login->auth($this->userData);
    if ($auth['result']) {
      $data = array(
        'result' => true,
  			'csrf'   => array(
  				'name' => $this->security->get_csrf_token_name(),
  				'hash' => $this->security->get_csrf_hash()
  			)
  		);
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
  }

}
