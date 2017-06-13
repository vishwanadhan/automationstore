<?php
session_start();
echo "---- Test Header New---- <br/>";
$headers = apache_request_headers();


echo "<pre>";
//print_r($headers);
print_r($_SESSION);
exit;

foreach (getallheaders() as $name => $value) {
    echo "<br>$name: $value\n";
}

echo "--- Response Headers --- <br/>";

print_r(apache_response_headers());

echo "--- Request Headers --- <br/>";

var_dump(headers_list());

?>