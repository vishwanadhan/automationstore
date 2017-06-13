<?php

function parseRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

echo "<pre>";
print_r( apache_request_headers() );

exit;


header('username: admin');
header('password: '.md5('admin'));
echo "<pre>";

/*
$headers = array();
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
    }
}
exit;
print_r($headerArray);
*/
echo "##############################################"."br";


print_r( apache_request_headers() );
//print_r(getallheaders());




/* $path = $_SERVER['DOCUMENT_ROOT']; // get upload folder path
$file = $path."/WorkFlowStore/css.zip";  // any file
echo md5_file($file); */
?>