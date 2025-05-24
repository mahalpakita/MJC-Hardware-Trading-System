<?php
// This function will handle the error and show a JavaScript alert.
function showError($errorMessage) {
    // Get the referring page URL, or fallback to the current page
    $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];

    echo '<script type="text/javascript">
            alert("Something went wrong: ' . addslashes($errorMessage) . '");
            window.location = "' . $previousPage . '"; 
          </script>';
}
?>
