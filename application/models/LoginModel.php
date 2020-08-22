<?php
date_default_timezone_set('Asia/Calcutta');
class LoginModel extends CI_Model {
     

    public function checkLogin($email, $password) {
        //query the table 'users' and get the result count
        $this->db->where('username', $email);
        $this->db->where('password', $password);
        $query = $this->db->get('tblusers')->row();
        if($query)
        {
        	$userdetail=[
						'id'=>$query->user_id,
						'name'=>$query->name
					];
					$this->session->set_userdata($userdetail);
					return true; //$q->row()->login_id;
        }
        else{
        	return false;
        }
    }

}
