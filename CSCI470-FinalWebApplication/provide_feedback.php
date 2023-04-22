<?php

    session_start();
    $user_link = isset($_SESSION['user_link']) ? $_SESSION['user_link'] : "";
    $feedback_complete = isset($_SESSION['feedback_complete']) ? $_SESSION['feedback_complete'] : false;

    if (empty($user_link)) {
        header("Location: login.php");
    }

    if (isset($_POST['submit'])) {
        $_SESSION['feedback_complete'] = true;

    } else {
        // Display the survey form
        echo '';
    }
?>

<form action="" method="post">
    <h4>1. Did you find the headstones you were looking for?</h4>
    <input type="radio" id="yes" name="headstones" value="yes">
    <label for="yes">Yes</label><br>
    <input type="radio" id="no" name="headstones" value="no">
    <label for="no">No</label><br>
    
    <h4>2. Would you recommend this website to others?</h4>
    <input type="radio" id="yes" name="recommend" value="yes">
    <label for="yes">Yes</label><br>
    <input type="radio" id="no" name="recommend" value="no">
    <label for="no">No</label><br>
    
    <h4>3. Would you use this website again?</h4>
    <input type="radio" id="yes" name="use_again" value="yes">
    <label for="yes">Yes</label><br>
    <input type="radio" id="no" name="use_again" value="no">
    <label for="no">No</label><br>
    
    <h4>4. Other comments:</h4>
    <textarea name="comments"></textarea><br>
    
    <input type="submit" formaction="visitor.php?id='.$user_link.'" value="Back">
    <input type="submit" name="submit" value="Submit">
            
</form>