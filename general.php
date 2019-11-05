<?php

//connect to MySQL
$db = mysqli_connect('localhost', 'root') or 
    die ('Unable to connect. Check your connection parameters.');

// make sure you're using the right database
mysqli_select_db($db,'carsite') or die(mysqli_error($db));

// retrieve information
$query = 'select car_id, car_name, car_year, car_brand, cartype_label, carconfig_label
from car, cartype, carconfig 
where (car_type=cartype_id) and (car_configuration=carconfig_id) order by car_name ASC , car_year DESC';

$result = mysqli_query($db,$query) or die(mysqli_error($db));

$numcars = mysqli_num_rows($result);

?>
<div style="text-align: center;">
 <h2>Movie Review Database</h2>
 <table border="1" cellpadding="2" cellspacing="2"
  style="width: 70%; margin-left: auto; margin-right: auto;">
  <tr>
   <th>Car name</th>
   <th>Car year</th>
   <th>Car brand</th>
   <th>Car type</th>
   <th>Car configuration</th>
  </tr>
  
 <?php
// loop through the results
while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    echo '<tr>';
    echo '<td><a href="ratings.php?car_id=' . $car_id . '">' . $car_name . '</td>';
    echo '<td>' . $car_year . '</td>';
    echo '<td>' . $car_brand . '</td>';
    echo '<td>' . $cartype_label . '</td>';
    echo '<td>' . $carconfig_label . '</td>';
    echo '</tr>';
    
     
}     



?>
 </table>
<p><?php echo $numcars; ?> Cars</p>
</div>



