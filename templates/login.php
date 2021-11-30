<!DOCTYPE html>
<!-- By: Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)-->

<html lang="en">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Nikita Saxena (ns5ub), Kevin Li (kl7ck), Zoe Pham (zcp7yd)">

        <title>Dungeons And Databases: Sign In</title>

        <!-- 3. link bootstrap -->
        <!-- if you choose to use CDN for CSS bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

        <!-- include your CSS by including your CSS last,
        anything you write may override (depending on specificity) the Bootstrap CSS -->
        <link rel="stylesheet" href="styles/landing.css" />
    </head>

    <body>
        <div class="container py-5">
            <!-- Align the card in the center!-->
            <div class="row d-flex justify-content-center align-items-center">
                <!-- Want card to be smaller in the center - change its size based on screen size-->
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 p-5">
                    <div class="card shadow-2-strong card-border">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-3">Dungeons and Databases</h3>
                            <p>You have how many bags of holding?</p>

                            <hr class="my-4">

                            <?php
                                if (!empty($error_msg)) {
                                    echo "<div class='alert alert-danger'>$error_msg</div>";
                                }
                            ?>

                            <form action="<?=$this->url?>/login" method="post" onsubmit="return validate();">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control is-invalid" id="email" name="email" autofocus=""/>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username (Optional)</label>
                                    <input type="text" class="form-control" id="username" name="username"/>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control is-invalid" id="password" name="password"/>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="submit">Log in / Create Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

        <script type="text/javascript">
            function validate () {
                var email = document.getElementById("email").value;
                var pw = document.getElementById("password").value;

                if (email.length > 0 && pw.length >= 0) {
                    return true;
                }
                alert("Email and Password are required.");
                return false;
            }
        </script>

        <!-- 4. include bootstrap Javascript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    </body>
</html>
