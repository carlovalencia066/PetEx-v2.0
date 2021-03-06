<?php

class Reset extends CI_Controller {

    function __construct() {
        parent::__construct();
        //---> MODELS HERE!
        $this->load->model('reset_model');
        //---> LIBRARIES HERE!
        $this->load->library('email');
        //---> SESSIONS HERE!
    }

    //---> CALLBACKS HERE!
    public function _email_check($email) {
        $result = $this->reset_model->emailAvailability($email);

        if (!$result) {
            $this->form_validation->set_message('_email_check', 'The %s is not existing');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _username_check($username) {
        $result = $this->reset_model->usernameAvailability($username);
        if (!$result) {
            $this->form_validation->set_message('_username_check', 'The %s is not existing');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _email_check_admin($email) {
        $result = $this->reset_model->emailAvailabilityAdmin($email);

        if (!$result) {
            $this->form_validation->set_message('_email_check_admin', 'The %s is not existing');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _username_check_admin($username) {
        $result = $this->reset_model->usernameAvailabilityAdmin($username);
        if (!$result) {
            $this->form_validation->set_message('_username_check_admin', 'The %s is not existing');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sendEmailReset($user) {

        $this->email->from("codebusters.solutions@gmail.com", 'Reset Password');
        $this->email->to($user['email']);
        $this->email->subject('Reset Password');
        $data = array('name' => $user['username']);
        $this->email->message($this->load->view('reset/reset_message', $data, true));

        if (!$this->email->send()) {
            echo $this->email->print_debugger();
        } else {
            
        }
    }

    public function index() {
        $data = array(
            'title' => 'Pet Ex | Reset Password',
            'wholeUrl' => base_url(uri_string())
        );
        $this->load->view("reset/includes/header", $data);
        $this->load->view("reset/reset");
        $this->load->view("reset/includes/footer");
    }

    public function resetPass_exec() {
        $user = $this->Reset_model->fetch("user", array("user_username" => $this->input->post("username")));
//        echo "<pre>";
//        print_r($user);
//        echo "</pre>";
//        die;
        if (!empty($user)) {
            die;
            $this->form_validation->set_rules('username', "Username ", "required|min_length[5]|strip_tags|callback__username_check");
            $this->form_validation->set_rules('email', "Email Address ", "required|valid_email|strip_tags|callback__email_check");
        } else {
            $this->form_validation->set_rules('username', "Username ", "required|min_length[5]|strip_tags|callback__username_check_admin");
            $this->form_validation->set_rules('email', "Email Address ", "required|valid_email|strip_tags|callback__email_check_admin");
        }
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Pet Ex | Reset Password'
            );
            $this->load->view("reset/includes/header", $data);
            $this->load->view("reset/reset");
            $this->load->view("reset/includes/footer");
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            if (!empty($user)) {
                $userUsername = $this->reset_model->fetch("user", array("user_username" => $username, "user_email" => $email));
            } else {
                $userUsername = $this->reset_model->fetch("admin", array("admin_username" => $username, "admin_email" => $email));
            }
            if (!$userUsername) {
                echo "<script>alert('Username and Email Address mismatch');"
                . "window.location='" . base_url() . "reset/'</script>";
            } else {
                $data = array(
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                );
                $this->sendEmailReset($data);
                echo "<script>alert('Please verify your account to your email address');"
                . "window.location='" . base_url() . "main/'</script>";
                //}
            }
        }
    }

    public function enter_newPass_exec() {
        $this->session->set_userdata("username", $this->uri->segment(3));
        redirect(base_url() . "Reset/enter_newPass");
    }

    public function enter_newPass() {

        $data = array(
            'title' => 'Pet Ex | Enter New Password',
            'wholeUrl' => base_url(uri_string()),
        );
        $this->load->view("reset/includes/header", $data);
        $this->load->view("reset/enter_newPass");
        $this->load->view("reset/includes/footer");
    }

    public function enter_newPass_submit() {
        $username = $this->session->userdata("username");
        $user = $this->Reset_model->fetch("user", array("user_username" => $username));
        $this->form_validation->set_rules('password', "Password ", "required|matches[conpass]|alpha_numeric|min_length[8]|strip_tags");
        $this->form_validation->set_rules('conpass', "Confirm Password ", "required|matches[password]|alpha_numeric|min_length[8]|strip_tags");
        if ($this->form_validation->run() == FALSE) {
            $this->enter_newPass();
        } else {
            if (!empty($user)) {
                $data = array(
                    'user_password' => sha1($this->input->post("password"))
                );
                if ($this->Profile_model->update_user_record($data, array("user_username" => $username))) {
                    echo "<script>alert('Your password has been changed.');"
                    . "window.location='" . base_url() . "main/'</script>";
                }
            } else {
                $data = array(
                    'admin_password' => sha1($this->input->post("password"))
                );
                if ($this->Profile_model->update_admin_record($data, array("admin_username" => $username))) {
                    echo "<script>alert('Your password has been changed.');"
                    . "window.location='" . base_url() . "main/'</script>";
                }
            }
        }
    }

}
