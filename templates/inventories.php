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

        function display_items() {
            var found_items = null;

            $.post("<?=$this->url?>/get_items", function(response) {
              found_items = JSON.parse(response);
              //console.log(found_items);
              if (found_items != null) {
                inventory_items = {};
                inventory_id = found_items[0].inventory_id;

                for (var num in found_items) {
                    i = found_items[num];
                    //console.log(i);
                    var i_i = i.item_name + "_" + i.party_id;
                    inventory_items[i_i] = new Item(i.item_name, i.party_id, i.is_magical, i.rarity, i.attunement, i.equipment_category, i.weight, i.description, i.item_quantity);
                    addToTable(i.item_name, i.party_id, i.is_magical, i.rarity, i.attunement, i.equipment_category, i.weight, i.description, i.item_quantity);
                }
              }
            });
        }

        function addToTable(name, party_id, is_magical, rarity, attunement, equipment_category, weight, description, quantity) {
            var table = document.getElementById("items_table");
            var newRow = table.insertRow(table.rows.length);
            newRow.insertCell(0).textContent = quantity;
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

            delete table[table.rows.item(table.clickedRow).id];
            table.deleteRow(delRow);
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
            <h3 class="text-center">My Inventories</h3>

            <!-- List of Parties -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div class="mb-3">
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

                <div>
                  <div class="row justify-content-center">
                    <div class="col-11">
                      <form class="card p-3 bg-light">
                          <div class="mb-3">
                              <input type="character" class="form-control" id="search" placeholder="Search Items">
                          </div>
                          <div class="col-auto">
                              <button type="search" class="btn btn-primary ">Search</button>
                          </div>
                      </form>
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
