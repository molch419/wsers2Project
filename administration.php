<?php

include_once "sessionCheck.php";
include_once "credentials.php";

if (!$_SESSION["UserLogged"]) {

  die("You are logged-in and NOT allowed here !");

}
$userSelect = $connection->prepare(

  "SELECT User_role FROM ppl WHERE PERSON_ID=?"

);

$userSelect->bind_param("i", $_SESSION["CurrentUser"]);
$userSelect->execute();
$resultUser = $userSelect->get_result();
$rowUser = $resultUser->fetch_assoc();
if ($rowUser["User_role"] !== 2) {

  die("You are not an admin and not allowed here");

}

if (isset($_POST["userToDelete"])) {
  $users = $connection->prepare("DELETE FROM ppl WHERE UserName=?");
  $users->bind_param("s", $_POST["userToDelete"]);
  $users->execute();

}

$users = $connection->prepare("SELECT UserName FROM ppl WHERE PERSON_ID <>?");
$users->bind_param("i", $_SESSION["CurrentUser"]);
$users->execute();
$resultUsers = $users->get_result();
while ($rowUsers = $resultUsers->fetch_assoc()) {
  print $rowUsers["UserName"] . "<br>"; ?>
  <form action="Administration.php" method="post">
        <input type="hidden" name="userToDelete" value="<?php print $rowUsers[
          "UserName"
        ]; ?>" >
        <input type="submit" name="Delete" value="Delete">
    </form>
  <?php
}
?>

here you can add a new product if u want to:<BR>
<form action="adminstration.php" method="post">
    Name: <input type="text" name="Name" required><br>
    Description: <input type="text" name="Description"><br>
    Price: <input type="text" name="Price"><br>
    PictureName: <input type="text" name="Picture" required><br>    
    <input type="submit" name="Add" value="Add">
</form>

<?php
if (isset($_POST['Add'])) {
  
    $productName = isset($_POST['Name']) ? $_POST['Name'] : '';
    $productQuantity = isset($_POST['Price']) ? $_POST['Price'] : 0;
    $productDescription = isset($_POST['Picture']) ? $_POST['Picture'] : '';

    
    if (empty($productName)) {
        $errors[] = 'Please provide a product Name.';
    }

    if (empty($productDescription)) {
        $errors[] = 'Please provide a Picture.';

    }
      }else{

        $stmt = $connection->prepare('INSERT INTO products (Name, Description, Price, Picture) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssis', $_POST["Name"], $_POST["Description"], $_POST["Price"], $_POST["Picture"]);
        $stmt->execute();
      }
    ?>
