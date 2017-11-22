<?php
require_once('../config/config.php');

function generateRandomString() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return  $randomString;
}

if(isset($_POST['url'])){
    $url = mysqli_real_escape_string($dbc, trim($_POST['url']));

    $query = "SELECT url, code FROM links WHERE url='$url'";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if(mysqli_num_rows($result) == 0){
        if(!filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
            $url_code = generateRandomString();
            $url_short = $site . "/" . $url_code;

            $query = "INSERT INTO links VALUES (0, '$url', '$url_code', 0, 0, DATE_FORMAT(NOW(), '%d %b %Y'))";
            $result = mysqli_query($dbc, $query);

            if ($result) {
                echo $url_short;
            } else {
                echo "ERROR: Please try again later.";
            }

        } else {
        echo "ERROR: " . $url . " is not a valid URL";
        }
    } else {
        echo $site . "/" . $row['code'];
    }
}
