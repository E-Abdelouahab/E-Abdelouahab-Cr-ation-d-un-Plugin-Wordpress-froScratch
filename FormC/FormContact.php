<?php
/*
Plugin Name:  Contact Form 
Plugin URI:  
Description: Simple contact form plugin. Form display is done via a shortcode.
Version: 1.0
Author:  Abdel
*/


require_once(ABSPATH . 'wp-config.php');
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($connection, DB_NAME);


function newTableData()
{
    global $connection;

    $sql = "CREATE TABLE Posts(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, Nom varchar(255) NOT NULL, email varchar(255) NOT NULL, text varchar(255) NOT NULL)";
    $result = mysqli_query($connection, $sql);
    return $result;
}

if ($connection == true){
    newTableData();
}



add_action("admin_menu", "addMenu");
function addMenu()
{
  add_menu_page("Contact Form", "Contact Form",4 , "contact-form" );

}

function contactform()
{
echo <<< 'EOD'

  <br /><h1 style="margin-left:40px;"> Contact Form:</h1><br />

<div style="display:flex;justify-content:space-around;">

<div>
<br>

  <form method="POST">

  <label>NAME:</label><br />
  <input type="text" class="form-control" name="name"><br /><br />

  <label>EMAIL:</label><br />
  <input type="text" class="form-control" name="email"><br /><br />

  <label>TEXT:</label><br />
  <input type="text" class="form-control" name="text"><br /><br />
<br /><input type="submit" name="submitcheck">
</form>

<h2>Use Shortcode : <em> [Contact_Form] </em></h2>
</div>

<div>
<br>
<form method="POST" >
<label>EMAIL:</label><br />
<input type="text" name="email"><br /><br />

<label>TEXT:</label><br />
<input type="text" name="text"><br /><br />

<br /><input type="submit" name="submitcheck">
</form>
<h2>You use this Shortcode : <em>[Contact form name='false']</em></h2>
</div>

</div>
EOD;
}




    function contact($atts){
        extract(shortcode_atts(
            array(
                'name' => 'true',
                'email' => 'true',
                'text' => 'true'

        ), $atts));

        if($name== "true"){
            $name1 = '<label>Name:</label><input type="text" class="form-control" name="nom" required>';
        }else{
            $name1 = "";
        }

        if($email== "true"){
            $email1 = '<label>Email:</label><input type="email" class="form-control" name="email" required>';
        }else{
            $email1 = "";
        }

        if($text== "true"){
            $text1 = '<label>Text:</label><input type="text" class="form-control" name="text" required>';
        }else{
            $text1 = "";
        }



        echo '<form method="POST"  >' .$name1 . $email1 . $text1 . '<br /><br /><input class="btn btn-primary" value="Submit" type="submit" name="submitcheck">
        </form><br />';
    }
    add_shortcode('contact_form', 'contact');



    function form($name, $email,  $text)
    {
        global $connection;

      $sql = "INSERT INTO posts(Nom, email, text) VALUES ('$name', '$email', '$text')";
      $result = mysqli_query($connection , $sql);

      return $result;
    }

    if(isset($_POST['submitcheck'])){

        $name = $_POST['nom'];
        $email = $_POST['email'];
        $text = $_POST['text'];

        form($name, $email, $text);



    }



?>
