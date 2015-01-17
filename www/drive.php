<?php

function make_packet($data)
{
	$bytes = str_split($data, 2);
	$buffer = "\x55\xff\x00";
	$sum = 0;
	foreach ($bytes as $byte) {
		$byte = hexdec($byte);
		$sum += $byte;
		$buffer .= chr($byte) . chr($byte ^ 0xFF);
	}
	$sum %= 0x100;
	$buffer .= chr($sum) . chr($sum ^ 0xFF);
	return $buffer . str_repeat("\x00", 30);
}

if (isset($_REQUEST["time"]))
	$time = $_REQUEST["time"];
else
	$time = 1;

if (isset($_REQUEST["power"]))
	$power = $_REQUEST["power"];
else
	$power = 4;

if (isset($_REQUEST["direction"]))
	$dir = $_REQUEST["direction"];
else
	$dir = "forward";

$tower = fopen('/dev/usb/legousbtower0', 'r+');

fwrite($tower, make_packet("e185")); // Set motors to forward
if ($dir == "left")
	fwrite($tower, make_packet("e941")); // Invert left motor
elseif ($dir == "right")
	fwrite($tower, make_packet("e944")); // Invert right motor
elseif ($dir == "backward")
	fwrite($tower, make_packet("e945")); // Invert both motors

fwrite($tower, make_packet("1305020" . $power)); // Set power for both motors

fwrite($tower, make_packet("2185")); // Turn on both motors
usleep($time/3 * 1000000);
fwrite($tower, make_packet("2945")); // Turn off both motors
fclose($tower);

?>