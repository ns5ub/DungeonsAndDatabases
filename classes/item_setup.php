<?php
    include("database_credentials.php"); // define variables

    /** SETUP **/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($dbserver, $dbuser, $dbpass, $dbdatabase);

    //DROP INVENTORY?
    $db->query("drop table if exists item;");
    $db->query("create table item (
                item_name VARCHAR(100),
                party_id INT,
                description VARCHAR (7500),
                rarity VARCHAR(200),
                equipment_category VARCHAR(100),
                weight FLOAT,
                magical BOOLEAN,
                attunement VARCHAR(100),
                PRIMARY KEY (item_name, party_id),
                FOREIGN KEY (party_id) REFERENCES party(party_id)
                );");
    
    $stmt = $db->prepare("insert into item (item_name, party_id, description, rarity, equipment_category, weight, magical, attunement) values (?,?,?,?,?,?,?,?);");

    //Generate Magic Items
    $default_party_id = 1;
    $default_is_magical = 1;

    $next = "https://api.open5e.com/magicitems/";
    while($next != null){
      $data = json_decode(file_get_contents($next), true);
      $next = $data["next"];
      $results = $data["results"];

      foreach($results as $item){
        print_r($item);
        $slug = $item["slug"];
        if($slug === "wand-of-wonder"){
          continue;
        }
        $weight = 1;
        $stmt->bind_param("sisssdis", $item["name"], $default_party_id, $item["desc"], $item["rarity"],  $item["type"], $weight, $default_is_magical, $item["requires_attunement"]);
        if (!$stmt->execute()) {
            echo "Could not add question: {$item["name"]}\n";
        }
      }

    }


    $url = "https://www.dnd5eapi.co";
    $data = json_decode(file_get_contents($url . "/api/equipment/"), true);
    //print_r($data);

    $default_party_id = 1;
    $default_is_magical = 0;
    $default_rarity = "mundane";
    $default_attunement = "";
    foreach($data["results"] as $item){
      $item_info = json_decode(file_get_contents($url . $item["url"]), true);
      print_r($item_info);
      $weight = $item_info["weight"];

      $type = $item_info["equipment_category"]["name"];
      if(array_key_exists("armor_category",$item_info)){
        $type = $type .", ". $item_info["armor_category"];
      }
      if(array_key_exists("gear_category",$item_info)){
        $type = $type .", ". $item_info["gear_category"]["name"];
      }

      $desc = "";
      if(array_key_exists("desc",$item_info)){
        $desc = $item_info["desc"];
        if(is_array($desc)){
          $desc = implode("<br>", $item_info["desc"]);
        }
      }

      $stmt->bind_param("sisssdis", $item_info["name"], $default_party_id, $desc, $default_rarity, $type, $weight, $default_is_magical, $default_attunement);
      if (!$stmt->execute()) {
          echo "Could not add question: {$item["name"]}\n";
      }
    }



    //Adding Coins
    $name = "gp";
    $default_party_id = 1;
    $default_is_magical = 0;
    $default_rarity = "mundane";
    $default_attunement = "";

    $default_type = "Coin";
    $default_weight = 0.02;
    $default_desc = "";

    $stmt->bind_param("sisssdis", $name, $default_party_id, $default_desc, $default_rarity, $default_type, $default_weight, $default_is_magical, $default_attunement);
    if (!$stmt->execute()) {
        echo "Could not add gp";
    }

    $name = "sp";
    $stmt->bind_param("sisssdis", $name, $default_party_id, $default_desc, $default_rarity, $default_type, $default_weight, $default_is_magical, $default_attunement);
    if (!$stmt->execute()) {
        echo "Could not add sp";
    }

    $name = "cp";
    $stmt->bind_param("sisssdis", $name, $default_party_id, $default_desc, $default_rarity, $default_type, $default_weight, $default_is_magical, $default_attunement);
    if (!$stmt->execute()) {
        echo "Could not add cp";
    }

    $name = "pp";
    $stmt->bind_param("sisssdis", $name, $default_party_id, $default_desc, $default_rarity, $default_type, $default_weight, $default_is_magical, $default_attunement);
    if (!$stmt->execute()) {
        echo "Could not add pp";
    }
