<!-- TODO: add JQuery -->

<!DOCTYPE html>

<!-- https://cs4640.cs.virginia.edu/ns5ub/comic-archive/index.html -->
<!-- By: Nikita Saxena (ns5ub), Simran (sk9za)-->

<!-- Sources: https://getbootstrap.com/docs/5.0/utilities/spacing/, https://mdbootstrap.com/docs/standard/extended/login/, -->
<html lang="en">
    <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="author" content="Nikita Saxena, Simran Kaur">
        <meta name="description" content="Landing page for Comic Archive">
        <meta name="keywords" content="Comic Books, Pull List, Comic Reading Order">

        <meta property="og:title" content="Comic Archive: Sign In"/>
        <meta property="og:description" content="Sign in to create lists of comics you've read and those you want to read!"/>
        <meta property="og:type" content="website"/>
        <meta property="og:image" content="https://cs4640.cs.virginia.edu/ns5ub/sprint2/images/comic_background.jpg"/>
        <meta property="og:url" content="https://cs4640.cs.virginia.edu/ns5ub/sprint2/landing.html"/>

        <title>Comic Archive: Sign In</title>

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
                            <h3 class="mb-3">Comic Archive</h3>
                            <p>Sign in below to search comic books, keep track of comics you've read, and create pull lists! Organize your different reading lists and access them through any device through your account.</p>

                            <hr class="my-4">

                            <?php
                                if (!empty($error_msg)) {
                                    echo "<div class='alert alert-danger'>$error_msg</div>";
                                }
                            ?>

                            <form action="<?=$this->url?>/login" method="post" onsubmit="return validate();">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" autofocus=""/>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control is-invalid" id="password" name="password"/>
                                    <div id="pwhelp" class="form-text" style="text-align: center;">
                                        Password must contain at least:
                                        <ul id="pwreqs" style="display: inline-block; text-align: left;">
                                            <li id="pwreqlen">12 total characters</li>
                                            <li id="pwrequpcase">One upper case character (A-Z)</li>
                                            <li id="pwreqlowcase">One lower case character (a-z)</li>
                                            <li id="pwreqdigit">One digit (0-9)</li>
                                            <li id="pwreqspecial">One special character (@$!%*?&.)</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="submit" disabled="">Log in / Create Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="navbar bottom mt-auto bg-light">
            <div class="container d-flex justify-content-between">
                <p> By: Nikita Saxena (ns5ub), Simran Kaur (sk9za), CS 4640 UVA</p>
                <p><a href="https://www.vecteezy.com/free-vector/cartoon" style="color:black">Cartoon Vectors by Vecteezy</a></p>
            </div>
        </footer>

        <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

        <script type="text/javascript">
            function validate () {
                var email = document.getElementById("email").value;
                var pw = document.getElementById("password").value;

                if (email.length > 0 && pw.length >= 12) {
                    return true;
                }
                alert("Email and Password are required.");
                return false;
            }

            function passwordCheck(num) {
                var pw = document.getElementById("password");
                var pwhelp = document.getElementById("pwhelp");
                var submit = document.getElementById("submit");

                var full_regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{12,}$/;
                function check (regex, id) {
                    if(pw.value.search(regex) != -1) {
                        $('#'+id).css("color", "#50C878");
                    } else {
                        $('#'+id).css("color", "grey");
                    }
                };

                if (pw.value.search(full_regex) == -1) {
                    check(/[A-Za-z\d@$!%*?&.]{12,}/, "pwreqlen");
                    check(/[a-z]/, "pwreqlowcase");
                    check(/[A-Z]/, "pwrequpcase");
                    check(/\d/, "pwreqdigit");
                    check(/[@$!%*?&.]/, "pwreqspecial");
                    pw.classList.add("is-invalid");
                    submit.disabled = true;
                } else {
                    $("ul li").each(function() { $(this).css("color", "#50C878") });
                    pw.classList.remove("is-invalid");
                    submit.disabled = false;

                }
            }

            document.getElementById("password").addEventListener("keyup", function() {
                passwordCheck(12);
            });
        </script>

        <!-- 4. include bootstrap Javascript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script crossorigin="anonymous" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script crossorigin="anonymous" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    </body>
</html>
