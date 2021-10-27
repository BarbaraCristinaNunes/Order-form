<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();
// setcookie("cookiee","ola");


$totalValue = $_COOKIE['price'];
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



        
//  make the validation of the information that the user gives to the page

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
        $_SESSION['email'] = $_POST['email'];
    }

    if (empty($_POST["street"])) {
      $streetErr = "Street is required";
    } else {
      $street = $_POST["street"];
      $_SESSION['street'] = $_POST['street'];

    } 
    if (empty($_POST["streetnumber"])) {
        $streetnumberErr = "Street number is required";
    } else {
      $streetnumber = $_POST["streetnumber"];
      $_SESSION['streetnumber'] = $_POST['streetnumber'];

    }

    if (!preg_match("/^[0-9]+$/",$streetnumber)) {
        $streetnumberErr = "The street number should be only numbers";
    }
  
    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    } else {
      $city = $_POST["city"];
      $_SESSION['city'] = $_POST['city'];
    }
  
    if (empty($_POST["zipcode"])) {
      $zipcodeErr = "Zipcode is required";
    } else {
      $zipcode = $_POST["zipcode"];
      $_SESSION['zipcode'] = $_POST['zipcode'];

    }
    if (!preg_match("/^[0-9]+$/",$zipcode)) {
        $zipcodeErr = "The zipcode should be only numbers";
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

// this function write an email to the user with his/her information 

function sendMessege(){
  $to = $_SESSION['email'];
  $subject = "Your order";
  // $messege1 = "Good morning,<br>";
  $messege2 = "Your order is: <br>";
  $messege3= "The total price is: € ";
  $messege4= "<br> Your order will be delivered in: ";
  $headers = '<br> From: nunes.barbarac@gmail.com'."\r\n".'<br> Reply-To: suport@example.com' . "\r\n";
  global $order;
  global $price;
  for($i = 0; $i < count($order); $i++){
    $messege2.= $order[$i]['name']. "- €".number_format($order[$i]['price'],2). "<br>";
  }
  $messege= $messege2.$messege3.number_format($price,2).$messege4.$_SESSION['street'].", ".$_SESSION['streetnumber'].", ".$_SESSION['city'].", ".$_SESSION['zipcode']."<br>";
  echo "<div class='alert alert-success' role='alert'>" .$messege. "</div>";
  // echo "<div class='alert alert-success' role='alert'>" .$headers. "</div>";
  $enviar = mail($to, $subject, $messege, $headers);
  
  if( $enviar == true ) {
    echo "Message sent successfully...";
  }else {
    echo "Message could not be sent...";
  }
}

// Show to the user in how many time his/her order will be delivered and call the function that send the email to the user

if(isset($_POST['btn'])){
  if(isset($_POST['express_delivery'])){
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 45 minutes! </div>";
    $price += 5;
    $totalValue += 5;
  }else{
    echo "<div class='alert alert-success' role='alert'> Your delivery will arrive in 2 hours!  </div>";
  }
  sendMessege();
}

require 'form-view.php';
whatIsHappening();
?>