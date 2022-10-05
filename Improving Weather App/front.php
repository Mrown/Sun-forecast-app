<?php

//This section: Checks if city exists and fetches data for city/date
if (array_key_exists('submit', $_GET)) {
    //Checks on submit if field contains a correct city key -> else error 
    if (!$_GET['city']) {
        $error = "Sorry, please enter a valid city";
    }
    //If city exists, fetches api data from city key
    if ($_GET['city']) {
        ($apiData = file_get_contents(
            "https://api.ipgeolocation.io/astronomy?"
                . "apiKey=13d752d67766466ca8de0ccc095fa331&location="
                . urlencode($_GET['city'])
                . "&date="
                . urlencode($_GET['datepicker'])
        ));
        //Decoding JSON data to be in a readable array, then fetching 
        //sunrise,sunset from array and generating dayinfo via date() function.
        $weatherArray = json_decode($apiData, true);
        $sunrise = "<b>Sunrise : </b>" . $weatherArray['sunrise'];
        $sunset = "<b>Sunset : </b>" . $weatherArray['sunset'];
        $dayinfo = date(" l jS M Y", strtotime($_GET['datepicker']));

        //Creates a "day-in-the-week" number of the picked date (mo:1 tu:2 we:3 etc.)
        $daynumber = date("w", strtotime($_GET['datepicker']));
    }
}
?>


<!doctype html>

<head>
    <title>Improving Weather</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.rtl.min.css"
        crossorigin="anonymous">
</head>

<style>
<?php //Style for the HTML has been set in a seperate css file.
include './style.css';
?>
</style>

<body>
    <div class="centerconsole">
        <h1>Imporoving Sun Forecast App</h1>
        <!-- This is the city name input field-->
        <form action="" method="GET">
            <br></br>
            <label for="city">Enter city name here</label>
            <input type="text" name="city" id="city" placeholder="city name">
            <br></br>

            <!-- This is the datepicker input field-->
            <label for="datepicker">
                Pick date here:
                <input id="datepicker" type="date" min="2022-01-01" max="2022-12-30" name="datepicker"
                    placeholder="Today">
            </label>

            <!-- This is the submit button starting the if (array_key_exists('submit', $_GET)) function-->
            <button type="submit" name="submit" class="btn btn-success">Submit Request</button>
        </form>


        <div class="output">
            <?php
            //if we have sunrise variable set, echos out variables displayed below
            if (isset($sunrise)) {
                echo '<div class="container text-center"><br></br>
                    <div class="col" style="border:2px solid black; border-radius:5px">
                    ' . $_GET['city'] . $dayinfo . '
                    <br></br>
                    ' . $sunrise . '
                    <br></br>
                    ' . $sunset . '
                    </div>';
            }
            //if we have $error in getting 'city' information from 
            if (isset($error)) {
                echo '<div class="container text-center">
                    ' . $error . '
                    </div>';
            }
            ?>
        </div>

        <!--
This section: Checks if rest of $restofweekdays has been calculated,
then loops through api-fetches for this amount of days
-->
        <div class="container text-center"><br></br>
            <div class="row">
                <?php
                if (isset($daynumber)) {
                    for ($daynumber; $daynumber - 1 < 6; $daynumber++) {
                        ($weekData = file_get_contents(
                            "https://api.ipgeolocation.io/astronomy?"
                                . "apiKey=13d752d67766466ca8de0ccc095fa331&location="
                                . urlencode($_GET['city'])
                                . "&date="
                                . urlencode($_GET['datepicker']++)
                        ));
                        $weekArray = json_decode($weekData, true);
                        $wsunrise = "<b>Sunrise : </b>" . $weekArray['sunrise'];
                        $wsunset = "<b>Sunset : </b>" . $weekArray['sunset'];
                        $wdayinfo = date(" l jS M Y", strtotime($_GET['datepicker']));

                        echo '<div class="col" style="border:2px solid black; border-radius:5px">
                        ' . $_GET['city'] . $wdayinfo . '
                        <br></br>
                        ' . $wsunrise . '
                        <br></br>
                        ' . $wsunset . '
                        </div>';
                    }
                }
                ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
        </script>
    </div>

</body>

</html>