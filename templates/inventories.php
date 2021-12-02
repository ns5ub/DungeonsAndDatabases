<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

    <title>Dungeons And Databases: My Inventories</title>

    <style>
      .scrolling{
        max-height: 100px;
        margin-bottom: 10px;
        overflow: scroll;
        -webkit-overflow-scrolling: touch;
      }
    </style>

    <!-- 3. link bootstrap -->
    <!-- if you choose to use CDN for CSS bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>
    <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- include your CSS by including your CSS last,
    anything you write may override (depending on specificity) the Bootstrap CSS -->
    <link rel="stylesheet" href="styles/main.css" />

    <script type="text/javascript">

        var inventory_items = null;
        var inventory_id = null;

        $(document).ready(function(){
          display_items();
          display_subinventories();
          display_inventory_information();

          $('button#addInventorySubmit').click(function(){
            form_data = $('form#addInventoryForm').serializeArray();
            console.log(form_data);

            if(form_data[0]["value"].length > 0 && !isNaN(form_data[1]["value"])){
              if(form_data[2]["value"] === ''){
                form_data[2]["value"] = '-1';
              }
              $.post("<?=$this->url?>/add_inventory_to_inventory", form_data, function(response){ location.reload(); });
            }

          });

        });

        function Item(name, party_id, is_magical, rarity, attunement, equipment_category, weight, description, quantity) {
            this.name = name;
            this.party_id = party_id;
            this.is_magical = is_magical;
            this.rarity = rarity;
            this.attunement = attunement;
            this.equipment_category = equipment_category;
            this.weight = weight;
            this.description = description;
            this.quantity = quantity;
        }

        function display_subinventories(){
          var found_inventories = null;
          $.post("<?=$this->url?>/get_inventories_in_inventory", function(response) {
            console.log(response);
            found_inventories = JSON.parse(response);


            var subinventories_table = document.getElementById("subinventories_table");
            if (found_inventories != null  && found_inventories.length != 0) {
              for (var num in found_inventories) {
                  inventory = found_inventories[num];
                  var newRow = subinventories_table.insertRow(subinventories_table.rows.length);
                  newRow.id = inventory.inventory_id_inner_bag;
                  newRow.insertCell(0).textContent = inventory.inventory_name;

                  newRow.addEventListener("mouseover", function() {
                    subinventories_table.clickedRow = this.rowIndex;
                  });

                  newRow.addEventListener("click", function() {
                    var subinventories_table = document.getElementById("subinventories_table");
                    var inventory_id = subinventories_table.rows.item(subinventories_table.clickedRow).id;
                    $.post("<?=$this->url?>/set_inventory", { "inventory_id" : inventory_id});
                    location.reload();
                  });
              }
            }
          });
        }

        function display_inventory_information(){
          $.post("<?=$this->url?>/get_inventory_info", function(response) {
            json_response = JSON.parse(response);
            console.log(json_response);
            document.getElementById("inventory_name").innerHTML = json_response.inventory_name;
            document.getElementById("max_weight").innerHTML = "Maximum Weight: " + json_response.maximum_capacity;
            if(json_response.fixed_current_weight != -1){
              document.getElementById("fixed_weight").text = "(But Always: " + json_response.fixed_current_weight + ")";
            }
          });
        }

        function display_items() {
            var found_items = null;

            $.post("<?=$this->url?>/get_items", function(response) {
              found_items = JSON.parse(response);
              //console.log(found_items);
              if (found_items != null && found_items.length != 0) {
                inventory_items = {};
                inventory_id = found_items[0].inventory_id;

                for (var num in found_items) {
                    i = found_items[num];
                    //console.log(i);
                    var i_i = i.item_name + "_" + i.party_id;
                    inventory_items[i_i] = new Item(i.item_name, i.party_id, i.is_magical, i.rarity, i.attunement, i.equipment_category, i.weight, i.description, i.item_quantity);
                    addToItemTable(i.item_name, i.party_id, i.is_magical, i.rarity, i.attunement, i.equipment_category, i.weight, i.description, i.item_quantity);
                }

                console.log(inventory_items);
                console.log(inventory_id);
              }
            });
        }

        function addToItemTable(name, party_id, is_magical, rarity, attunement, equipment_category, weight, description, quantity) {
            var table = document.getElementById("items_table");
            var newRow = table.insertRow(table.rows.length);
            //newRow.insertCell(0).textContent = quantity;
            var q_id = "quantity_" + name + "_" + party_id;
            var temp = '<input type="number" style="width: 75px" id="' + q_id + '" value="' + quantity + '" step="1">';
            temp = temp + '<button class="btn" onclick="quantity_change()"><i class="fas fa-check"></i></button>';
            newRow.insertCell(0).innerHTML = temp;
            newRow.insertCell(1).textContent = name;
            newRow.insertCell(2).textContent = equipment_category;
            var full_rarity = rarity;
            if(attunement !== ""){
              full_rarity = full_rarity + "(" + attunement + ")";
            }
            newRow.insertCell(3).textContent = full_rarity;
            newRow.insertCell(4).textContent = weight;
            newRow.insertCell(5).innerHTML = '<p class="scrolling">' + description + '</p>';
            newRow.insertCell(6).innerHTML = '<button class="btn btn-close" onclick="delete_item()"></button>';
            newRow.id = name + "_" + party_id;

            newRow.addEventListener("mouseover", function() {
                table.clickedRow = this.rowIndex;
            });
        }

        function delete_item() {
            var table = document.getElementById("items_table");
            var delRow = table.clickedRow;
            var item_id = table.rows.item(table.clickedRow).id;

            //delete table[table.rows.item(table.clickedRow).id];
            $.post("<?=$this->url?>/delete_item_from_inventory", { "name" : inventory_items[item_id].name, "party_id": inventory_items[item_id].party_id});
            table.deleteRow(delRow);
            delete inventory_items[item_id];
            //console.log(inventory_items);
        }

        function quantity_change(){
          var table = document.getElementById("items_table");
          var delRow = table.clickedRow;
          var item_id = table.rows.item(table.clickedRow).id;
          var quanity_id = "quantity_" + item_id;
          var quantity = document.getElementById(quanity_id).value;
          $.post("<?=$this->url?>/change_item_quantity", {"quantity": quantity, "name" : inventory_items[item_id].name, "party_id": inventory_items[item_id].party_id});
        }

    </script>
</head>

<body>
    <!-- Header -->
    <div class="container">
        <header class="title py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 pt-1">
                    <p class="text-muted">My Inventories:
                        <?= $user["email"] ?>
                    </p>
                </div>
                <div class="col-4 text-center">
                    <a class="title-text text-dark" href="<?=$this->url?>/parties">Dungeons And Databases</a>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a class="btn btn-sm btn-outline-secondary" title="Sign out of your account and return to the landing page." href="<?= $this->url ?>/logout">
                        <i class='fas fa-user-alt'></i> Sign Out
                    </a>
                </div>
            </div>
        </header>
    </div>
    <div>
        <main class="container">
            <!--INVENTORY INFO-->
            <h3 class="text-center" id="inventory_name"></h3>
            <h4 class="text-center"> <i id="max_weight"> </i><i id="fixed_weight"> </i></h4>

            <!--SELECT INVENTORY-->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Inventory Selection
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <button class="dropdown-item" type="button">Inventory 1</button>
                    <button class="dropdown-item" type="button">Inventory 2</button>
                    <button class="dropdown-item" type="button">Inventory 3</button>
                </div>
            </div>

            <!--SUBINVENTORY TABLE-->

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div class="mb-3">
                            <div>
                                <h3 class="centered-text">Subinventories:</h3>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-inverse table-striped" id="subinventories_table">
                                    <thead class="table-header">
                                        <tr>
                                          <th>Inventory Name</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-11">
                        <div class="mb-3">
                          <form id = "addInventoryForm">
                            <div class="input-group mb-3">
                              <span class="input-group-text">New Inventory Name:</span>
                              <input type="text" class="form-control" name ="inventory_name" required>
                            </div>
                            <div class="input-group mb-3">
                              <span class="input-group-text">Maximum Weight</span>
                              <input type="number" class="form-control" name ="maximum_capacity" required>
                            </div>
                            <div class="input-group mb-3">
                              <span class="input-group-text">Fixed Weight?</span>
                              <input type="number" class="form-control" name ="fixed_current_weight">
                            </div>
                            <div class="text-center">
                             <button type="reset" id="addInventorySubmit" class="btn btn-primary">Create New Subinventory</button>
                            </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM LISTING-->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div class="mb-3">
                            <div>
                                <h3 class="centered-text">Items</h3>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-inverse table-striped" id="items_table">
                                    <thead class="table-header">
                                        <tr>
                                          <th>Quantity</th>
                                          <th>Name</th>
                                          <th>Type</th>
                                          <th>Rarity/Attunement</th>
                                          <th>Weight</th>
                                          <th>Description</th>
                                          <th>Delete</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                  <div class="col-11">
                      <div class="text-center">
                        <a href="<?=$this->url?>/search" class="btn btn-primary">Add Official Items</a>
                      </div>
                  </div>
                </div>

            </div>

    </div>

    </main>
    </div>

    <!-- 4. include bootstrap Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

</body>

</html>
