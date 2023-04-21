<link rel="stylesheet" href="styles.css">
<?php
    $topLeftLat = 45.985970;
    $topLeftLong = -112.545465;
    $bottomRightLat = 45.982065;
    $bottomRightLong = -112.536416;

    if (isset($_GET['id'])) {
        session_start();
        $_SESSION['visitor'] = true;
        $_SESSION['user_link'] = $_GET['id'];
        include('header.php');

        $user_link = $_SESSION['user_link'];

        if (isset($_REQUEST['feedback'])) {
            exit();
        }

        if (isset($_REQUEST['block_image'])) {
            
        }

        if (isset($_REQUEST['block_layout'])) {

        }

        if (isset($REQUEST['help'])) {

        }

        define("DB_SERVER", "localhost");
        define("DB_USER", "ButteArchives");
        define("DB_PASSWORD", 'password');
        define("DB_DATABASE", "CemeteryLocatorApplication");

        // connect to the database
        $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

        // Select data with block, lot, polt, name from ButteArchivesRecords
        if ($stmt = $conn->prepare("SELECT * FROM `Users` WHERE `uniqueLink`=?")) {
            $stmt->bind_param("s", $user_link);
        } else {
            die("Error: ". $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $currentData = $result->fetch_assoc();
        $user_firstName = $currentData['firstName'];
        $user_lastName = $currentData['lastName'];

        
        $headstoneColors = ['#D45991', '#E35F63', '#E37F26', '#E0B816', '#8FBC27'];

        // Get headstone names from HeadstonesForLinks and ButteArchivesRecords tables
        if ($stmt = $conn->prepare(
            "SELECT bar1.name AS name1, bar2.name AS name2, bar3.name AS name3, bar4.name AS name4, bar5.name AS name5
            FROM HeadstonesForLinks hfl
            LEFT JOIN ButteArchivesRecords bar1 ON hfl.headstoneID_1 = bar1.ID
            LEFT JOIN ButteArchivesRecords bar2 ON hfl.headstoneID_2 = bar2.ID
            LEFT JOIN ButteArchivesRecords bar3 ON hfl.headstoneID_3 = bar3.ID
            LEFT JOIN ButteArchivesRecords bar4 ON hfl.headstoneID_4 = bar4.ID
            LEFT JOIN ButteArchivesRecords bar5 ON hfl.headstoneID_5 = bar5.ID
            WHERE hfl.userLink=?"
        )) {
            $stmt->bind_param("s", $user_link);
            $stmt->execute();
            $result = $stmt->get_result();
            $headstoneData = $result->fetch_assoc();
            $headstoneNames = [
                isset($headstoneData['name1']) ? $headstoneData['name1'] : 'Headstone 1',
                isset($headstoneData['name2']) ? $headstoneData['name2'] : 'Headstone 2',
                isset($headstoneData['name3']) ? $headstoneData['name3'] : 'Headstone 3',
                isset($headstoneData['name4']) ? $headstoneData['name4'] : 'Headstone 4',
                isset($headstoneData['name5']) ? $headstoneData['name5'] : 'Headstone 5',
            ];
        } else {
            die("Error: " . $conn->error);
        }
    };
?>
<div class="cemetery-map">
    <h2>Welcome, <?php echo htmlspecialchars($user_firstName) . ' ' . htmlspecialchars($user_lastName) ?>!</h2>
    <div class="map-container">
        <!-- Add a container for the image and highlights -->
        <div id="cemetery-container">
            <!-- Add the image here -->
            <img src="images\Cemetery_BirdsEyeView.jpg" alt="Cemetery Image" class="cemetery-image" id="cemetery-image">
            <div class="legend">
                <ul>
                    <?php foreach ($headstoneNames as $index => $name) : ?>
                        <li><span class="icon" style="background-color: <?php echo $headstoneColors[$index] ?>;"></span> <?php echo htmlspecialchars($name) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>