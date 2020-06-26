<form action="countryInfo.php" method="post">
select country:<select id="country">

<?php
$servername = "localhost";
$username ="root";
$password="";
$database="mollinger";
$connection = mysqli_connect($servername, $username, $password, $database);
if(!$connection){
    die("Connection failed: " . mysqli_connect_error());
}

$nationality = $connection-> prepare("SELECT * FROM countries");
if(!$connection){
    die("we did manage to connect your database");
}
$nationality->execute();
$result= $nationality->get_result();
while($row =$result->fetch_assoc()){ 
?>
<option value="<?php print $row["COUNTRY_ID"];  ?> "><?php print $row["COUNTRY_NAME"];?></option>
<?php
}
?>


</select> 
        
        <input type="submit" name="SelectCountry" value="Filter">
    </form>
List of people in the database:<BR>
    <?php
    $user=0;
    $displayAll= true;
    if(isset($_POST["countrySelect"])){
        if($_POST["countrySelect"]!=0){
            $displayAll = false;
        }
    }
    if($displayAll){
        $sqlSelect = $connection->prepare("SELECT * FROM ppl");
    }else{
        $sqlSelect = $connection->prepare("SELECT * FROM ppl WHERE Nationality=?");
        $sqlSelect->bind_param("i", $_POST["countrySelect"]);
    }
   
    $sqlSelect->execute();
    $result = $sqlSelect->get_result();
    while($row = $result->fetch_assoc()){
        print $row["First_Name"] . " ";
        print $row["Second_Name"] . " ";
    }
  
    ?>