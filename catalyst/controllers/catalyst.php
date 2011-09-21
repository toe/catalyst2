<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalyst extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if(!$this->isInstalled()){
			redirect('install');
		}else{
			redirect('login');
		}
	} // end public function index
	
	/**
	*	isInstalled will let the user know if catalyst is installed or not. 
	* To check to see if Catalyst has been installed look at the database
	* config file and see if it has been setup.
	* 
	*	@params - none
	*
	* @return - true or false
	*/
	private function isInstalled(){
		$content = read_file('config/database.php');
		
		$iPos = strpos($content, '{HOSTNAME}');
		return ($iPos) ? false : true ;		
	} // end private function isInstalled()
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */