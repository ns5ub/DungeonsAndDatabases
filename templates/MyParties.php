<!DOCTYPE html>

<html lang="en">
<head>

  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

  <title>Dungeons And Databases: My Parties</title>

  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/6dd6be76af.js" crossorigin="anonymous"></script>

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
          <p class="text-muted">Your Parties: <?=$user["email"]?></p>
        </div>
        <div class="col-4 text-center">
          <p class="title-text text-dark" href="#">Dungeons And Databases</p>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="btn btn-sm btn-outline-secondary" title="Sign out of your account and return to the landing page."
            href="<?=$this->url?>/logout">
            <i class='fas fa-user-alt'></i> Sign Out
          </a>
        </div>
      </div>
    </header>
  </div>

  <!-- 4. include bootstrap Javascript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>
  <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
    src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

</body>

</html>
