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
          case "parties":
            $this->parties();
            break;
          case "characters":
            $this->characters();
            break;
          case "get_user_parties":
            $this->get_user_parties();
            break;
          case "add_user_to_party":
            $this->add_user_to_party();
            break;
          case "set_party":
            $this->set_party();
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
                  header("Location: {$this->url}/parties");
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
                header("Location: {$this->url}/parties");
                return;
            }

        }

        include "templates/login.php";
    }

    public function parties() {
        // set user information for the page
        $user = [
            "email" => $_SESSION["email"],
            "username" => $_SESSION["username"]
        ];

        include("templates/parties.php");
    }

    public function characters() {
        // set user information for the page
        $user = [
            "email" => $_SESSION["email"],
            "username" => $_SESSION["username"]
        ];

        $page_info = [
            "party_id" => $_SESSION["party_id"],
            "party_name" => $_SESSION["party_name"]
        ];

        include("templates/characters.php");
    }

    public function get_user_parties(){
      $party_ids = $this->db->query("CALL party_ids_from_email(?)", "s", $_SESSION["email"]);

      $results = array();
      foreach($party_ids as $id_pair){
        $users = $this->db->query("CALL users_of_party(?)", "i", $id_pair["party_id"]);
        $name = $this->db->query("CALL parties_with_id(?)", "i", $id_pair["party_id"]);
        $results[$id_pair["party_id"]] = array();
        $results[$id_pair["party_id"]]["users"] = $users;
        $results[$id_pair["party_id"]]["party_name"] = $name[0]["party_name"];
      }
      echo json_encode($results);
    }

    public function add_user_to_party(){
      $data = $this->db->query("select username, email from user where email = ?;", "s", $_POST["email"]);
      if (empty($data)) {
        echo json_encode(array());
        return;
      }
      $check_doesnt_exist = $this->db->query("select email from PlaysIn where email = ? AND party_id = ?;", "si", $_POST["email"], $_POST["party_id"]);
      if (!empty($check_doesnt_exist)) {
        echo json_encode(array());
        return;
      }
      $this->db->query("CALL give_access(?, ?)", "si", $_POST["email"], $_POST["party_id"]);
      echo json_encode($data);
    }

    public function set_party(){
      $_SESSION["party_id"] = $_POST["party_id"];
      $_SESSION["party_name"] = $_POST["party_name"];
    }
}
