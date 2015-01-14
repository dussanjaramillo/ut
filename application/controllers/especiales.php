<?php

class Especiales extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('especiales_model');
    }
    
    function index() {
        redirect(base_url() . 'index.php/especiales/listar');
    }
    
    function listar(){
        echo "YA";
        
    }

}
?>