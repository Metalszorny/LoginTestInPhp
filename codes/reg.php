<html>
    <head>
        <title>Regisztr�ci�</title>
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
	<div>A jelsz� legal�bb 6 karakter hossz� legyen �s tartalmazzon legal�bb egy sz�mot!</div>
	<form name="bejelentkezes" action="reg.php" method=post>
            felhaszn�l�n�v: <input type="text" name="user" value="<? echo $username; ?>" size="5" maxlength="15" class="reguserinput">
            </br>
            jelsz�: <input type="password" name="pass1" value="<? echo $password1; ?>" size="5" maxlength="15" class="regpsw1input">
            </br>
            jelsz� megint: <input type="password" name="pass2" value="<? echo $password2; ?>" size="5" maxlength="15" class="regpsw2input">
            </br>
            e-mail c�m: <input type="text" name="mail" value="<? echo $electronicmail; ?>" size="5" maxlength="30" class="regemailinput">
            </br>
            �v: <input type="text" name="EvValasztas" value="<? echo $birthyear; ?>" size="4" class="regyearinput">
            H�nap: <select name="HonapValasztas" value="<? echo $birthmonth; ?>" size="1">
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
<!--		neme: n�: <INPUT TYPE = "RADIO" name = "nem" value = "true"> f�rfi: <INPUT TYPE = "RADIO" name = "nem" value = "false">
		</br> -->
		<input name='registration' type='hidden'>
		<input type="submit" name="go" value="r�gz�t�s" size="6">
		</br>
		<input type="reset" name="new" value="t�rl�s" size="6">
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
                    $mail->Subject  =  'Sikeres regisztr�ci�';
                    $mail->IsHTML(true);
                    $mail->Body = "Kedves $username,<br><br>Sikeresen regisztr�lt�l a rendszer�nkben.<br>
                        K�rj�k regisztr�ci�dat er�s�tsd meg ezen a c�men: <a href='$link'>Ide kattints</a>";
                        $mail->AddAddress($electronicmail, $username);

                        if(!$mail->Send())
                        {
                            echo "Hiba: ".$mail->ErrorInfo;
                        }
                        else
                        {
                            echo "Az adatokat r�gz�tett�k, a megadott e-mail c�mre k�ld�nk egy levelet, a benne tal�lhat� linkre kattintva �rv�nyes�theti regisztr�ci�j�t.";
                        }

                    //$username = " ";
                    //$password1 = " ";
                    //$password2 = " ";
                    //$birthyear = " ";
                    //$electronicmail = " ";
                    //$birthmonth = " ";
                    //$birthday = " ";

                    //echo "Az adatokat r�gz�tett�k, a megadott e-mail c�mre k�ld�nk egy levelet, a benne tal�lhat� linkre kattintva �rv�nyes�theti regisztr�ci�j�t.";	
                }
		else
		{
                    if ($password1 != $password2)
                    {
                        echo "A k�t jelsz� nem egyezik meg, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($validdate == false)
                    {
                        echo "A megadott sz�let�si adatok valamelyike helytelen, k�rem pr�b�lja �jra, a hiba tov�bbi fenn�ll�sa eset�n l�pjen kapcsolatba vel�nk.";
?>
                        </br>
<?
                    }

                    if ($birthyear > $currentyear)
                    {
                        echo "A megadott sz�let�si �vsz�m nagyobb, mint a jelenlegi �v, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($birthyear < "1900")
                    {
                        echo "A megadott sz�let�si �vsz�m alapj�n t�bb, mint 100 �ves, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($username == "")
                    {
                        echo "Nem adott meg felhaszn�l�i nevet, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($password1 == "")
                    {
                        echo "Nem adta meg a jelsz�t, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($password2 == "")
                    {
                        echo "Nem er�s�tette meg a jelsz�t, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($goodpass == false)
                    {
                        echo "A megadott e-mail c�m nem hat karakter hossz� vagy nem tartalmaz legal�bb egy sz�mot, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($electronicmail == "")
                    {
                        echo "Nem adott meg e-mail c�met, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if ($goodemail == false)
                    {
                        echo "A megadott e-mail c�m nem szabv�nyos, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if (mysql_num_rows($query1) > 0)
                    {
                        echo "A felhaszn�l� n�v m�r foglalt, k�rem pr�b�lja �jra.";
?>
                        </br>
<?
                    }

                    if (mysql_num_rows($query2) > 0)
                    {
                        echo "A jelsz� m�r foglalt, k�rem pr�b�lja �jra.";
                    }

                    $username = " ";
                    $password1 = " ";
                    $password2 = " ";
                    $birthyear = " ";
                    $electronicmail = " ";
                    $birthmonth = "janu�r";
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