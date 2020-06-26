<?php
include_once "sessionCheck.php";

include_once "credentials.php";

include_once "displayUser.php";
?>

<!DOCTYPE html>


<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Products Page</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='2tpif.css'>

</head>

<body>
<script>
        window.onscroll = function() {myFunction()};
        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;
        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }
</script>
    <nav id="NavigationBar">
        <div id="NavigationTitle">
            
        </div>
        <div id="NavigationLinks">
            <a href="products.php" class="page active">Products</a>
            <a href="about.html" class="page">About</a>
        </div>

        <?php
        if (isset($_POST["BuyItem"])) {
            array_push($_SESSION["Basket"], $_POST["BuyItem"]);
          }
        if (isset($_POST["Logout"])) {
            session_unset();
            session_destroy();
            print "You have been successfully logged-out" . "<br>";

        ?>
<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="post">
                    <div>
                      <div>
                        <label for="Username">Username: </label>
                        <input type="text" name="Username" placeholder="Username" required>
                      </div>
                      <div>
                        <label for="Password">Password: </label>
                        <input type="password" name="Password" placeholder="Password" required>
                      </div>
                    </div>
                    <input type="submit" name="Login" id="loginButton" value="Login">
                  </form>
<!--             <a href="products.php"> Click here to login again </a>
 -->
            <?php
             $bDisplaySignup = false;
             if (!isset($_SESSION["UserLogged"])) {
               $bDisplaySignup = true;
             } elseif (!$_SESSION["UserLogged"]) {
               $bDisplaySignup = true;
             }
 
             if ($bDisplaySignup) { ?>
             <div id="Signup"><a href="Signup.php">Signup</a></div>
             <?php }

        } elseif ($_SESSION["UserLogged"]) {

            print "You have already been logged-in" . "<br>";
            displayUserDetails($connection);
        } elseif (isset($_POST["Username"]) && isset($_POST["Password"])) {
            $userFromMyDatabase = $connection->prepare(
                "SELECT * FROM ppl WHERE UserName=?"
            );
            $userFromMyDatabase->bind_param("s", $_POST["Username"]);
            $userFromMyDatabase->execute();
            $result = $userFromMyDatabase->get_result();
            if ($result->num_rows === 1) {

                print "Your password is being verified " . "<br>";
                $row = $result->fetch_assoc();
                if (password_verify($_POST["Password"], $row["Password"])) {

                    $_SESSION["UserLogged"] = true;
                    $_SESSION["CurrentUser"] = $row["PERSON_ID"];
                    $_SESSION["Basket"] = [];
                    displayUserDetails($connection);
                } else {
                    print "Password mismatched ! Please type your password correctly"; ?>
                    <form>
                        <div>
                            <label for="password">Password</label>
                            <input type="password" name="Password">
                        </div>
                        <input type="submit" value="Login">
                    </form> <?php
                        }
                    } else {

                        print "The username you typed has not been found in our database !!"; ?>

                <a href="signup.php">Please register first</a> <br>
                <a href="products.php">Try again to login</a>
            <?php
                    }
                } else {

            ?>
            <div id="Login">
                <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
                    <div>
                        <label for="Username">Username</label>
                        <input type="text" name="Username">
                    </div>

                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="Password">
                    </div>
                    <input type="submit" value="Login">
                </form>

            </div>

        <?php

                } ?>
                  </div>


        <div id="Signup"> <a href="signup.php">Signup</a></div>
        <div id="NavigationLanguage">
            <a href="productsF.php" class="page active">french</a>
            
            <a href="products.php" class="page ">English</a>
        </div>
        <?php if (isset($_SESSION["UserLogged"])) {
          if (!$_SESSION["UserLogged"]) { ?>
        <div id="Signup"><a href="Signup.php">Signup</a></div>
<?php }
        } ?>
    </nav>


    <h1>Voici Nos Produits</h1>
    <div><a href="finishOrder.php">Basket = </a><?php print sizeof($_SESSION["Basket"]); ?>
    </div>
    <div id="AllProducts">

        <?php
        $product = $connection->prepare("SELECT * FROM products");
        $product->execute();
        $result = $product->get_result();
        while ($row = $result->fetch_assoc()) {
        ?>

            <div class="Product">
            <img src="<?php print $row["Picture"]; ?>" width="50%" length="50%" />
            <h3>Name: <?php print $row["Name"]; ?></h3>
            <h2>Car which is able to drive</h2>
            <h4>Price: 3 &euro;</h4>
            </div>

            <form action="products.php" method="post">
                <input type="hidden" name="BuyItem" value="<?php print $row["ID"]; ?>">
                <input type="submit" name="Buy" value="Buy">
            </form>
        <?php

        }
        ?>

    </div>

    <?php
    if(isset($_POST["Buy"])){
        print "done";
    }
    
    
    
    
    ?>
</body>

</html>