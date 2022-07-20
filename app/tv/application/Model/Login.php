<?php

namespace Mini\Model;

use Mini\Core\Model;
use Mini\Libs\Helper;

class Login extends Model
{

    public function Logged($id){
        $sql = "UPDATE users SET login =  1 WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':id' => $id,
        );

        $query->execute($parameters);
    }


    public function login($username, $password)
    {

        $sql = "SELECT users.*, locations.name FROM users, locations WHERE username = :username AND  password = :password LIMIT 1 ";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':username' => $username,
            ':password' => $password
        );
        $query->execute($parameters);
      //  echo Helper::debugPDO($sql, $parameters); exit;
        return ($query->rowcount() ? $query->fetch() : false);
    }



}
