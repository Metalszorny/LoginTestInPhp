<?
    session_start();
?>
<html>
    <head>
        <title>Bejelentk�zes</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body class="bentvan">
<?
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
        {
?>
            <div class="uzenet">Bejelentkez�s sikeres!</div>
            <input type=button value="kijelentkez�s" size="6" onClick="document.location='index.php?action=logout'">
<?
        }
        else
        {
?>
            <script> document.location = 'not_logged_in.php' </script>
<?
        }
?>
    </body>
</html>