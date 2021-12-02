<!DOCTYPE html>
<style>
    .card {
        margin-top: 10px;
    }
</style>

<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

    <title>Dungeons And Databases: Items</title>

    <!-- 3. link bootstrap -->
    <!-- if you choose to use CDN for CSS bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>
    <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){

      		$('button#searchButton').click(function(){
	      		postSearchItems();
    		});
	});

	function postSearchItems() {
	    console.log("we got in");	
	    form_data = $('form#searchInput').serializeArray();
	    console.log("we did the serial");
            $.post("<?= $this->url ?>/search_items", form_data, function(response) {
                var json_response = JSON.parse(response);
                
                for (i in json_response["items"]) {
		    var isi = json_response["items"][i];
		    console.log(isi);
                    addToSearchTable(isi.item_name, isi.party_id, isi.is_magical, isi.rarity, isi.attunement, isi.equipment_category, isi.weight, isi.description);
                }
	    });
	    console.log("posted");
	}

        function addToSearchTable(name, party_id, is_magical, rarity, attunement, equipment_category, weight, description) {
            var table = document.getElementById("items_search_table");
            var newRow = table.insertRow(table.rows.length);
            newRow.insertCell(0).textContent = name;
            newRow.insertCell(1).textContent = equipment_category;
            var full_rarity = rarity;
            if (attunement !== "") {
                full_rarity = full_rarity + "(" + attunement + ")";
            }
            newRow.insertCell(2).textContent = full_rarity;
            newRow.insertCell(3).textContent = weight;
            newRow.insertCell(4).innerHTML = '<p class="scrolling">' + description + '</p>';
            newRow.insertCell(5).innerHTML = '<button class="btn btn-close" onclick="delete_item()"></button>';
            newRow.id = name + "_" + party_id;

            newRow.addEventListener("mouseover", function() {
                table.clickedRow = this.rowIndex;
            });
        }
    </script>
</head>

<body>
    <!-- Header -->
    <div class="container">
        <header class="title py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 pt-1">
                    <p class="text-muted">Items Search: <?= $user["email"] ?></p>
                </div>
                <div class="col-4 text-center">
                    <a class="title-text text-dark" href="<?= $this->url ?>/parties">Dungeons And Databases</a>
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
            <h3 class="text-center">Items</h3>
            <div class="container">
                <div class="row justify-content-center">
                    <div class = "col">
                        <div class = "col-4">
                        <form id="addItem" onkeypress="return event.keyCode != 13">
                                <input type="text" id="itemName" name="pattern" class="form-control" placeholder="Item Name" />
                                <input type="text" id="itemQuantity" name="pattern" class="form-control" placeholder="Item Quantity" />
                                <button type="reset" id="addItem" class="btn btn-primary">Add Item</button>
                            </form> 
                        </div>

                        <div class="input-group mb-6">
                            <form id="searchInput" onkeypress="return event.keyCode != 13">
                                <input type="search" id="form1" name="pattern" class="form-control" placeholder="Search Items" />
                                <button type="reset" id="searchButton" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table table-inverse table-striped" id="items_search_table">
                                <thead class="table-header">
                                    <tr>
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
    </div>

    </main>
    </div>

    <!-- 4. include bootstrap Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

</body>

</html>
