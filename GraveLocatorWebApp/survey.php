<!-- 'survey.php' allows the Regular User to leave us with their closing thoughts. 
     There are three fields on this page, each with an associated question, and an input field for the User's answers.
     Once the Regular User is done answering, they submit their answers and are taken to 'end.php', -->
<?php
session_start();
require_once('config.php');
$surveyID = "";

if(isset($_POST['surveyDone']) && isset($_POST['qOne'])){

    if(isset($_POST['qTwo']) && isset($_POST['qThree'])){

    $surveyID = md5(date('Y-m-d h:i:sa')." ".strval($_POST['qOne']));
    InsertSurvey($surveyID, $_POST['searchIDCarrier2'], $_POST['qOne'], $_POST['qTwo'], $_POST['qThree']);
    header('location: end.php');
    exit();

    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/survey.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div class = "outer-wrapper">       
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                <h3>Post Search Survey</h3>
                <p>Rate your search from 1-10</p>
                <input type="text" name="qOne" placeholder="0" required>
                <p>Would you recommend this web application to your friends?</p>
                <input type="text" name="qTwo" placeholder="answer" required>
                <p>Comments</p>
                <input type="text" name="qThree" placeholder="answer" required>
                <input type = "hidden" name = "searchIDCarrier2" value = "<?php echo $_POST['searchIDCarrier2'];?>">
                <input type="submit" name="surveyDone">
            </form>
        </div>
</body>
</html>