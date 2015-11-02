<!DOCTYPE html>
<html>
<!--
StockLookup.php
David Brutsch
A php web program that reads in a company ticker from a web form and prints out stock details.  
-->

<?php 
   //Start first PHP section

// First set txt variable to nothing.  This will be the input field in the form.
$txt = "";
session_start();
// This stores the post field in the session if it exists and then redirects to the same page.
if (isset($_POST['submit']) && (($_POST['text']) != "")) {
    $_SESSION['text'] = $_POST['text'];
    header("Location: ". $_SERVER['REQUEST_URI']);
    exit;
} else {
    if(isset($_SESSION['text'])) {
        //Retrieve show string from form submission.
        $txt = $_SESSION['text'];
        unset($_SESSION['text']);
    }
}

 //End of first PHP section
?> 

<!-- Build the html form for the user to input a company symbol to lookup. -->
<div style="border: solid 1px #000000;">
<center>
<p><h3>Enter Company Symbol:</h3></p>

<form method="post" action="StockLookup.php">
<textarea rows="1" cols="6" name="text" >
</textarea>
<input type="submit" name="submit" value="Lookup" />
</form>

<?php
   //Start second PHP section

  //Obtain Quote Info - First we correctly build the string with the Company Ticker selected 
  //in the form - then we get all the data and store it in the quote variable 
  $string = "http://finance.google.com/finance/info?infotype=infoquoteall&q=$txt";
  $quote = file_get_contents($string);
  
  //Remove CR's from ouput - make it one line
  $stock = str_replace("\n", "", $quote);
  //Remove's the //, and [] to build qualified string  
  $data = substr($stock, 4, strlen($stock) -5);
  //decode JSON data
  $stock_output = json_decode($data, true);
  
  //Un-remark these to see all array keys, I used this to help determine my printed output
  //I left it in here just for documentation reasons
  //echo "$stock <br><br>";
  //echo "$data <br><br>";
  //echo "$stock_output <br><br>";
  
  //Output Stock price array key.
  echo "<br>";
  echo "<strong>Company: \n</strong>".$stock_output['name'] . "<br>"; 
  echo "<strong><span style=color:#FF0000;>Current: \n</span></strong>".$stock_output['l'] . "<br>"; 
  echo "<strong>Today's High: \n</strong>".$stock_output['hi'] . "<br>"; 
  echo "<strong>Previous Close: \n</strong>".$stock_output['pcls_fix'] . "<br>"; 
  echo "<strong>52 Week Range: \n</strong>".$stock_output['lo52'] ;
  echo " -\n".$stock_output['hi52'] . "<br>";
  echo "<strong>Date: \n</strong>".$stock_output['lt'] . "<br><br>"; 

 //End of second PHP section
?>
<button onclick="location = location.href">Clear</button>

</center>
</div>
</html>
