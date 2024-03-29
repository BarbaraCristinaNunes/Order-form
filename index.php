<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();
// setcookie("cookiee","ola");


$totalValue = $_COOKIE['price'];
$price = 0;
$order = [];
$check = true;

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



        
//  make the validation of the information that the user gives to the page

$email = $street = $streetnumber = $city = $zipcode = "";
$emailErr = $streetErr = $streetnumberErr = $cityErr = $zipcodeErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "E-mail is required";
        $check = false;
    } else {
        $email = $_POST["email"];
        $check = true;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $check = false;
    }else {
        $email = $_POST["email"];
        $_SESSION['email'] = $_POST['email'];
        $check = true;
    }

    if (empty($_POST["street"])) {
      $streetErr = "Street is required";
      $check = false;
    } else {
      $street = $_POST["street"];
      $_SESSION['street'] = $_POST['street'];
      $check = true;

    } 
    if (empty($_POST["streetnumber"])) {
        $streetnumberErr = "Street number is required";
        $check = false;
    } else {
      $streetnumber = $_POST["streetnumber"];
      $_SESSION['streetnumber'] = $_POST['streetnumber'];
      $check = true;

    }

    if (!preg_match("/^[0-9]+$/",$streetnumber)) {
        $streetnumberErr = "The street number should be only numbers";
        $check = false;
    }
  
    if (empty($_POST["city"])) {
        $cityErr = "City is required";
        $check = false;
    } else {
      $city = $_POST["city"];
      $_SESSION['city'] = $_POST['city'];
      $check = true;
    }
  
    if (empty($_POST["zipcode"])) {
      $zipcodeErr = "Zipcode is required";
      $check = false;
    } else {
      $zipcode = $_POST["zipcode"];
      $_SESSION['zipcode'] = $_POST['zipcode'];
      $check = true;

    }
    if (!preg_match("/^[0-9]+$/",$zipcode)) {
        $zipcodeErr = "The zipcode should be only numbers";
        $check = false;
    }
  }




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

// send the infromation of the arrays food and drink to the page

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

// get the product which the user chose and the price
// the variable $price is used to show the price of the order in the email that is sended to the user when he/she submits the form$price=0;
if(isset($_POST['products'])){
  $position = $_POST['products'];
  $key = array_keys($position);
  for($i=0; $i < count($key,1); $i++){
    array_push($order, $products[$key[$i]]);
    $totalValue += $products[$key[$i]]['price'];
    $price += $products[$key[$i]]['price'];
  }
  setcookie("order", json_encode($order));
  setcookie("price", strval($totalValue), time() + (86400 * 30), "/");
}else{
  $totalValue = $_COOKIE['price'];
}
// if(isset($_POST['products'])){
//   $position = $_POST['products'];
//   print_r($position);
//   $key = array_keys($position);
//   print_r($key);
//   if($position > 0){
//     for($i=0; $i < count($key,1); $i++){
//       array_push($order, $products[$key[$i]]);
//       $totalValue += $products[$key[$i]]['price'] * $key[$i];
//       $price += $products[$key[$i]]['price'] * $key[$i];
//     }
//     setcookie("order", json_encode($order));
//     setcookie("price", strval($totalValue), time() + (86400 * 30), "/");
//   }else{
//     $totalValue = $_COOKIE['price'];
//   }
  
// }
// this function write an email to the user with his/her information 

function sendMessage(){
  $to = $_SESSION['email'];
  $subject = "Your order";
  // $message1 = "Good morning,<br>";
  $message2 = "Your order is: <br>";
  $message3= "The total price is: € ";
  $message4= "<br> Your order will be delivered in: ";
  $headers = '<br> From: nunes.barbarac@gmail.com'."\r\n".'<br> Reply-To: suport@example.com' . "\r\n";
  global $order;
  global $price;
  for($i = 0; $i < count($order); $i++){
    $message2.= $order[$i]['name']. "- €".number_format($order[$i]['price'],2). "<br>";
  }
  $message= $message2.$message3.number_format($price,2).$message4.$_SESSION['street'].", ".$_SESSION['streetnumber'].", ".$_SESSION['city'].", ".$_SESSION['zipcode']."<br>";
  echo "<div class='alert alert-success' role='alert'>" .$message. "</div>";
  // echo "<div class='alert alert-success' role='alert'>" .$headers. "</div>";
  $enviar = mail($to, $subject, $message, $headers);
  
  if( $enviar == true ) {
    echo "Message sent successfully...";
  }else {
    echo "Message could not be sent...";
  }
}

// Show to the user in how many time his/her order will be delivered and call the function that send the email to the user

if(isset($_POST['btn']) && isset($_POST['products']) && $check == true){
  if(isset($_POST['express_delivery'])){
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 45 minutes! </div>";
    $price += 5;
    $totalValue += 5;
  }else{
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 2 hours!  </div>";
  }
  sendMessage();
}

require 'form-view.php';
whatIsHappening();
?>