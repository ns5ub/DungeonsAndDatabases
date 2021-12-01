<!DOCTYPE html>
<style>

</style>

<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

    <title>Dungeons And Databases: My Inventories</title>

    <!-- 3. link bootstrap -->
    <!-- if you choose to use CDN for CSS bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>

    <!-- include your CSS by including your CSS last,
    anything you write may override (depending on specificity) the Bootstrap CSS -->
    <link rel="stylesheet" href="styles/main.css" />
    
    <script type="text/javascript">
        
        var inventory_items = null;

        function Items(name, party_id, is_magical, rarity, attunement, type, weight, desc) {
            this.name = name;
            this.party_id = party_id;
            this.is_magical = is_magical;
            this.rarity = rarity;
            this.attunement = attunement;
            this.type = type;
            this.weight = weight;
            this.desc = desc;
        }

        function load_items() {
            var found_items = null;

            let table_id = (n) => parseInt(n) + 1;

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "/ns5ub/dungeonsanddatabases/get_items", true);
            ajax.responseType = "json";
            ajax.send(null);

            ajax.addEventListener("load", function() {
                if (this.status == 200) { // worked
                    //console.log(this.response);
                    //found_comics = JSON.parse(this.response);
                    found_items = this.response;

                    if (found_items != null) {
                        inventory_items = {};
                        for (var num in found_items) {
                            c = found_items[num];
                            //console.log(c);
                            var c_i = c.item_id;
                            inventory_items[c_i] = new Items(i.name, i.party_id, i.is_magical, i.rarity, i.attunement, i.type, i.weight, i.desc);
                            addToTable(table_id(num), i.name, i.party_id, i.is_magical, i.rarity, i.attunement, i.type, i.weight, i.desc);
                        }
                    }
                }
            });
            ajax.addEventListener("error", function() {
                document.getElementById("message").innerHTML = "<div class='alert alert-danger'>An Error in Retrieving</div>";
            });
        }

        function addToTable(name, party_id, is_magical, rarity, attunement, type, weight, desc) {
            var table = document.getElementById("items_table");
            var newRow = table.insertRow(table.rows.length);
            newRow.insertCell(0).textContent = name;
            newRow.insertCell(1).textContent = party_id;
            newRow.insertCell(2).textContent = is_magical;
            newRow.insertCell(3).textContent = rarity;
            newRow.insertCell(4).textContent = attunement;
            newRow.insertCell(5).textContent = type;
            newRow.insertCell(6).textContent = weight;
            newRow.insertCell(7).textContent = desc;
            newRow.id = item_id;

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

            var ajax = new XMLHttpRequest();
            ajax.open("POST", "/ns5ub/dungeonsanddatabases/delete_item", true);
            var params = "item_id=" + item_id;
            ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); //https://stackoverflow.com/questions/58217910/xmlhttprequest-not-sending-post-data
            ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) { // worked
                    //console.log(ajax.responseText);
                    document.getElementById("message").innerHTML = "<div class='alert alert-success'>Deleted Successfully</div>";
                }
            }
            ajax.addEventListener("error", function() {
                document.getElementById("message").innerHTML = "<div class='alert alert-danger'>Deletion Error</div>";
            });
            ajax.send(params); //https://stackoverflow.com/questions/9713058/send-post-data-using-xmlhttprequest
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
                    <p class="title-text text-dark" href="#">Dungeons And Databases</p>
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
                    <div class="col-6">
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
                                <table class="table table-sm table-inverse table-striped">
                                    <thead class="table-header">
                                        <tr>
                                            <th>Item Name / #</th>
                                            <th>Category</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
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

    </main>
    </div>

    <!-- 4. include bootstrap Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

</body>

</html>