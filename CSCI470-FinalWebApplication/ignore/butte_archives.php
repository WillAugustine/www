<!DOCTYPE html>
<?php
    include_once('header.php');
    echo "You logged in!";
?>
<br>
<br>
<form action="create_new_user.php" method="post">
    <input type="submit" value="Create a User" />
    <input type="submit" formaction="view_feedback.php" value="View Feedback" />
</form>
<!-- <br>
<form action="view_feedback.php" method="post">
    <input type="submit" value="View Feedback" />
</form> -->
