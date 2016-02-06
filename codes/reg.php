<html>
    <head>
        <title>Regisztráció</title>
        <link rel="stylesheet" type="text/css" href="style.css">
<?
        include "db_config.php";
?>
    </head>
    <body class="regist">
<?
        $username = $_POST['user'];
        $password1 = $_POST['pass1'];
	$password2 = $_POST['pass2'];
	$electronicmail = $_POST['mail'];
	$birthyear = $_POST['EvValasztas'];
	$birthmonth = $_POST['HonapValasztas'];
	$birthday = $_POST['NapValasztas'];
	$goodemail = false;
	$goodpass = false;
	$gooddate = false;
	$validdate = false;
	$currentyear = date('Y');
?>
	<div>A jelszó legalább 6 karakter hosszú legyen és tartalmazzon legalább egy számot!</div>
	<form name="bejelentkezes" action="reg.php" method=post>
            felhasználónév: <input type="text" name="user" value="<? echo $username; ?>" size="5" maxlength="15" class="reguserinput">
            </br>
            jelszó: <input type="password" name="pass1" value="<? echo $password1; ?>" size="5" maxlength="15" class="regpsw1input">
            </br>
            jelszó megint: <input type="password" name="pass2" value="<? echo $password2; ?>" size="5" maxlength="15" class="regpsw2input">
            </br>
            e-mail cím: <input type="text" name="mail" value="<? echo $electronicmail; ?>" size="5" maxlength="30" class="regemailinput">
            </br>
            Év: <input type="text" name="EvValasztas" value="<? echo $birthyear; ?>" size="4" class="regyearinput">
            Hónap: <select name="HonapValasztas" value="<? echo $birthmonth; ?>" size="1">
                    <option>januar</option>
                    <option>februar</option>
                    <option>marcius</option>
                    <option>aprilis</option>
                    <option>majus</option>
                    <option>junius</option>
                    <option>julius</option>
                    <option>augusztus</option>
                    <option>szeptember</option>
                    <option>oktober</option>
                    <option>november</option>
                    <option>december</option>
                   </select>
            Nap: <select name="NapValasztas" value="<? echo $birthday; ?>" size="1">
<?
                    for ($i=1; $i<=31; ++$i)
                    { 
?>
                        <option><? echo $i; ?></option>
<?
                    }
?>
                  </select>
                </br>
<!--		neme: nõ: <INPUT TYPE = "RADIO" name = "nem" value = "true"> férfi: <INPUT TYPE = "RADIO" name = "nem" value = "false">
		</br> -->
		<input name='registration' type='hidden'>
		<input type="submit" name="go" value="rögzítés" size="6">
		</br>
		<input type="reset" name="new" value="törlés" size="6">
	</form>
<?
	if (isset($_POST['registration']))
	{
            if ((strlen($password1) >= 6) && ($password1 == $password2) && ($password1 != "") && ($password2 != ""))
            {
                if( preg_match("#[0-9]+#", $password1) )
                {
                    if( preg_match("#[a-z]+#", $password1) )
                    {
                        $goodpass = true;
                    }
                }
            }

            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";

            if (eregi($pattern, $electronicmail))
            {
                $goodemail = true;
            }

            if(($birthmonth=="februar" && 
                $birthday >28 && 
                ((($birthyear-1904)%4)!=0)) || 
                ($birthmonth=="februar" && 
                $birthday >29 && 
                ((($birthyear-1904)%4)==0)) || 
                (($birthmonth=="aprilis" || 
                $birthmonth=="junius" || 
                $birthmonth=="szeptember" || 
                $birthmonth=="november") && 
                $birthday == 31))
            {
                $validdate = false;
            }
            else
            {
                $validdate = true;
            }

            $query1 = mysql_query("SELECT username FROM users WHERE users.username='$username'");
            $query2 = mysql_query("SELECT password FROM users WHERE users.password='$password1'");

            if (($birthyear < ($currentyear+1)) &&
                ($birthyear > "1900") && 
                ($username != "") && 
                (mysql_num_rows($query1) == 0) && 
                (mysql_num_rows($query2) == 0) && 
                ($goodemail == true) && 
                ($goodpass == true) && 
                ($validdate == true))
            {
                mysql_query("INSERT INTO `users` (`id`, `username`, `password`, `email`, `valid`, `birth_year`, `birth_month`, `birth_day`, `create_date`) VALUES (NULL, '$username', '$password1', '$electronicmail', '0', '$birthyear', '$birthmonth', '$birthday', CURRENT_TIMESTAMP)");
                include_once('mailer/class.phpmailer.php');

                    $code = sha1($electronicmail);
                    $link = "http://url/validate.php?c=$code";
                    $mail = new PHPMailer();
                    $mail->IsMail();
                    $mail->From     = 'sender';
                    $mail->FromName = 'NOREPLY';
                    $mail->Subject  =  'Sikeres regisztráció';
                    $mail->IsHTML(true);
                    $mail->Body = "Kedves $username,<br><br>Sikeresen regisztráltál a rendszerünkben.<br>
                        Kérjük regisztrációdat erõsítsd meg ezen a címen: <a href='$link'>Ide kattints</a>";
                        $mail->AddAddress($electronicmail, $username);

                        if(!$mail->Send())
                        {
                            echo "Hiba: ".$mail->ErrorInfo;
                        }
                        else
                        {
                            echo "Az adatokat rögzítettük, a megadott e-mail címre küldünk egy levelet, a benne található linkre kattintva érvényesítheti regisztrációját.";
                        }

                    //$username = " ";
                    //$password1 = " ";
                    //$password2 = " ";
                    //$birthyear = " ";
                    //$electronicmail = " ";
                    //$birthmonth = " ";
                    //$birthday = " ";

                    //echo "Az adatokat rögzítettük, a megadott e-mail címre küldünk egy levelet, a benne található linkre kattintva érvényesítheti regisztrációját.";	
                }
		else
		{
                    if ($password1 != $password2)
                    {
                        echo "A két jelszó nem egyezik meg, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($validdate == false)
                    {
                        echo "A megadott születési adatok valamelyike helytelen, kérem próbálja újra, a hiba további fennállása esetén lépjen kapcsolatba velünk.";
?>
                        </br>
<?
                    }

                    if ($birthyear > $currentyear)
                    {
                        echo "A megadott születési évszám nagyobb, mint a jelenlegi év, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($birthyear < "1900")
                    {
                        echo "A megadott születési évszám alapján több, mint 100 éves, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($username == "")
                    {
                        echo "Nem adott meg felhasználói nevet, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($password1 == "")
                    {
                        echo "Nem adta meg a jelszót, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($password2 == "")
                    {
                        echo "Nem erõsítette meg a jelszót, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($goodpass == false)
                    {
                        echo "A megadott e-mail cím nem hat karakter hosszú vagy nem tartalmaz legalább egy számot, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($electronicmail == "")
                    {
                        echo "Nem adott meg e-mail címet, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if ($goodemail == false)
                    {
                        echo "A megadott e-mail cím nem szabványos, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if (mysql_num_rows($query1) > 0)
                    {
                        echo "A felhasználó név már foglalt, kérem próbálja újra.";
?>
                        </br>
<?
                    }

                    if (mysql_num_rows($query2) > 0)
                    {
                        echo "A jelszó már foglalt, kérem próbálja újra.";
                    }

                    $username = " ";
                    $password1 = " ";
                    $password2 = " ";
                    $birthyear = " ";
                    $electronicmail = " ";
                    $birthmonth = "január";
                    $birthday = "1";
                    $goodemail = false;
                    $goodpass = false;
                }
            } 
?>
	</br>

	<input type=button value="vissza" size="6" onClick="document.location='index.php'">
    </body>
</html>