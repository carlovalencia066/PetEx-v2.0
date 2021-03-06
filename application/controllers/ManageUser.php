<?php

class ManageUser extends CI_Controller {

    function __construct() {
        parent::__construct();
        //---> SESSIONS HERE!

        $manageUserModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 1));
        if ($this->session->has_userdata('isloggedin') == FALSE) {
            //user is not yet logged in
            $this->session->set_flashdata("err_4", "Login First!");
            redirect(base_url() . 'main/');
        } else {
            $current_user = $this->session->userdata("current_user");
            if ($this->session->userdata("user_access") == "user") {
                //USER!
                $this->session->set_flashdata("err_5", "You are currently logged in as " . $current_user->user_firstname . " " . $current_user->user_lastname);
                redirect(base_url() . "UserDashboard");
            } else if ($this->session->userdata("user_access") == "subadmin") {
                //SUBADMIN!
                if (empty($manageUserModule)) {
                    $this->session->set_flashdata("err_5", "You have no access in Manage Users Module.");
                    redirect(base_url() . "SubadminDashboard");
                }
            } else if ($this->session->userdata("user_access") == "admin") {
                //ADMIN!
                //Do nothing!
            }
        }
    }

    public function index() {
        $manageUserModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 1));
        $manageOfficerModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 2));
        $petManagementModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 3));
        $scheduleModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 4));

        $current_user = $this->ManageUsers_model->get_users("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $data = array(
            /* MODULE ACCESS */
            'manageUserModule' => $manageUserModule,
            'manageOfficerModule' => $manageOfficerModule,
            'petManagementModule' => $petManagementModule,
            'scheduleModule' => $scheduleModule,
            //////////////////////////////
            'title' => "Manage Users",
            'users' => $this->ManageUsers_model->get_users("user"),
            'user_last_update' => $this->ManageUsers_model->get_recent_timestamp("user", NULL, "user_added_at"),
            //NAV INFO
            'user_name' => $current_user->admin_firstname . " " . $current_user->admin_lastname,
            'user_picture' => $current_user->admin_picture,
            'user_access' => "Administrator"
        );
        $this->load->view("dashboard/includes/header", $data);
        if ($current_user->admin_access == "Subadmin") {
            $this->load->view("subadmin_nav/navheader");
        } else {
            $this->load->view("admin_nav/navheader");
        }
        $this->load->view("manage_user/main");
        $this->load->view("dashboard/includes/footer");
    }

    public function activate_user_exec() {
        $this->session->set_userdata("activate_user", $this->uri->segment(3));
        redirect(base_url() . "ManageUser/activate_user");
    }

    public function activate_user() {
        $user = $this->ManageUsers_model->get_users("user", array("user_id" => $this->session->userdata("activate_user")))[0];
        if ($this->ManageUsers_model->activate_user("user", array("user_id" => $this->session->userdata("activate_user")))) {
            $this->SaveEventAdmin->trail($this->session->userdata("userid"), "Activated " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
            $this->session->set_flashdata("activation_success", "Successfully activated " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
        } else {
            $this->session->set_flashdata("activation_fail", "Something went wrong while activating " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
        }
        $this->session->unset_userdata("activate_user");
        redirect(base_url() . "ManageUser");
    }

    public function deactivate_user_exec() {
        $this->session->set_userdata("deactivate_user", $this->uri->segment(3));
        redirect(base_url() . "ManageUser/deactivate_user");
    }

    public function deactivate_user() {
        $user = $this->ManageUsers_model->get_users("user", array("user_id" => $this->session->userdata("deactivate_user")))[0];
        if ($this->ManageUsers_model->deactivate_user("user", array("user_id" => $this->session->userdata("deactivate_user")))) {
            $this->SaveEventAdmin->trail($this->session->userdata("userid"), "Deactivated " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
            $this->session->set_flashdata("activation_success", "Successfully deactivated " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
        } else {
            $this->session->set_flashdata("activation_fail", "Something went wrong while deactivating " . $user->user_firstname . " " . $user->user_lastname . "'s account.");
        }
        $this->session->unset_userdata("deactivate_user");
        redirect(base_url() . "ManageUser");
    }

    public function show_user_info_exec() {
        $this->session->set_userdata("show_user_info", $this->uri->segment(3));
        redirect(base_url() . "ManageUser/show_user_info");
    }

    public function show_user_info() {
        $manageUserModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 1));
        $manageOfficerModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 2));
        $petManagementModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 3));
        $scheduleModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 4));

        $selected_user = $this->ManageUsers_model->get_user_info("user", array("user_id" => $this->session->userdata("show_user_info")))[0];
        $user_transaction = $this->ManageUsers_model->get_user_transactions(array("transaction.user_id" => $this->session->userdata("show_user_info")));
        $user_pet = $this->ManageUsers_model->get_user_pets(array("adoption.user_id" => $this->session->userdata("show_user_info")));
        $user_activity = $this->ManageUsers_model->get_user_activities(array("event.user_id" => $this->session->userdata("show_user_info")));
        $current_user = $this->ManageUsers_model->get_users("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $data = array(
            /* MODULE ACCESS */
            'manageUserModule' => $manageUserModule,
            'manageOfficerModule' => $manageOfficerModule,
            'petManagementModule' => $petManagementModule,
            'scheduleModule' => $scheduleModule,
            //////////////////////////////
            "title" => $selected_user->user_firstname . " " . $selected_user->user_lastname . " | Information",
            "user" => $selected_user,
            "transactions" => $user_transaction,
            "pets" => $user_pet,
            "activities" => $user_activity,
            //NAV INFO
            'user_name' => $current_user->admin_firstname . " " . $current_user->admin_lastname,
            'user_picture' => $current_user->admin_picture,
            'user_access' => "Administrator"
        );
        $this->load->view("dashboard/includes/header", $data);
        if ($current_user->admin_access == "Subadmin") {
            $this->load->view("subadmin_nav/navheader");
        } else {
            $this->load->view("admin_nav/navheader");
        }
        $this->load->view("manage_user/show_user_information");
        $this->load->view("dashboard/includes/footer");
    }

}
