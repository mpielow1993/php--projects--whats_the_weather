<?php
  if (array_key_exists('city', $_GET)) {
      //include("assets/keys.php");
      require("vendor/autoload.php");
      //require(__DIR__."/dotenv/vendor/vlucas/phpdotenv/src/Dotenv.php");

      $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
      $dotenv->load();
      //echo getenv('WHATS_THE_WEATHER_API_KEY');
      //var_dump($dotenv);
      //echo getenv($WHATS_THE_WEATHER_API_KEY);
      $url = "http://api.openweathermap.org/data/2.5/weather?q=".urlencode(ucwords($_GET["city"]))."&APPID=".getenv('WHATS_THE_WEATHER_API_KEY');
      $url_headers = @get_headers($url);

      if (!$url_headers || $url_headers[0] == 'HTTP/1.1 404 Not Found') {
          $weatherForecast = '<div class="alert alert-danger">Not Found</div>';
      } else {
          $urlContents = file_get_contents($url);
          //Converts JSON to an array in PHP
          //2nd argument set to 'true' will return the data as an associative array
          $weatherArray = json_decode($urlContents, true);
          //echo $weatherArray["cod"];

          //print_r($weatherArray);

          $attributes = array(
          "weatherDescription" => ucfirst($weatherArray["weather"][0]["description"]).".",
          //echo $weatherDescription;
          "tempInCelsius" => ((float)ucfirst($weatherArray["main"]["temp"]) - 273.15)."&deg;C.",
          //echo $tempInCelsius;

          "windSpeed" => ((float)$weatherArray["wind"]["speed"])."m&#47;s."
          //echo $windSpeed;
        );

          $weatherForecast = "";
          foreach ($attributes as $key => $value) {
              $weatherForecast .= "<p><strong>".ucwords(preg_replace('/[A-Z]/', " $0", $key)).":&nbsp</strong>".$value."</p>";
          }
          $weatherForecast = '<div class="alert alert-success">'.$weatherForecast.'</div>';
      }
  }


  //echo ucfirst($_GET["city"]);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap-jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <style>

    /*
      .container {
        width: 100%;
        height: auto;
        padding: 0;
      }

      .row {
        width: 100%;
        height: auto;
        margin: 0;
        padding-top: 1em;
      }

      .col-md-10.col-md-offset-2 {
        background-image: url("sunset.jpg");
      }

      */

      html {
        background: url("assets/images/sunset.jpg") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
      }

      body {
        background: none;
      }

      .container {
        margin-top: 3em;
        margin-left: auto;
        margin-right: auto;
        width: 60%;
        text-align: center;
      }

      /*
      .btn.btn-primary {
        width: 60%;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
      }

      #button_div {
        width: 60%;
        margin-left: auto;
        margin-right: auto;
      }

      */

      h1 {
        margin-bottom: 1em;
      }

    </style>
  </head>
  <body>
    <div class="container">
      <h1>What's the weather?</h1>
        <form id="weatherForm" method="get">
          <div class="form-group">
            <label for="city">Enter the name of a city</label>
            <input type="text" class="form-control" name="city" id="city" placeholder="E.g. London, Paris, Istanbul...">
          </div>
            <div id="result">
              <h2><strong>Weather for: </strong><?php echo ucwords($_GET["city"]); ?></h2>
              <?php
                echo $weatherForecast;
              ?>
            </div>
            <div class="form-group">
              <button type="submit" id="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
  </body>
  <script>
    $('#submit').click(function(e) {
      $('#weatherForm').submit();
    });
  </script>
</html>
