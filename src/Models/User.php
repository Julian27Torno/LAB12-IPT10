<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class User extends BaseModel
{
    public function verifyLogin($email, $password)
{
    $sql = "SELECT id, password FROM users WHERE email_address = :email";
    $statement = $this->db->prepare($sql);
    $statement->execute(['email' => $email]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user['id']; // Return user ID on successful login
    }
    return false;
}



    public function save($data)
    {
        $sql = "INSERT INTO users 
                SET
                    username = :username,
                    first_name = :first_name,
                    last_name = :last_name,
                    email_address = :email,
                    `password` = :password_hash";        
        $statement = $this->db->prepare($sql);
        $password_hash = $this->hashPassword($data['password']);
        $statement->execute([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => $password_hash
        ]);
    
        // Return the last inserted ID
        return $this->db->lastInsertId();
    }

    protected function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyAccess($email, $password)
    {
        $sql = "SELECT `password` FROM users WHERE email_address = :email";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'email' => $email
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }

        // Verify the hashed password
        return password_verify($password, $result['password']);
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
