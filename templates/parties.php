<!DOCTYPE html>
<html lang="en">
<style>
  .col-auto {
    margin: 5px;
  }
</style>

<head>

  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

  <title>Dungeons And Databases: My Parties</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>
  <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <!-- include your CSS by including your CSS last,
    anything you write may override (depending on specificity) the Bootstrap CSS -->
  <link rel="stylesheet" href="styles/main.css" />
  <style>
    .list-group-1 {
      max-height: 100px;
      margin-bottom: 10px;
      overflow: scroll;
      -webkit-overflow-scrolling: touch;
    }
  </style>

  <script type="text/javascript">
    $(document).ready(function() {
      display_user_parties();
    });

    function display_user_parties() {
      $.post("<?= $this->url ?>/get_user_parties", function(response) {

        var user_parties = JSON.parse(response);
        //console.log("hi");
        //console.log(user_parties);
        var container = $('#party_list');
        container.empty();

        var row_length = 2;
        var col_num = 0;
        var new_row = $('<div class="row justify-content-center"></div>');
        for (var party_id in user_parties) {
          var party_info = user_parties[party_id];
          var form_id = "add_user_to_party_form" + party_id;
          var new_col = $('<div class="col-6"></div>');
          var form = $('<form class="card p-3 bg-light"></form');
          form.attr("id", form_id);

          var info = $('<div class="mb-3"></div');
          var link = $('<legend><a href="<?= $this->url ?>/characters">' + party_info["party_name"] + '</a></legend>');
          link.attr("party_id", party_id);
          link.attr("party_name", party_info["party_name"]);
          //
          link.click(function() {
            console.log($(this).attr("party_id"), $(this).attr("party_name"));
            set_party($(this).attr("party_id"), $(this).attr("party_name"));
          });
          info.append(link);
          
          // Delete User Button
          var delete_user_button = $('<button type="reset" class="btn btn-danger"></button>');
          delete_user_button.text("Delete User");
          delete_user_button.click(function() {
            //console.log($('form#' + form_id).serializeArray());
            delete_user_from_party($('form#' + form_id).serializeArray(), party_id, list);
          });
          
          // List of Users
          var list = $('<div class="list-group-1"><ul class="list-group"></ul></div>');
          for (var u in party_info["users"]) {
            user_info = party_info["users"][u];
            list.append('<li class="list-group-item">' + user_info["username"] + '(' + user_info["email"] + ')' + '<button type="reset" class="btn-sm btn-outline-danger" onClick="delete_user_from_party">Delete User</button>' +'</li>')
          }
          info.append(list);
          var email_input = $('<input type="email-address" class="form-control" name="email" placeholder="Email Address">');
          info.append(email_input);
          form.append(info);
          // Add User
          var submission = $('<div class="col-auto"> </div');
          var button = $('<button type="reset" class="btn btn-primary"></button>');
          button.text("Add User");
          button.click(function() {
            //console.log($('form#' + form_id).serializeArray());
            add_user_to_party($('form#' + form_id).serializeArray(), party_id, list);
          });
          submission.append(button);
          form.append(submission);
          new_col.append(form);
          new_row.append(new_col);
          col_num++;

          // DELETE party
          var delete_party = $('<div class="col-auto"> </div');
          var delete_party_button = $('<button type="reset" class="btn btn-danger" style="text-right"></button>');
          delete_party_button.text("Delete Party");
          delete_party_button.click(function() {
            //console.log($('form#' + form_id).serializeArray());
            delete_party($('form#' + form_id).serializeArray(), party_id, list);
          });
          delete_party.append(delete_party_button);
          form.append(delete_party);
          //new_col.append(form);
          //new_row.append(new_col);
          //col_num++;

          if (col_num == row_length) {
            col_num = 0;
            container.append(new_row);
            var new_row = $('<div class="row justify-content-center"></div>');
          }
        }
        if (col_num != 0) {
          container.append(new_row);
        }

      });
    }

    // TODO:
    function delete_party(form_data, party_id, list) {

    }
    // TODO
    function delete_user_from_party(form_data, party_id, list) {
      form_data.push({
        name: "party_id",
        value: party_id
      });
      $.post("<?= $this->url ?>/delete_user_from_party", form_data, function(response) {
        //console.log(response);
        json_response = JSON.parse(response);
        //console.log(json_response);
        document.getElementById("message").innerHTML = "<div class='alert alert-success'>Deleted User</div>";
        return;
      });
    }






    function add_user_to_party(form_data, party_id, list) {
      console.log(form_data);
      if (!validate_email(form_data[0]["value"])) {
        document.getElementById("message").innerHTML = "<div class='alert alert-danger'>Not an Email!</div>";
        return;
      }
      form_data.push({
        name: "party_id",
        value: party_id
      });
      $.post("<?= $this->url ?>/add_user_to_party", form_data, function(response) {
        //console.log(response);
        json_response = JSON.parse(response);
        //console.log(json_response);
        if (!Object.keys(json_response).length) {
          document.getElementById("message").innerHTML = "<div class='alert alert-danger'>Not an Valid User!</div>";
        } else {
          document.getElementById("message").innerHTML = "<div class='alert alert-success'>Added User</div>";
          list.append('<li class="list-group-item">' + json_response[0]["username"] + '(' + json_response[0]["email"] + ')' + '</li>')
        }

      });
    }

    function validate_email(email) { //https://stackoverflow.com/questions/46155/whats-the-best-way-to-validate-an-email-address-in-javascript
      return String(email)
        .toLowerCase()
        .match(
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
    }

    function set_party(party_id, party_name) {
      $.post("<?= $this->url ?>/set_party", {
        "party_id": party_id,
        "party_name": party_name
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
          <p class="text-muted">Your Parties: <?= $user["email"] ?></p>
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

  <div class="container">
    <div id="message"></div>
  </div>

  <div>
    <main class="container">
      <h3 class="text-center">My Parties</h3>

      <!-- List of Parties -->
      <div class="container" id="party_list">
        <div class="row justify-content-center">
          <div class="col-4">
            <form class="card p-3 bg-light">
              <div class="mb-3">
                <legend>Party 1</legend>
                <div class="list-group-1">
                  <ul class="list-group">
                    <li class="list-group-item">User Name 1</li>
                    <li class="list-group-item">User Name 2</li>
                    <li class="list-group-item">User Name 3</li>
                  </ul>
                  <!-- TODO: Function to add user -->
                </div>
                <input type="email-address" class="form-control" id="addUser" placeholder="Email Address">
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary">Add User</button>
              </div>
            </form>
          </div>
          <div class="col-4">
            <form class="card p-3 bg-light" onsubmit="addUser()">
              <div class="mb-3">
                <legend>Party 2</legend>
                <div class="list-group-1">
                  <ul class="list-group">
                    <li class="list-group-item">User Name 1</li>
                    <li class="list-group-item">User Name 2</li>
                    <li class="list-group-item">User Name 3</li>
                  </ul>
                </div>
                <input type="email-address" class="form-control" id="addUser" placeholder="Email Address">

              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary">Add User</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- 4. include bootstrap Javascript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

</body>

</html>