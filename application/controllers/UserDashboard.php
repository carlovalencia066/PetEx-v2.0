<?php

class UserDashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        //---> MODELS HERE!
        $this->load->model('UserDashboard_model');
        //---> LIBRARIES HERE!
        $this->load->helper('download');
        //---> SESSIONS HERE!
        if ($this->session->has_userdata('isloggedin') == FALSE) {
            //user is not yet logged in
            $this->session->set_flashdata("err_4", "Login First!");
            redirect(base_url() . 'main/');
        } else {
            $current_user = $this->session->userdata("current_user");
            if ($this->session->userdata("user_access") == "user") {
                //USER!
                //Do nothing
            } else if ($this->session->userdata("user_access") == "subadmin") {
                //SUBADMIN!
                $this->session->set_flashdata("err_5", "You are currently logged in as " . $current_user->user_firstname . " " . $current_user->user_lastname);
                redirect(base_url() . "SubadminDashboard");
            } else if ($this->session->userdata("user_access") == "admin") {
                //ADMIN!
                $this->session->set_flashdata("err_5", "You are currently logged in as " . $current_user->admin_firstname . " " . $current_user->admin_lastname);
                redirect(base_url() . "AdminDashboard");
            }
        }
    }

    public function index() {
        $allPets = $this->UserDashboard_model->fetchPetDesc("pet");
        $allAdopted = $this->UserDashboard_model->get_adopted();

        $myAdopted = $this->UserDashboard_model->fetchJoinThreeAdoptedDesc("adoption", "pet", "adoption.pet_id = pet.pet_id", "user", "adoption.user_id = user.user_id", array("user.user_id" => $this->session->userdata("userid")));

        $myPets = $this->UserDashboard_model->fetchJoinThreeAdoptedDesc("adoption", "pet", "adoption.pet_id = pet.pet_id", "user", "adoption.user_id = user.user_id", array("user.user_id" => $this->session->userdata("userid")))[0];
        $petAdopters = $this->UserDashboard_model->fetchJoinThreeProgressDesc();
        $current_user = $this->ManageUsers_model->get_users("user", array("user_id" => $this->session->userdata("userid")))[0];
        $userInfo = $this->UserDashboard_model->fetchJoinProgress(array('transaction.user_id' => $this->session->userid));
//        echo "<pre>";
//        print_r($petAdopters);
//        echo "</pre>";
//        die;

        if (!empty($myAdopted)) {
            $checker = 0;

            $data = array(
                'title' => "Dashboard | " . $current_user->user_firstname . " " . $current_user->user_lastname,
                //NAV INFO
                'user_name' => $current_user->user_firstname . " " . $current_user->user_lastname,
                'user_picture' => $current_user->user_picture,
                'pets' => $allPets,
                'user_access' => "User",
                'checker' => $checker,
                'adoptedPets' => $myAdopted,
                'myAdopted' => $myAdopted,
                'myPets' => $myPets,
                'userInfo' => $userInfo
            );
        } else {
            $checker = 1;

            $data = array(
                'title' => "Dashboard | " . $current_user->user_firstname . " " . $current_user->user_lastname,
                //NAV INFO
                'user_name' => $current_user->user_firstname . " " . $current_user->user_lastname,
                'user_picture' => $current_user->user_picture,
                'pets' => $allPets,
                'checker' => $checker,
                'user_access' => "User",
                'adoptedPets' => $allAdopted,
                'userInfo' => $userInfo
            );
        }
        $this->load->view("userdashboard/includes/header", $data);
        $this->load->view("user_nav/navheader");
        $this->load->view("userdashboard/main");
        $this->load->view("userdashboard/includes/footer");
    }

    public function messageRead() {
        $data = array(
            'adoption_isRead' => 1,
        );
        if ($this->UserDashboard_model->update_adoption($data, array("user_id" => $this->session->userdata("userid")))) {
            redirect(base_url() . "MyPets/");
        }
    }

}
