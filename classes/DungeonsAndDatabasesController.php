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
          case "inventories":
            $this->inventories();
            break;
          case "get_user_parties":
            $this->get_user_parties();
            break;
          case "add_user_to_party":
            $this->add_user_to_party();
            break;
          case "delete_user_from_party":
            $this->delete_user_from_party();
            break;
          case "delete_party":
            $this->delete_party();
            break;
          case "set_party":
            $this->set_party();
            break;
          case "get_characters":
            $this->get_characters();
            break;
          case "add_character":
            $this->add_character();
            break;
          case "delete_char_from_party":
            $this->delete_char_from_party();
            break;
          case "set_inventory":
            $this->set_inventory();
            break;
          case "get_items":
            $this->get_items();
            break;
          case "delete_item_from_inventory":
            $this->delete_item_from_inventory();
            break;
          case "get_inventories_in_inventory":
            $this->get_inventories_in_inventory();
            break;
          case "get_inventory_info":
            $this->get_inventory_info();
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

    public function inventories() {
        // set user information for the page
        $user = [
            "email" => $_SESSION["email"],
            "username" => $_SESSION["username"]
        ];

        $page_info = [
            "party_id" => $_SESSION["party_id"],
            "party_name" => $_SESSION["party_name"],
            "inventory_id" => $_SESSION["inventory_id"]
        ];

        include("templates/inventories.php");
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
      $_SESSION["party_id"]  = $_POST["party_id"];
      $_SESSION["party_name"] = $_POST["party_name"];
    }

    public function delete_user_from_party(){
      $character_info = $this->db->query("CALL remove_user_from_party(?, ?)", "si", $_POST["email"], $_POST["party_id"]);
    }

    public function delete_party(){
      $character_info = $this->db->query("CALL delete_party(?)", "i", $_POST["party_id"]);
    }

    //CHARACTERS PAGE
    public function get_characters(){
      $character_info = $this->db->query("CALL characters_from_party_id(?)", "i", $_SESSION["party_id"]);
      $data = array();
      $data["party_id"] = $_SESSION["party_id"];
      $data["characters"] = $character_info;
      echo json_encode($data);
    }

    public function add_character(){
      echo $_POST["character_name"];
      echo $_POST["maximum_capacity"];
      echo $_SESSION["party_id"];
      $character_info = $this->db->query("CALL create_character(?, ?, ?)", "sii", $_POST["character_name"], $_POST["maximum_capacity"], $_SESSION["party_id"]);
    }

    public function delete_char_from_party(){
      $this->db->query("CALL remove_character_from_party(?, ?)", "ii", $_POST["character_id"], $_POST["party_id"]);
    }

    public function set_inventory(){
      $_SESSION["inventory_id"]  = $_POST["inventory_id"];
    }

    public function get_items(){
      $items = $this->db->query("CALL items_from_inventory(?)", "i", $_SESSION["inventory_id"]);
      echo json_encode($items);
    }

    public function delete_item_from_inventory(){
      $this->db->query("CALL remove_item_from_inventory(?, ?, ?)", "sii", $_POST["name"], $_POST["party_id"], $_SESSION["inventory_id"]);
    }

    public function get_inventories_in_inventory(){
      $inventories = $this->db->query("CALL inventories_from_inventory(?)", "i", $_SESSION["inventory_id"]);
      echo json_encode($inventories);
    }

    public function get_inventory_info(){
      $inventories = $this->db->query("CALL get_inventory(?)", "i", $_SESSION["inventory_id"]);
      echo json_encode($inventories[0]);
    }
}
