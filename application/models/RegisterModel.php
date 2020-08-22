<?php
date_default_timezone_set('Asia/Calcutta');
class RegisterModel extends CI_Model{
    public function add_user($data){
        //get the data from controller and insert into the table 'users'
        return $this->db->insert('tblusers', $data);
    }
}
