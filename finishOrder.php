<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Finish order</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='2tpif.css'>

</head>
<body>

    <h1>Finish your order here:</h1>
    <?php
    include_once "sessionCheck.php";
    include_once "credentials.php";

    if (!$_SESSION["UserLogged"]) {
      die("You cannot be here- you must log in first in order to finish your order");
    }
    if (isset($_POST["ItemToDelete"])) {
      array_splice($_SESSION["Basket"], $_POST["ItemToDelete"], 1);
    }
    //var_dump($_SESSION["Basket"]);
    if (sizeof($_SESSION["Basket"]) === 0) {
      print "You do not have anything in your basket ! " . "<br>";
    } else {
      $total = 0;
      for ($i = 0; $i < sizeof($_SESSION["Basket"]); $i++) {

        $sqlSelect = $connection->prepare("SELECT NAME, Price FROM products WHERE ID=?");
        $sqlSelect->bind_param("i", $_SESSION["Basket"][$i]);
        $sqlSelect->execute();
        $myResult = $sqlSelect->get_result();
        if ($row = $myResult->fetch_assoc()) {
          print $row["NAME"] . "\n" . $row["Price"] . "<br>";
          $total = $total + $row["Price"];
        }
        ?> 
      <form action="finishOrder.php" method="post"> 
      <input type="hidden" name="ItemToDelete" value="<?= $i ?>" />
      <input type="submit" name="Delete" value="Delete"/>
      </form> <br>
      
      <?php
      }
      print "Total amount to pay is &euro;" . $total;
    }
    ?>

<a href="products.php">Keep shopping</a> <br>
    
</body>
</html>