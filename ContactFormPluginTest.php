<?php
/*
  Plugin Name: form
  Description: simple forme with field validator
  Version: 1.0.0
  Author: yassinaibi
*/


require_once(ABSPATH . 'wp-config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($conn, DB_NAME);

function newTable()
{

    global $conn;

    $sql = "CREATE TABLE infos(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname varchar(255) NOT NULL, lastname varchar(255) NOT NULL, email varchar(255) NOT NULL, subj varchar(255) NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($conn, $sql);
    return $res;
}


if ($conn == true){

    newTable();
}


function form($atts){
    $prenom= "";
    $nom= "";
    $mail= "";
    $sujet= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'firstname' => 'true',
            'lastname' => 'true',
            'email' => 'true',
            'subject' => 'true',
            'message' => 'true'
            
    ), $atts));

    if($firstname== "true"){
        $prenom = '<label>First name:</label><input type="text" name="fname" required>';
    }

    if($lastname== "true"){
        $nom = '<label>Last name:</label><input type="text" name="lname" required>';
    }

    if($email== "true"){
        $mail = '<label>Email:</label><input type="email" name="email" required>';
    }
    if($subject== "true"){
        $sujet = '<label>Subject:</label><input type="text" name="subject" required>';
    }

    if($message== "true"){
        $msg = '<label>Message:</label><textarea name="msg"></textarea>';
    }

    echo '<form method="POST"  >' .$prenom.$nom.$mail.$sujet.$msg. '<input style="margin-top : 20px;" value="Send" type="submit" name="send"></form>';
}


add_shortcode('Form', 'form');



    function sendToDB($fname,$lname,$email,$subject,$msg)
    {
        global $conn;

    $sql = "INSERT INTO infos(firstname,lastname,email,subj, msg) VALUES ('$fname','$lname','$email','$subject','$msg')";
    $res = mysqli_query($conn , $sql);
    
    return $res;
    }

    if(isset($_POST['send'])){

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $msg = $_POST['msg'];

        // if ( ( $email )=="" &&  $email== "true" || ( $subject )=="" && $subject== "true" || ( $msg )=="" && $message=== "true") {
        //    echo 'Please Fill all the filed of registration form <br>';
        // }
        if (!is_email($email)) {
            echo 'this is not a valid email <br>';
        }
        else if (10 > strlen($msg) ) {
           echo 'msg is too short <br>';
        }
        else{
            sendToDB($fname,$lname,$email,$subject,$msg);
            echo "thank's for us yaContact Plugin <br> you data has send successfully";
        }
    }

    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("yaContact", "yaContact", 4, "yaContact", "adminMenu");
    }

function adminMenu()
{
echo <<< 'EOD'
<div style="text-align: center;">
<h1 style="color: red;">Contact form Created By yassine aibi</h1>
<h2 style="color: saddlebrown;">to use this form form just add this short Code [Form]</h2>
<p> this Contact Form have </p>
<ul>
    <li>first name</li>
    <li>last name</li>
    <li>email</li>
    <li>subject</li>
    <li>message</li>
</ul>
</div>

<h2>
 Use this format to delete label

[Form lastname="false" firstname="false"]

    if you want to use all label use this short code

[Form]
</h2>

<style>

ul li {
    font-size: 40px;
    padding: 1% 1%;
}
</style>
EOD;
}

?>