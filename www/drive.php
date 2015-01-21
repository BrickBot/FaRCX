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

// Require direction to be set so just browsing to the page won't do anything
if (!$_REQUEST["direction"]) { die(); }

// Validate time
$time = (int) $_REQUEST["time"];
if ($time < 1 || $time > 3) { $time = 2; }

// Validate power
$power = (int) $_REQUEST["power"];
if ($power < 1 || $power > 7) { $power = 4; }

$tower = fopen('/dev/ttyS0', 'r+');

// Set motor directions
fwrite($tower, make_packet("e185")); // Set motors to forward
switch ($_REQUEST["direction"])
{
	case "left":
		fwrite($tower, make_packet("e941")); // Invert left motor
		break;
	case "right":
		fwrite($tower, make_packet("e944")); // Invert right motor
		break;
	case "backward":
	case "backwards":
		fwrite($tower, make_packet("e945")); // Invert both motors
		break;
}

fwrite($tower, make_packet("1305020" . $power)); // Set power for both motors

// Drive
fwrite($tower, make_packet("2185")); // Turn on both motors
usleep($time * 1000000);
fwrite($tower, make_packet("2945")); // Turn off both motors

fclose($tower);

?>