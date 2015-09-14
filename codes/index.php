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
        <title>Bejelentkézes</title>
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
                    echo "Még nem érvényesítette regisztrációját, ezt megteheti az e-mail címére kapott levelünkben a linkre kattintva.";
                }
                else
                {
                    echo "Nem megfelelõ felhasználónév vagy jelszó, regisztrált már?";
                }
            }
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false)
        {
?>
            <form name="bejelentkezes" action="index.php" method=post>
                felhasználónév: <input type="text" name="user" value="<? echo $username; ?>" size="5" maxlength="15" class="userinput">
                </br>
                jelszó: <input type="password" name="psw" value="<? echo $password; ?>" size="5" maxlength="15" class="pswinput">
                </br>
                <input type="submit" name="go" value="bejelentkezés" size="6">
                </br>
                <input type="button" name="new" value="regisztráció" size="6" onClick="document.location = 'reg.php'">
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