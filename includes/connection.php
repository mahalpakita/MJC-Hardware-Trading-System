<?php
 $db = mysqli_connect('localhost', 'u214863458_kylie', 'Lee353262') or
        die ('Unable to connect. Check your connection parameters.');
        mysqli_select_db($db, 'u214863458_prince' ) or die(mysqli_error($db));
?>