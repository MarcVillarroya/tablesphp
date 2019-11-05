<?php
// function to generate ratings
function generate_ratings($rating) {
    $car_rating = '';
    for ($i = 0; $i < $rating-1; $i++) {
        
        $car_rating .= '<img style="object-fit: cover; object-position: 0px 0;" width="20" src="star.png" alt="star"/>';
    }
   // echo '<h1>'. $rating/1 . '</h1>';
    if(fmod($rating, 1) == 0){
       
        $car_rating .= '<img style=" object-fit: cover; object-position: 0px 0;" width="20" src="star.png" alt="star"/>';    
    }else{
       $residuo =  fmod($rating, 1);
       
       $total = ($residuo*10)/0.5;
       
        $car_rating .= '<img style="object-fit: cover; object-position:'.  (20-$total) .'px 0;" width="20" src="star.png" alt="star"/>';
    }
    
    return $car_rating;
}

// take in the id of a director and return his/her full name
/*function get_director($director_id) {

    global $db;

    $query = 'SELECT 
            people_fullname 
       FROM
           people
       WHERE
           people_id = ' . $director_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}*/

// take in the id of a lead actor and return his/her full name
/*function get_leadactor($leadactor_id) {

    global $db;

    $query = 'SELECT
            *
        FROM
            people 
        WHERE
            people_id = ' . $leadactor_id;
    $result = mysql_query($query, $db) or die(mysql_error($db));

    $row = mysql_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}*/

// take in the id of a car type and return the meaningful textual
// description
function get_cartype($type_id) {

    global $db;

    $query = 'SELECT 
            cartype_label
       FROM
           cartype
       WHERE
           cartype_id = ' . $type_id;
    $result = mysql_query($query, $db) or die(mysql_error($db));

    $row = mysql_fetch_assoc($result);
    extract($row);

    return $cartype_label;
}

// function to calculate if a car made a profit, loss or just broke even
function calculate_differences($takings, $cost) {

    $difference = $takings - $cost;

    if ($difference < 0) {     
        $color = 'red';
        $difference = '$' . abs($difference) . ' million';
    } elseif ($difference > 0) {
        $color ='green';
        $difference = '$' . $difference . ' million';
    } else {
        $color = 'blue';
        $difference = 'broke even';
    }

    return '<span style="color:' . $color . ';">' . $difference . '</span>';
}

//connect to MySQL
$db = mysqli_connect('localhost', 'root') or 
    die ('Unable to connect. Check your connection parameters.');
mysqli_select_db($db,'carsite') or die(mysqli_error($db));

// retrieve information
$query = 'SELECT car_id, car_name, car_year, car_brand, carconfig_label, cartype_label, price, sells, maxspeed
        
    FROM
        car, carconfig, cartype
    WHERE
        car_type=cartype_id and car_configuration=carconfig_id and car_id =' .  $_GET["car_id"] ;
$result = mysqli_query($db, $query) or die(mysqli_error($db));

$row = mysqli_fetch_assoc($result);
$car_name         = $row['car_name'];
$cartype_label     = $row['cartype_label'];
$car_brand    = $row['car_brand'];
$car_year         = $row['car_year'];
$carconfig_label = $row['carconfig_label'];
$sells     = $row['sells'];
$price         = $row['price'];
$maxspeed       = $row['maxspeed'];

// display the information
echo <<<ENDHTML
<html>
 <head>
  <title>Details and Reviews for: $car_name</title>
  <style>
  .tabla table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

.tabla th, td {
  text-align: left;
  padding: 16px;
}

.tabla tr:nth-child(even) {
  background-color: #f2f2f2;
}
  </style>
  
 </head>
 <body>
  <div style="text-align: center;">
   <h2>$car_name</h2>
   <h3><em>Details</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 70%; margin-left: auto; margin-right: auto;">
    <tr>
     <td><strong>Title</strong></strong></td>
     <td>$car_name</td>
     <td><strong>Year</strong></strong></td>
     <td>$car_year</td>
    </tr><tr>
     <td><strong>car type</strong></td>
     <td>$cartype_label</td>
     <td><strong>Price</strong></td>
     <td>$price<td/>
    </tr><tr>
     <td><strong>Car Brand</strong></td>
     <td>$car_brand</td>
     <td><strong>Sells</strong></td>
     <td>$sells<td/>
    </tr><tr>
     <td><strong>Configuration</strong></td>
     <td>$carconfig_label</td>
     <td><strong>max Speed</strong></td>
     <td>$maxspeed<td/>
    </tr>
   </table>
ENDHTML;
switch ($_GET['order']) {
    case "date":
        $orderdatabase = 'date';
        break;
    case "reviewer":
        $orderdatabase = 'reviewer';
        break;
    case "comments":
        $orderdatabase = 'comment';
        break;
    case "rating":
        $orderdatabase = 'rating';
        break;
    default:
        $orderdatabase = 'date';
}
// retrieve reviews for this car
$query = 'SELECT
        rate_id, date, reviewer, comment,
        rating, refid
    FROM
        rating
    WHERE
        refid = ' .  $_GET['car_id'] .'
    ORDER BY
        '. $orderdatabase .' DESC';

$result = mysqli_query($db, $query) or die(mysqli_error($db));

// display the reviews
$carid = $_GET['car_id'];

echo <<<ENDHTML
   <h3><em>Reviews</em></h3>
   <table class="tabla" cellpadding="2" cellspacing="2"
    style="width: 90%; margin-left: auto; margin-right: auto;">
    <tr>
     <th style="width: 7em;"><a href="ratings.php?car_id=$carid&order=date">Date</a></th>
     <th style="width: 10em;"><a href="ratings.php?car_id=$carid&order=reviewer">Reviewer</a></th>
     <th><a href="ratings.php?car_id=$carid&order=comments">Comments</a></th>
     <th style="width: 5em;"><a href="ratings.php?car_id=$carid&order=rating">Rating</a></th>
    </tr>
ENDHTML;
 $ratingsum = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $reviewer = $row['reviewer'];
    $comment = $row['comment'];
    $rating = generate_ratings($row['rating']);
    $ratingsum = $ratingsum + $row['rating'];
    echo <<<ENDHTML
    <tr>
      <td style="vertical-align:top; text-align: center;">$date</td>
      <td style="vertical-align:top;">$reviewer</td>
      <td style="vertical-align:top;">$comment</td>
      <td style="vertical-align:top;">$rating</td>
    </tr>
    
ENDHTML;
}
$total = mysqli_num_rows($result);
$media = $ratingsum / $total;
 echo '<h3> LA MEDIA ES:' . generate_ratings($media) . '</h3>';
echo <<<ENDHTML
  </div>
 </body>
</html>
ENDHTML;
?>

