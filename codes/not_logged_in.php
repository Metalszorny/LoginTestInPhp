<?
    
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false)
    
{

        echo "Ezt az oldalt csak bejelentkezve láthatod";
        echo "<script> document.location = 'index.php';</script>";


        exit();

    }

?>