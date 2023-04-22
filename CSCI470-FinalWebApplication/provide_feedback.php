<?php

    session_start();
    $user_link = isset($_SESSION['user_link']) ? $_SESSION['user_link'] : "";

    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    // Create connection
    $conn = new mysqli($db_server, $db_username, $db_password, $db_database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT Feedback.userID FROM Feedback INNER JOIN Users ON Feedback.userID = Users.ID WHERE Users.uniqueLink = ?");
    $stmt->bind_param("s", $user_link);

    // Execute the prepared statement
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    // Check if any rows were returned
    if ($stmt->num_rows > 0) {
        // Feedback has been provided for this user's link
        $feedback_provided = true;
    } else {
        // No feedback has been provided for this user's link
        $feedback_provided = false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    include("header.php");

    if (empty($user_link)) {
        header("Location: login.php");
    }

    if (isset($_POST['submit'])) {

        // Create connection
        $conn = new mysqli($db_server, $db_username, $db_password, $db_database);
    
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo "Feedback recieved!<br>";
        $foundHeadstones = null;
        $recommend = null;
        $useAgain = null;

        if (!empty($_GET['headstones_found'])) {
            $foundHeadstones = ($_POST['headstones_found'] === "yes") ? true : false;
        }

        if (!empty($_GET['recommend'])) {
            $foundHeadstones = ($_POST['recommend'] === "yes") ? true : false;
        }

        if (!empty($_GET['use_again'])) {
            $foundHeadstones = ($_POST['use_again'] === "yes") ? true : false;
        }

        $comments = $_POST['comments'];
        $stmt = $conn->prepare("SELECT ID FROM Users WHERE uniqueLink = ?");
        $stmt->bind_param("s", $user_link);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentData = $result->fetch_assoc();
        $user_id = $currentData['ID'];

        $stmt = $conn->prepare("INSERT INTO Feedback(userID, headstoneFound, recommend, useAgain, comments) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $foundHeadstones, $recommend, $useAgain, $comments);
        if ($stmt->execute()) {
            echo "Feedback added to database!<br>";
        }
        else {
            echo $stmt->error;
        }

        $stmt->close();
        exit();

    } 

    if ($feedback_provided) {
        echo "You already provided feedback you silly goose!<br>";
        exit();
    }

?>

<div class="feedback-form">
    <form action="" method="post">
        <h4>1. Did you find the headstones you were looking for?</h4>
        <div class="yes-no-buttons" data-name="headstones_found">
            <button value="yes">Yes</button>
            <button value="no">No</button>
        </div>
        
        <h4>2. Would you recommend this website to others?</h4>
        <div class="yes-no-buttons" data-name="recommend">
            <button value="yes">Yes</button>
            <button value="no">No</button>
        </div>
        
        <h4>3. Would you use this website again?</h4>
        <div class="yes-no-buttons" data-name="use_again">
            <button value="yes">Yes</button>
            <button value="no">No</button>
        </div>

        <h4>4. Other comments:</h4>
        <textarea name="comments" rows="5" cols="30"></textarea><br>
        
        <input type="submit" formaction="visitor.php?id=<?php echo $user_link ?>" value="Back">
        <input type="submit" name="submit" value="Submit">
                
    </form>
</div>

<script>

    const buttonGroups = document.querySelectorAll('.yes-no-buttons');

    buttonGroups.forEach(group => {
        const buttons = group.querySelectorAll('button');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = group.dataset.name;
        group.appendChild(input);
        
        buttons.forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault();
                buttons.forEach(btn => btn.classList.remove('selected'));
                button.classList.add('selected');
                input.value = button.value;
            });
        });
    });

</script>