<?
    session_start();

    if (isset($_GET['action']) && $_GET['action'] == 'logout')
    {
        unset( $_SESSION['logged_in']);
        //$_SESSION['logged_in'] = false;
    } 
?>
<html>

    <head>
        <title>Bejelentk�zes</title>
	<link rel="stylesheet" type="text/css" href="style.css">
<?
	include "db_config.php";
?>
    </head>
    
<body class="alap">

<?
        if (isset($_POST['user']) && isset($_POST['psw']))
	{
            $username = $_POST['user'];
            $password = $_POST['psw'];
            $query1 = mysql_query("SELECT id FROM users WHERE username='$username' AND password='$password' AND valid='1'");
            $query2 = mysql_query("SELECT id FROM users WHERE username='$username' AND password='$password' AND valid='0'");

            if (mysql_num_rows($query1) == 1)
            {	
                $_SESSION['logged_in'] = true;
            }
            else
            {
                if (mysql_num_rows($query2) == 1)
                {
                    echo "M�g nem �rv�nyes�tette regisztr�ci�j�t, ezt megteheti az e-mail c�m�re kapott level�nkben a linkre kattintva.";
                }
                else
                {
                    echo "Nem megfelel� felhaszn�l�n�v vagy jelsz�, regisztr�lt m�r?";
                }
            }
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false)
        {
?>
            <form name="bejelentkezes" action="index.php" method=post>
                felhaszn�l�n�v: <input type="text" name="user" value="<? echo $username; ?>" size="5" maxlength="15" class="userinput">
                </br>
                jelsz�: <input type="password" name="psw" value="<? echo $password; ?>" size="5" maxlength="15" class="pswinput">
                </br>
                <input type="submit" name="go" value="bejelentkez�s" size="6">
                </br>
                <input type="button" name="new" value="regisztr�ci�" size="6" onClick="document.location = 'reg.php'">
            </form>
<?
        }	
        else
        {
?>
            <script>document.location = 'belepve.php'</script>
<?
        }
?>
    
</body>

</html>