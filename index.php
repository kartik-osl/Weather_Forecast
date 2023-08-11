<?php
if (isset($_POST['location'])) {
    $location = $_POST['location'];
    $fetchlocation = "http://api.openweathermap.org/geo/1.0/direct?q=$location&limit=1&appid=e710d74d87dfb77e06dd712429edf50f";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fetchlocation);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $location_data = json_decode($response, true);
    $lat = $location_data[0]['lat'];
    $lon = $location_data[0]['lon'];
    // echo round($lat,2) . " " . round($lon, 2) . "<br>";

    if ($lat != 0 && $lon != 0) {

        $api_key = "e710d74d87dfb77e06dd712429edf50f";
        $api_url = "http://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$api_key";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($data) {
            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Weather App</title>
                <link rel="stylesheet" href="index.css">
            </head>

            <body>
                <div class="container">
                <button><a href="userinput.php">Check Another City</a></button>
                <!-- <a href="index.php">Home</a> -->
                <?php
                $loc = $_POST['location'];
                echo "<table>";
                echo "<tr><td colspan='6'><h1>WEATHER DETAILS OF <span style='color: red'>" . strtoupper($loc) . "</span> (Lat:" . round($lat, 2) . ", Lon:" . round($lon, 2) . ")" . "</h1></td></tr>";
                echo "<tr>";
                echo "<td><strong>Serial number<strong></td>";
                echo "<td><strong>Date & Time<strong></td>";
                echo "<td><strong>Temperature<br>(in &degC)<strong></td>";
                echo "<td><strong>Wind speed<br>(in m/s)<strong></td>";
                echo "<td colspan='2'><strong>Weather<strong></td>";
                echo "</tr>";

                $counter = 0;
                while ($counter != 10) {
                    echo "<tr>";
                    echo "<td>" . $counter + 1 . "</td>";
                    echo "<td>" . $data['list'][$counter]['dt_txt'] . "</td>";
                    echo "<td>" . $data['list'][$counter]['main']['temp'] - 273 . "</td>";
                    echo "<td>" . $data['list'][$counter]['wind']['speed'] . "</td>";
                    echo "<td>" . $data['list'][$counter]['weather'][0]['main'] . "</td>";
                    $weather = $data['list'][$counter]['weather'][0]['main'];
                    switch($weather){
                        case "Rain":
                            echo "<td><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWklEQVR4nO2Xz2vUQBTHU/AgokcFLzWDHvwf/PVPqPQg6EkQbEEQQa968yK1idSDV9t/QNx5K3sRPUkrFATpe6vQVauidrJg9010JC3W1t002exOOoV84LEsG958v/u+k0k8r6KioiKL52/MASCeAOJngPxBEnPyCcl34vHGgtnvuUq9GY8B8TKQNmkliT9Jis97riGRb0nk39uJh78mkuuQb5Ymro58SiLPAHILiDsSeUkiPwbkk2u/N+OxvOJhkwnrk2g0zB5A/WBbMagfJrHoRzxsFC8ne8aagUzxQyiJfNWK+BrxadviYX1TgxUDgDxbhgFAbtky0CrFAPGqHQPEnXImoIczgdqiOQSk7wDqOUBuS+z0dVsccB98AdKPnjZXjxcSLyk+J4lVWYIh3YhOHjf6F9/nIWQ/Vnwld2xc+Ofh/0kg/wQ0o5kG1jLvgGDoOQV9N9sA6vkdF0opUyC9kCP/7sUHNibA7d1u4FueCM25a0C/zJ4A6ts7LpR6V534xm6+jX7M/Z5QR33WrYOMO5L4TC7xm00AceSA+Pe1RT7hFeHJW3OwhvpesvuBSpoI8i8gXqlTDEB8udE0ewuJr3CVY9M/jvphNCECNSuC6IWrPbdizIgfti+KQM2LMDL/Ss14LvVMww/a17cusl5+EI271DMVEailXouNTn4XnkM9UxFh9LV7MfVqkFwX6VkYP1D3uxYLogtdFxozcmRq5ZIfqtdZuc7dcxgcnjb7RKgm/TD6LEL1TkxF1wbNdd6epVJqrm1Qaq5tUGqubeBkrisqKio8m/wBuRuM8aYnfRUAAAAASUVORK5CYII='></td>";
                            break;        
                        
                        case "Clouds":
                            echo "<td><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADC0lEQVR4nO1YO28TQRAeCArvjkCBaJCQkho6QCCokIgoeIifQEHooeFZIESbitQJooPCkF1HDggFioSL7L0ERdkNROGRBoED8e6sk0XnPHyY8+Mc9nwW/qSRbXm1+r6Zb2ZOB9BCCy3UhdeTZjcVeI0KHKIcPxOB6H0WfgvsSTGzC+KK5Ez+MhU4T4U25YII/EpE/hLEDYTjDcJxuRL5dRHeOY7XIU6ZJzWS94tYq0SS43HC8THl+IkKVITjHOE4QDkes07e87RnizDki4HzdFr3Vbac7k2lzBZrAlYath7yIaoldK9NAUO2BVChzeA0HrUigHD8EoUAKrDfigCv6aIQQDjO2RFQWFKRVED+M9KD02YvFfoe5dqhHPORVECgohx/roR+6231xJTZGpo8EfmLRGA2mqzrysG1k5w1+8ORD7mwohCRqKUSnm1ik3lRYi+OV6sKKHg+BmRpcBXeVBfA9XhkGRXe85BaCnE+W4P/caHhmRblAn/UIiDb7BZyGk5UbKCJCdd3Y5p95wkz7bEeo6PdaFxQ6/GqD+tbZEmuL0S9yFJEG7a5SH7slNKE6xHPNjVlPkiE1/WNyD4DuZiG3EHYKBJTpoNwfYdwPeaN18KI5TpvO/sM5E2whXqmFOG4GML7fAbMNmsCwk6pl/1/NubYafw7+5uK/2dAngWbCDulnCNKr1ujTerhhHbW7Ei4HnE6cdQvsBgSGVh63VLrlBruV8slxG7578mA7GIgl4IEMMhVX1obFVF5SuH3dIdkPlIfR8HsKHefC+qhr4kHIAoETSnvO+X69rsuec6f0QzI7kp3uaAmVslnxsHsjERAFUIpn4AXlc5mYPHAKvmsZ6voWJYllDsZ3JgFko9Kz7uAVxjI5QzI8xAHlGTfP1nGZ8FsDzj/1AV1H+IGF+QhH/lvQY8HDEy7C+pZCiy+2K0XDHI9q7ZZckGeCTqTBtmZhoV9EEe4oJ5bf7axBc/rLshfDBQxYNqg2eCCPMNAfXgPZg80IxioB5OAh6FZMQF4otEcWmjhf8BvgT9tquUPYcoAAAAASUVORK5CYII='></td>";
                            break;      
                        
                        case "Clear":
                            echo "<td><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABsUlEQVR4nO2ZzU7CQBSFJ8TUN9CFuMcVpobQxAdzKUvfgIlGA76E7uARZCkYSFhUkz7EMddWLWM7xeH2Z8jc5CQESjlfO733TBDClStXrva2MBQDDMWNsNa8FPiSbRBIm7cNAlnmbYGAznwTIFBkjknCAeRU4dV7OACej4HZGbD0gY8gFr2edYCno/iYxt2BuxYwbQNhAESXetExk3b8HdkEgJEHzLvFxlW9nQOPhzUDjD1g3fu/+W+te/E5agGgJbAwuPKq5l3gtlUDwPR0d/NRInomygbYGFLUSbZ5YLdVGGx2J+5h92fCUqvkMh8lohZbxsTOjAfU57kBXjr8sSM326wu+AGWPm920gazd8b1HyWic3IGQEhxVSlAqAGQ4trsLuRBrPzqlpA0NK+FoGBWxUMsdzSfC1FFG5VM5jMh2AdZX43ZvOZTEL8/QuOfC2ByUlOYM4nRql7rCnNccXrkWbqhWTRhQyNNtpT9+PlRlg1qBUh3J2qHNCdoMNHEJtFr6vP02X0TN/WSV8IBGJY2AJY9pCqCaLb5Agg7zOdA2GVe+Ytp8POGK1euXO1dfQIP3ciBFRe34QAAAABJRU5ErkJggg=='></td>";
                            break; 
                        case "Snow":
                            echo "<td><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFXUlEQVR4nO1ZXWhcRRTeWPyj/ov/BduqID74S6kgPmh220IVzV0T8Ie+qWDvTSxFK1iNWLO7d2eSdNugbnZnViNCu75YfOyTwVqJSrFq2wR9sIK1VK1VYjXN7Cfn7tzN5Ho36ebPTciBYe/OnDvzfTNnZs45NxJZlEVZlFkTLgEqkfkqfD4RANDQWcSFkxEgHdKN1JMAaGASPVxgoDOHK6oRSGVxKRPYxwRy7e04J1IvkhG4ikl87wEW+CLxLq4MEqA6aqP/pEvvROpJ0gVcywS+0QAPuXlc7xPYnsM1XOArr01gMNWLZf8b0M4cbi4WsSSsjYAygYM+UJ9AhZjAYSIW9m6xiCXU96yCZxJ7NJDV1XTINLjAAR98pQgcmMhsmMBqvXp7ZpNAjx5k60R63QVcxgT2G+Ar+6KacIFXtO7OmQErwNIFPG2aS1qgSYP62NSNJtATc3GmMYluv66nBxe9mgHadwCZ93BJRTeJTNTFSDSFdIBAv9d3AY+OMyuJZ5nEm7WCv41LKA32ay6x1pjZUSYxYoKKEaA0EE1hxOynpa0EKmYdEQ3qunlcTH1S38n3cblHSGKtHptMtuRK3F0bCYmHucB3hh3v7RS4g0t8ppf6IV+XZj6WQmn9yyU0OVjn18edEqhU/reicf1WgHRjHWOrxWisMtD9vIBbmUDRML8fOgU2TOnyy2ZxLhd4hkmc0B0q/5ThEhlT17LxkgfYVkPrHJwfJNDcjvPitjpUrsOWcZMlsEODHeASZzSZk0xiS3sBF0SmK94lJJGhZfZnJpXDKR9oNYAmgTCCJPSczOF45cilMQR2Trbpz1q6sriOlpCWlUn8bh6NT2xWR+MOWiKR8vKSiZRBq2HrOdzoE2hysMxy1J/6WZsYGujdxzero2afTGKYzJVLtLFerJgycJbHC1xgKOQ8P97xNgZat6kfWzaVAcZtNRC3cT+9ZzmqSHWWrXb7BMw60mlqxSrLUf1UR304r6tjb7yFfuo7ZLwhwlI7AQlXd/IHzQjZI38H9/ibiZyxx2xsiNvqJwPoR5aN+/zZHl/UcJODe+O26os7qqQJnbA2oq25eeyo7hJY6e072sgCv2gSqdoJ9GIFz+OuybzGpzZjKdm8D9py1Ihlq30hBPZajjpdMTFbpZ50xo7iMKGxCcO0TMmXB9K4qdHFpsYkVoa1N2/CDXFbZS1HjerZ9X41Kf2sSmROj7Rh+VTGmJY0pjFEF9CaDgRmdubKmgTgXXIuBiMzLdQpdb42MYsEOjSBNI7MOAFa1mgaz8dYuD0GTSi8TGxCjZOMMXeb2FG/BcFb9uivc7qJ2dSPUebfuMbMHy7XITF3x2i++kWWyOLzsIsseOOOzT6i5g0dvMhat+FYIouDM3qRhboSAqcmdCVs9YEGuus/vlDgNp51V8KU7gKWM4Fd5Jf7AyVzOGk6ZcFZDhII94cinjPn5sdNzJkZc+ZYH5Yygde4wGnt4v7FJD4NutOeJ6rt3LLxYrV4oJpHyrQ77eWMJP423elMZkzvrIU2qGc2Ej/7ERGZEK1EWEBTAeaoQRNYkIBJ1IwJmBHQuBK3cIEPjRU5Qu01EUgXcGclpBToZxKrqoWUFOPGXJQo0rI2lkPPiUJKMjUvKnNLiCWwvVpIySQeNHJJpXQOt9dEggl0dOYRH0csJKivFg9TGpECeir0PFlczKsE9ZRY4BK8JvC1pFViHdjpAUqgy6+jlaqYWnkVvzQ3JcXQURf/RJNwZy2tsiATW7WkFmnz1V1qcbLkLpf4Niy5y/pwdd0kdxdkeh01fODgAp/U3QeOef+JaUF85FuQBBZlUSL1L/8C3VPsLcvzgKcAAAAASUVORK5CYII='></td>";       
                    }
                    echo "</tr><br>";
                    $counter++;
                }

                ?>
                </table>
                </div>
            </body>

            </html>
            <?php
        }
    }
    else{
        echo "Wrong location";
        header("location: userinput.php");
    }
} else {
    header("location:userinput.php");
}
?>