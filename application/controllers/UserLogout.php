<?php

class UserLogout extends CI_Controller {

    function __construct() {
        parent::__construct();
        //---> MODELS HERE!
        //---> LIBRARIES HERE!
        //---> SESSIONS HERE!
    }

    public function index() {
        $this->SaveEventUser->logout($this->session->userdata("userid"));
        $this->session->sess_destroy();
        redirect(base_url() . 'main/');
    }

}
