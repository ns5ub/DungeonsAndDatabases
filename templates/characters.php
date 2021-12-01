<!DOCTYPE html>
<style>
.card{
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

  <title>Dungeons And Databases: Characters in Party</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>
  <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <script type="text/javascript">

    var party_id = <?= $page_info["party_id"] ?>;
    $(document).ready(function(){
      display_characters();

      $('button#submitCharacter').click(function(){
        form_data = $('form#addCharacter').serializeArray();
        console.log(form_data);
        if(form_data[0]["value"].length > 0){
          $.post("<?=$this->url?>/add_character", form_data, function(response){
            console.log(response);
            location.reload();
          });
        }
      });
    });

    function display_characters(){
      $.post("<?=$this->url?>/get_characters", function(response) {
        var json_response = JSON.parse(response);
        var list = $('#character_list');
        list.empty();

        for(c in json_response["characters"]){
          var char_info = json_response["characters"][c];
          var link = $('<a href="<?=$this->url?>/inventories" class="list-group-item list-group-item-action"></a>');
          link.attr("main_inventory_id", char_info["main_inventory_id"]);
          link.click(function(){
            set_inventory($(this).attr("main_inventory_id"));
          });
          link.text(char_info["character_name"]);
          list.append(link);
        }
      });
    }

    function set_inventory(main_inventory_id){
      $.post("<?=$this->url?>/set_inventory", { "inventory_id" : main_inventory_id});
    }

  </script>

  <!-- include your CSS by including your CSS last,
    anything you write may override (depending on specificity) the Bootstrap CSS -->
  <link rel="stylesheet" href="styles/main.css" />
</head>

<body>
  <!-- Header -->
  <div class="container">
    <header class="title py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <p class="text-muted">Characters in Party: <?= $user["email"] ?></p>
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
      <h3 class="text-center">Characters in Party</h3>
      <h4 class="text-center"><?= $page_info["party_name"] ?></h4>
      <!-- List of Characters -->
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-5">
          <div class="list-group" id = "character_list">
                <a href="#" class="list-group-item list-group-item-action active">Character 1</a>
                <a href="#" class="list-group-item list-group-item-action">Character 2</a>
                <a href="#" class="list-group-item list-group-item-action">Character 3</a>
            </div>
            <form class="card p-3 bg-light" id="addCharacter">
              <div class="mb-3">
                <input type="text"  name ="character_name" class="form-control" placeholder="Character Name">
              </div>
              <div class="mb-3">
                <input type="number"  name ="maximum_capacity" class="form-control" placeholder="Maximum Capacity">
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary" id="submitCharacter">Add Character</button>
              </div>
            </form>
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
