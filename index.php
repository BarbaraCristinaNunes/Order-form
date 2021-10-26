<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();
// setcookie("cookiee","ola");


$totalValue = 0;
$price;
$order = [];

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
// whatIsHappening();



        
 
   
$email = $street = $streetnumber = $city = $zipcode = "";
$emailErr = $streetErr = $streetnumberErr = $cityErr = $zipcodeErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "E-mail is required";
    } else {
        $email = $_POST["email"];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }else {
        $email = $_POST["email"];
    }

    if (empty($_POST["street"])) {
      $streetErr = "Street is required";
    } else {
      $street = $_POST["street"];
    } 
    if (empty($_POST["streetnumber"])) {
        $streetnumberErr = "Street number is required";
    } else {
      $streetnumber = $_POST["streetnumber"];
    }

    if (!preg_match("/^[0-9]+$/",$streetnumber)) {
        $streetnumberErr = "The street number should be only numbers";
    }
  
    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
      $city = $_POST["city"];
    }
  
    if (empty($_POST["zipcode"])) {
      $zipcodeErr = "Zipcode is required";
    } else {
      $zipcode = $_POST["zipcode"];
    }
    if (!preg_match("/^[0-9]+$/",$zipcode)) {
        $zipcodeErr = "The zipcode should be only numbers";
    }
  }


$_SESSION['email'] = $_POST['email'];
$_SESSION['street'] = $_POST['street'];
$_SESSION['streetnumber'] = $_POST['streetnumber'];
$_SESSION['city'] = $_POST['city'];
$_SESSION['zipcode'] = $_POST['zipcode'];

//your products with their price.
$food = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5],
];

$drink = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

$products;
if(isset($_GET["food"])){
  if($_GET["food"]){
    $products = $food;
    // var_dump($products);
  }
  else{
    $products = $drink;
    // var_dump($products);
  }
}else{
  $products = $food;
}


if(isset($_POST['products'])){
  $position = $_POST['products'];
  // var_dump($position);
  $key = array_keys($position);
  // var_dump($key);
  for($i=0; $i < count($key,1); $i++){
    // var_dump($products[$key[$i]]['price']); 
    array_push($order, $products[$key[$i]]);
    // var_dump($order);
    $totalValue += $products[$key[$i]]['price'];
  }
  setcookie("order", json_encode($order));
  setcookie("price", strval($totalValue), time() + (86400 * 30), "/");
}else{
  $totalValue = $_COOKIE['price'];
}
// var_dump(json_decode($_COOKIE['order']));

// $to = "barbara.n.bio@gmail.com";
// $subject = "teste";
// $messege = "email enviado com php";
// $header = "From: nunes.barbarac@gmail.com";
// $enviar = mail($to, $subject, $messege, $header);

// if( $enviar == true ) {
//   echo "Message sent successfully...";
// }else {
//   echo "Message could not be sent...";
// }

if(isset($_POST['btn'])){
  if(isset($_POST['express_delivery'])){
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 45 minutes! </div>";
  }else{
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 2 minutes!  </div>";
  }
}

require 'form-view.php';
whatIsHappening();
?>