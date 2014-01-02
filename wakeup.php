<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>唤醒主机</title>
</head>
<body>
<form action="" method="get">
<label>ip/host</label>
<p>
  <input type="text" value="<?php echo @$_REQUEST['ip_or_host'];?>" name="ip_or_host"/>
</p>


<label>port</label>
<p>
  <input type="text" value="<?php echo @$_REQUEST['port'];?>" name="port"/>
  </p>

<label>mac</label>
<p>
  <input type="text" value="<?php echo @$_REQUEST['mac']; ?>" name="mac"/>
  </p>

<p>
  <input type="submit" value="提交" name="btn"/>
	
</p>
</form>
<a href="http://my.veryeast.cn/wakeup.php?ip_or_host=lijianwei123.vicp.net&port=800&mac=50-E5-49-E1-7A-37&btn=%E6%8F%90%E4%BA%A4">
唤醒家里电脑
</a>

<?php
if(strtolower($_SERVER['REQUEST_METHOD']) == 'get' && !empty($_GET)) {
        $port = @$_REQUEST['port'];
        $ip = $_REQUEST['ip_or_host'];
        $mac = @$_REQUEST['mac'];
        if(!$port || !$ip || !$mac) {
                echo "信息不完整.";
                exit;
        }
        echo wol_magic_packet($mac, $ip, $port) ? "成功" : "失败";
}
//@see http://www.php.net/socket-send
function wol_magic_packet($mac, $addr='255.255.255.255', $port = 0)
{
        if (!preg_match("/([A-F0-9]{2}[-:]){5}[A-F0-9]{2}/",$mac,$maccheck))
                return false;
        $addr_byte = preg_split("/[-:]/",$maccheck[0]);

        //Creating hardware adress
        $hw_addr = '';
        for ($a=0; $a < 6; $a++)//Changing mac adres from HEXEDECIMAL to DECIMAL
                $hw_addr .= chr(hexdec($addr_byte[$a]));
	
        //Create package data
        $msg = str_repeat(chr(255),6);
        for ($a = 1; $a <= 16; $a++)
                $msg .= $hw_addr;

        //Sending data
        if (
        function_exists('socket_create') AND //socket_create exists
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP) AND //Can create the socket
        $sock_data = socket_connect($sock, $addr, intval($port)) //Can connect to the socket
        ) {  //Then
                $sock_data = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1); //Set
                $sock_data = socket_write($sock, $msg, strlen($msg)); //Send data
                socket_close($sock); //Close socket
                return true;
        } else //Esle? :P
                return false;
}
?>

</body>
</html>