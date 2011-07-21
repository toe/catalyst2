<?php

class Install extends MX_Controller 
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index(){
    	$this->load->model("Page");
    	echo "inside install";
    	
    }
}

/* End of file install.php */
/* Location: ./application/modules/install/controllers/install.php */