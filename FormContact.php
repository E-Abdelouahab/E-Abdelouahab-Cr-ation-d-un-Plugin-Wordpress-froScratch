<?php
/*
Plugin Name: AbdelP
Description: Simple plugin WordPress Contact Form created From scratch
Version: 1.0.0
Author: Abdelouahab
*/

//Creation de la connection avec la base de donné de wordpress
require_once(ABSPATH . 'wp-config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($conn, DB_NAME);

//Fonction de la creation d'une nouvelle table pour le stockage des informations de la formulaire
function Tableinfo()
{

    global $conn;

    $sql = "CREATE TABLE infos(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, Premiername varchar(255) NOT NULL, deuxiemname varchar(255) NOT NULL, email varchar(255) NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($conn, $sql);
    return $res;
}

//Creation du Table si la connection est établie
if ($conn == true){

    Tableinfo();
}


//Fonction pour laisser ou supprimer des champs du formulaire
function formP($atts){
    $premiername= "";
    $deuxiemname= "";
    $mail= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'premiername' => 'true',
            'deuxiemname' => 'true',
            'email' => 'true',
            'message' => 'true'
    ), $atts));

    if($premiername== "true"){
        $prenom = '<label> prenom:</label><input type="text" name="Pname" required>';
    }

    if($deuxiemname== "true"){
        $nom = '<label>nom:</label><input type="text" name="Dname" required>';
    }

    if($email== "true"){
        $mail = '<label>Email:</label><input type="email" name="email" required>';
    }

    if($message== "true"){
        $msg = '<label>Message:</label><textarea name="msg"></textarea>';
    }

    echo '<form method="POST"  >' .$prenom.$nom.$mail.$msg. '<input style="margin-top : 15px;" value="Send" type="submit" name="sendMsg"></form>';
}



//Shortcode du plugin
add_shortcode('FormP', 'formP');



// Fonction d'envoi des informations au base de donnée
    function sendToDB($prenom,$nom,$email,$msg)
    {
        global $conn;

    $sql = "INSERT INTO infos(premiername,deuxiemname,email, msg) VALUES ('$prenom','$nom','$email','$msg')";
    $res = mysqli_query($conn , $sql);
    
    return $res;
    }



//L'envoi des informations au base de donnée 
    if(isset($_POST['send'])){

        $Pname = $_POST['premiername'];
        $Dname = $_POST['deuxiemname'];
        $email = $_POST['email'];
        $msg = $_POST['msg'];
        

        sendToDB($Pname,$Dname,$email,$msg);
    
    }




    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("AbdelP", "AbdelP", 4, "AbdelP", "adminMenu");
    }

function adminMenu()
{
    echo <<< EOD
    <div style="font-size : 20px; display : flex; flex-direction : column;">
    <center><h1 style="color:red; font-family : monospace;">
    AbdelP
    </h1></center>
  
    <h3>
    Ce plugin génère un formulaire de contact
    </h3>
  
    <h4>
    Les champs de ce formulaire de contact :
    </h4>
    <ul>
      <li>prenom</li>
      <li>nom</li>
      <li>email</li>
      <li>message</li>
    </ul>
  
    <h3>
    Utilisez le shortcode [FormP] dans votre page pour générer le formulaire de contact
    </h3>
  
    <h3>
    Si vous souhaitez supprimer un champ, ajoutez simplement name de dossiers = "false" au shortcode
    </h3>
    <h4>Example:</h4>
    <p style="font-size : 20px;">
    si vous voulez supprimer le champ du nom, utilisez le shortcode [FormP nom = "false"] <br>
    vous pouvez supprimer plusieurs champs dans le même formulaire <br>
    [FormP nom = "false" email = "false"] pour supprimer à la fois le nom de famille et les champs de messagerie.
    </p>
  
  
  
  </div>

EOD;
}

?>