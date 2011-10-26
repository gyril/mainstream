<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accueil extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/accueil/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();
//		if ((strpos($_SERVER['HTTP_HOST'],'www.')===false)) { header('Location: ' . base_url() . $_SERVER['REQUEST_URI']); exit(); }
		
		$this->load->model('user_model');
	}

	
	private function _check_cookie() {
		if(!$this->input->cookie('permanent', true))
			return false;

		if($this->user_model->autoLogin($this->input->cookie('permanent', true)))
			return true;
	}
	
	public function index()
	{
		if($this->user_model->isLogged() || $this->_check_cookie())
			redirect('timeline');
			
		$headers['title'] = "MaiNsTream - MNT only";
		$this->load->view('head', $headers);
		$this->load->view('accueil');
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */