<?php

namespace App\Controllers;

use App\Models\User;
use PDO;
use App\Validator;

class Auth
{
    /**
     * @return void
     */
    public static function create()
    {
        $data = json_decode(file_get_contents("php://input"));
        $errors = Validator::validateLogin($data);
        if (count($errors) > 0) {
            echo json_encode(array("error" => json_encode($errors)));
        }else{
            $user = new User();
            $user->username = $data->username;
            $user->password = $data->password;
            $user->email = $data->email;
            $result = $user->create();
            if ($result) {
                echo json_encode(array("message" => "User created successfully."));
            }else{
                echo json_encode(array("message" => "Unable to create user."));
            }
        }
    }
    
    /**
     * @return void
     */
    public static function login()
    {
        $data = json_decode(file_get_contents("php://input"));
        $errors = Validator::validateLogin($data);
        if (count($errors) > 0) {
            echo json_encode(array("error" => json_encode($errors)));
        }else{
            $user = new User();
            $user->username = $data->username;
            $user->password = $data->password;
            $result = $user->login();
            if ($result) {
                echo json_encode(array(
                    "message" => "User logged in successfully.",
                    "username" => "$user->username",
                    "email" => "$user->email",
                    "isLoggedIn" => true,
                    "password" => "$user->password",
                    "id" => "$user->id"
                ));
        
                setcookie('username', $user->username, time() + (86400 * 30));
        
            } else {
                echo json_encode(array("error" => "Login or password is incorrect."));
            }
        }
    }
    
    /**
     * @return void
     */
    public static function logout()
    {
        $data = json_decode(file_get_contents("php://input"));
        $user = new User();
        $user->id = $data->userId;
        $user->logout();
        setcookie('username', '', time() - 3600);
        echo json_encode(array("message" => "User logged out successfully."));
    }
    
    /**
     * @return void
     */
    public static function getAll()
    {
        $user = new User();
        $result = $user->getAll();
        $num = $result->rowCount();
        
        if ($num > 0) {
            // users array
            $users_arr = array();
            $users_arr["users"] = array();
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                $user_item = array(
                    "id" => $id,
                    "username" => $username,
                    "password" => $password,
                    "email" => $email,
                    "isLoggedIn" => $isLoggedIn
                );
                
                array_push($users_arr["users"], $user_item);
                
            }
            
            // set response code - 200 OK
            http_response_code(200);
            // show users data in json format
            echo json_encode($users_arr);
        } else {
            echo json_encode(array("message" => "No users found."));
        }
    }
    
    /**
     * @return void
     */
    public static function update()
    {
        $data = json_decode(file_get_contents("php://input"));
        $user = new User();
        $user->id = $data->userId;
        $user->username = $data->username;
        $user->password = $data->password;
        $user->email = $data->email;
        $user->isLoggedIn = true;
        $result = $user->update();
        if ($result) {
            echo json_encode(array(
                "message" => "User logged in successfully.",
                "username" => "$user->username",
                "email" => "$user->email",
                "isLoggedIn" => true,
                "password" => "$user->password",
                "id" => "$user->id"
            ));
        }else{
            echo json_encode(array("error" => "User not updated."));
        }
    }
}