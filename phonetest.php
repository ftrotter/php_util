<?php
require_once("PhoneFormat.php");

$phone = "+117138933361";

echo "original $phone for storage: ";
echo PhoneFormat::forStorage($phone);
echo "\nfor printing:";
echo PhoneFormat::forPrinting(PhoneFormat::forStorage($phone));

?>
