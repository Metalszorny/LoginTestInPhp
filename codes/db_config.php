<?
    $kapcsolat = mysql_connect("server", "username", "password");
    mysql_select_db("tablename", $kapcsolat);
    mysql_query("SET CHARACTER SET 'utf8'");
    mysql_query("SET COLLATION_CONNECTION='ut8_hungarian_ci'");
	
    function clean_string($value, $DB)
    {
        if (get_magic_quotes_gpc())
            $value = stripslashes($value);

        return mysql_real_escape_string($value, $DB);
    }

    function clean_int($i)
    {
        if (is_numeric( $i ))
            return $i;
        else
            return false;
    }
	
    function check_email_address($email) 
    {
        return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
    }

    function generateRandomString($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
?>