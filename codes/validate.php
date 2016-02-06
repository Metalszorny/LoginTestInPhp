<?

    include "db_config.php";

    $code=$_GET['c'];

    $query = mysql_query("SELECT * FROM users WHERE sha1(email)='$code'");


    if (mysql_num_rows($query) == 1)

    {

        $row = mysql_fetch_array($query);

        $id = $row['id'];

        $query = mysql_query("UPDATE `users` SET `valid` = '1' WHERE `id` = '$id'");


        echo "Bent volt";
    }
?>

</br>

<?

    echo "E-mail cimet sikeresen megerositette, most mar bejelentkezhet.";

?>

</br>

    <input type="button" name="new" value="fooldal" size="6" onClick="document.location = 'index.php'">