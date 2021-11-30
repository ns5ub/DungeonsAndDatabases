<?php

class DungeonsAndDatabasesController {

    private $db;
    //private $url = "/ns5ub/DungeonsAndDatabases";
    private $url = "/DungeonsAndDatabases";

    public function __construct() {
        $this->db = new Database();
    }

    public function run($parts) {
        $command = $parts[0];
        switch($command) {
            case "logout":
                $this->destroySession();
            case "login":
            default:
                $this->login();
                break;
        }

    }

    private function destroySession() {
        session_destroy();

        session_start();
    }


    public function login() {
        $error_msg = "";
        if (isset($_POST["email"])) { /// validate the email coming in
            $data = $this->db->query("select * from user where email = ?;", "s", $_POST["email"]);
            if ($data === false) {
                $error_msg = "Error checking for user";
            } else if (!empty($data)) {
                // user was found!
                // validate the user's password
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    $_SESSION["email"] = $data[0]["email"];
                    $_SESSION["user_id"] = $data[0]["user_id"];
                    header("Location: {$this->url}/comics");
                    return;
                } else {
                    $error_msg = "Invalid Password";
                }
            } else {
                $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $insert = $this->db->query("insert into user (email, password) values (?, ?);", "ss", $_POST["email"], $hash);
                if ($insert === false) {
                    $error_msg = "Error creating new user";
                }

                $_SESSION["email"] = $_POST["email"];

                $data = $this->db->query("select * from user where email = ?;", "s", $_POST["email"]);
                $_SESSION["user_id"] = $data[0]["user_id"];

                header("Location: {$this->url}/comics");
                return;
            }

        }

        include "templates/login.php";
    }
}
