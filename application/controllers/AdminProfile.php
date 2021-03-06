<?php

class AdminProfile extends CI_Controller {

    function __construct() {
        parent::__construct();
        //---> HELPERS HERE!
        //---> LIBRARIES HERE!
        //---> SESSIONS HERE!
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
            } else if ($this->session->userdata("user_access") == "admin") {
                //ADMIN!
                // Do Nothing
            }
        }
    }

    function _alpha_dash_space($str = '') {
        if (!preg_match("/^([-a-z_ ])+$/i", $str)) {
            $this->form_validation->set_message('_alpha_dash_space', 'The {field} is not correct.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function index() {
        $manageUserModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 1));
        $manageOfficerModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 2));
        $petManagementModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 3));
        $scheduleModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 4));

        $userDetails = $this->Profile_model->fetch("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $current_user = $this->ManageUsers_model->get_users("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $data = array(
            /* MODULE ACCESS */
            'manageUserModule' => $manageUserModule,
            'manageOfficerModule' => $manageOfficerModule,
            'petManagementModule' => $petManagementModule,
            'scheduleModule' => $scheduleModule,
            //////////////////////////////
            'title' => "Profile | " . $current_user->admin_firstname . " " . $current_user->admin_lastname,
            'trails' => $this->AuditTrail_model->get_audit_trail("event", "admin", "event.admin_id = admin.admin_id", "user", "event.user_id = user.user_id", array("event_classification" => "trail", 'admin.admin_id' => $this->session->userid)),
            //NAV INFO
            'user_name' => $current_user->admin_firstname . " " . $current_user->admin_lastname,
            'user_picture' => $current_user->admin_picture,
            'user_access' => "Administrator",
            'userDetails' => $userDetails
        );
        $this->load->view("admin_profile/includes/header", $data);
        if ($current_user->admin_access == "Subadmin") {
            $this->load->view("subadmin_nav/navheader");
        } else {
            $this->load->view("admin_nav/navheader");
        }
        $this->load->view("admin_profile/main");
        $this->load->view("admin_profile/includes/footer");
    }

    public function edit_profile() {
        $manageUserModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 1));
        $manageOfficerModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 2));
        $petManagementModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 3));
        $scheduleModule = $this->AdminDashboard_model->fetch("module_access", array("admin_id" => $this->session->userdata("userid"), "module_id" => 4));

        $userDetails = $this->Profile_model->fetch("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $current_user = $this->ManageUsers_model->get_users("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $data = array(
            /* MODULE ACCESS */
            'manageUserModule' => $manageUserModule,
            'manageOfficerModule' => $manageOfficerModule,
            'petManagementModule' => $petManagementModule,
            'scheduleModule' => $scheduleModule,
            //////////////////////////////
            'title' => "Edit Profile | " . $current_user->admin_firstname . " " . $current_user->admin_lastname,
            //NAV INFO
            'user_name' => $current_user->admin_firstname . " " . $current_user->admin_lastname,
            'user_picture' => $current_user->admin_picture,
            'user_access' => "Administrator",
            'userDetails' => $userDetails
        );
        $this->load->view("admin_profile/includes/header", $data);
        if ($current_user->admin_access == "Subadmin") {
            $this->load->view("subadmin_nav/navheader");
        } else {
            $this->load->view("admin_nav/navheader");
        }
        $this->load->view("admin_profile/edit_profile");
        $this->load->view("admin_profile/includes/footer");
    }

    public function edit_picture_submit() {
        $userDetails = $this->Profile_model->fetch("admin", array("admin_id" => $this->session->userdata("userid")))[0];

        $config['upload_path'] = './images/user/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_ext_tolower'] = true;
        $config['max_size'] = 5120;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        if (!empty($_FILES["user_picture"]["name"])) {
            if ($this->upload->do_upload('user_picture')) {
                $imagePath = "images/user/" . $this->upload->data("file_name");
                if ($userDetails->admin_picture == "images/user/male.png" || $userDetails->admin_picture == "images/user/female.png") {
                    
                } else {
                    unlink($userDetails->admin_picture);
                }
            } else {
                echo $this->upload->display_errors();
                $this->session->set_flashdata("uploading_error", "Please make sure that the max size is 5MB the types may only be .jpg, .jpeg, .gif, .png");
            }
        } else {
            //DO METHOD WITHOUT PICTURE PROVIDED
            if ($userDetails->admin_picture == "images/user/male.png" || $userDetails->admin_picture == "images/user/female.png") {
                if ($this->input->post('admin_sex') == "Male") {
                    $imagePath = "images/user/male.png";
                } else {
                    $imagePath = "images/user/female.png";
                }
            } else {
                $imagePath = $userDetails->admin_picture;
            }
        }
        $data = array(
            'admin_picture' => $imagePath,
            'admin_updated_at' => time()
        );

        if ($this->Profile_model->update_admin_record($data, array("admin_id" => $userDetails->admin_id))) {
            $accountDetailsAdmin = $this->Login_model->getinfo("admin", array("admin_id" => $userDetails->admin_id))[0];
//            echo "<pre>";
//            print_r($accountDetailsAdmin);
//            echo "</pre>";
//            die;
            //SUCCESS
            $this->SaveEventAdmin->trail($this->session->userdata("userid"), $userDetails->admin_firstname . " change profile picture.");
            $this->session->set_flashdata("uploading_success", "Successfully update the image");
            $this->session->set_userdata('userid', $accountDetailsAdmin->admin_id);
            $this->session->set_userdata('current_user', $accountDetailsAdmin);
            if ($accountDetailsAdmin->admin_access == 'Admin') {
                $this->session->set_userdata('user_access', "admin");
            } else {
                $this->session->set_userdata('user_access', "subadmin");
            }
            redirect(base_url() . "AdminProfile/edit_profile");
        } else {
            
        }
        redirect(base_url() . "AdminProfile/");
    }

    public function edit_profile_submit() {
        $userDetails = $this->Profile_model->fetch("admin", array("admin_id" => $this->session->userdata("userid")))[0];
        $this->form_validation->set_rules('admin_firstname', "Firstname", "required|callback__alpha_dash_space|min_length[2]");
        $this->form_validation->set_rules('admin_lastname', "Lastname", "required|callback__alpha_dash_space|min_length[2]");
        $this->form_validation->set_rules('admin_address', "Address", "required");
        $this->form_validation->set_rules("admin_email", "Email", "required|valid_email");
        $this->form_validation->set_rules("admin_contact_no", "Mobile Phone No.", "required|numeric|regex_match[^(09|\+639)\d{9}$^]");
        if ($this->form_validation->run() == FALSE) {
            //ERROR IN FORM
            $this->edit_profile();
        } else {
            $data = array(
                'admin_firstname' => $this->input->post("admin_firstname"),
                'admin_lastname' => $this->input->post("admin_lastname"),
                'admin_sex' => $this->input->post("admin_sex"),
                'admin_bday' => strtotime($this->input->post('admin_bday')),
                'admin_address' => $this->input->post("admin_address"),
                'admin_contact_no' => $this->input->post("admin_contact_no"),
                'admin_email' => $this->input->post("admin_email"),
                'admin_updated_at' => time()
            );

            if ($this->Profile_model->update_admin_record($data, array("admin_id" => $userDetails->admin_id))) {

                $accountDetailsAdmin = $this->Login_model->getinfo("admin", array("admin_id" => $userDetails->admin_id))[0];

                //SUCCESS
                $this->SaveEventAdmin->trail($this->session->userdata("userid"), $userDetails->admin_firstname . " change account information.");
                $this->session->set_flashdata("uploading_success", "You have successfully changed your account information");
                $this->session->set_userdata('user_id', $accountDetailsAdmin->admin_id);
                $this->session->set_userdata('current_user', $accountDetailsAdmin);
                if ($accountDetailsAdmin->admin_access == 'Admin') {
                    $this->session->set_userdata('user_access', "admin");
                } else {
                    $this->session->set_userdata('user_access', "subadmin");
                }
                redirect(base_url() . "AdminProfile/edit_profile");
            } else {
                $this->session->set_flashdata("uploading_fail", $userDetails->admin_lastname . " seems to not exist in the database.");
            }
            redirect(base_url() . "AdminProfile/");
        }
    }

}
