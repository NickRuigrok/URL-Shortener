<?php
require_once('config/config.php');
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>URL Shortener</title>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,500,900" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <h1 id="header" class="animated bounceInDown"><b style="font-weight: 900;">URL</b> SHORTENER</h1>
            <form method="post" action="ajax/process.php" class="ajax">
                <input type="url" class="url-input animated bounceInLeft" name="url" placeholder="Enter your URL..." autocomplete="off" autofocus required/>
                <input type="submit" class="url-shorten animated bounceInRight" value="SHORTEN"/>
                <input type="button" class="url-copy" value="COPY"/>
            </form>
        </div>
        <div class="info-box animated bounceInUp">
            <div id="info-popular">
                <h1 id="most-used">MOST <b style="font-weight: 900;">CLICKS</b></h1>
                <table id="info-popular-table">
                    <tr>
                        <th>URL</th>
                        <th>Redirect</th>
                        <th>Clicks</th>
                        <th>Last Visited</th>
                        <th>Created</th>
                    </tr>
                    <?php
                    $query = "SELECT * FROM links ORDER BY clicks DESC LIMIT 10";
                    $result = mysqli_query($dbc, $query);
                    while($row = mysqli_fetch_array($result)) {
                        $target = $site . "/" . $row['code'];
                        $clicks = $row['clicks'];
                        $lastvisit = $row['lastvisit'];
                        $created = $row['created'];
                        $url = $row['url'];
                        $url = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
                        $url_name = preg_split('/(?=\.[^.]+$)/', $url);
                        $url = ucfirst($url_name[0]);


                        echo "<tr>
                                <td><a href='$target' target='_blank'>$target</a></td>
                                <td>$url</td>
                                <td>$clicks</td>
                                <td>$lastvisit</td>
                                <td>$created</td>
                              </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>

<?php
if(isset($_GET['url']) || !empty($_GET['url']))
{
    $url_code = $_GET['url'];

    $query = "SELECT url FROM links WHERE code='$url_code'";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if(mysqli_num_rows($result) == 1)
    {
        $query = "UPDATE links SET clicks=clicks+1, lastvisit=DATE_FORMAT(NOW(), '%T %d %b %Y') WHERE code='$url_code'";
        $result = mysqli_query($dbc,$query);
        header('Location:' . $row['url']);
    } else {
        header('Location: index.php');
    }
}