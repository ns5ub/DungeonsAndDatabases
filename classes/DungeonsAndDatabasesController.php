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
          case "MyParties":
            $this->MyParties();
            break;
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

        //POST --> CHECK EMAIL
        if (isset($_POST["email"])) {
            $data = $this->db->query("select * from user where email = ?;", "s", $_POST["email"]);
            if ($data === false) {
              $error_msg = "Error checking for user";
            }
            else if (!empty($data)) {
              // user was found - Validate their password!
              if (password_verify($_POST["password"], $data[0]["password"])) {
                  $_SESSION["email"] = $data[0]["email"];
                  $_SESSION["username"] = $data[0]["username"];
                  header("Location: {$this->url}/MyParties");
                  return;
              } else {
                  $error_msg = "Invalid Password";
              }
            }
            else {
              $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
              $insert = $this->db->query("insert into user (email, username, password) values (?, ?, ?);", "sss", $_POST["email"], $_POST["username"], $hash);
                if ($insert === false) {
                    $error_msg = "Error creating new user";
                }

                $_SESSION["email"] = $_POST["email"];
                $_SESSION["username"] = $_POST["username"];
                header("Location: {$this->url}/MyParties");
                return;
            }

        }

        include "templates/login.php";
    }

    public function MyParties() {
        // set user information for the page
        $user = [
            "email" => $_SESSION["email"],
            "username" => $_SESSION["username"]
        ];

        include("templates/MyParties.php");
    }
}
