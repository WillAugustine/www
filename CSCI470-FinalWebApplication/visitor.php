<link rel="stylesheet" href="styles.css">
<?php

    function createNewSession() {

        session_destroy();
        session_unset();
        session_abort();
        session_reset();
    
        session_start();
        $_SESSION['visitor'] = true;
        $_SESSION['user_link'] = $_GET['id'];
    }
    session_start();

    createNewSession();

    include('header.php');

    $user_link = $_SESSION['user_link'];

    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    $block_layout = 'false';
    $help = 'false';
    $block_records = 'false';

    if (isset($_GET['id'])) {

        if (isset($_REQUEST['feedback'])) {
            header("Location: provide_feedback.php");
        }

        $block_records = isset($_POST['block_records']) ? 'true' : 'false';
        
        $block_layout = isset($_POST['block_layout']) ? 'true' : 'false';

        $help = isset($_POST['help']) ? 'true' : 'false';

        // connect to the database
        $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );

        // Select data with block, lot, polt, name from ButteArchivesRecords
        if ($stmt = $conn->prepare("SELECT * FROM `Users` WHERE `uniqueLink`=?")) {
            $stmt->bind_param("s", $user_link);
        } else {
            die("Error: ". $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            header("Location: ./");
        }

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
                isset($headstoneData['name1']) ? $headstoneData['name1'] : '',
                isset($headstoneData['name2']) ? $headstoneData['name2'] : '',
                isset($headstoneData['name3']) ? $headstoneData['name3'] : '',
                isset($headstoneData['name4']) ? $headstoneData['name4'] : '',
                isset($headstoneData['name5']) ? $headstoneData['name5'] : '',
            ];
        } else {
            die("Error: " . $conn->error);
        }

        // Query the BlockCoordinates, HeadstonesForLinks, and ButteArchivesRecords tables to get the headstone data for the user
        if ($stmt = $conn->prepare(
            "SELECT bc.*, bar.block, bar.lot, bar.plot, bar.dateOfDeath
            FROM BlockCoordinates bc
            JOIN ButteArchivesRecords bar ON bc.block = bar.block
            JOIN HeadstonesForLinks hfl ON bar.ID = hfl.headstoneID_1 OR bar.ID = hfl.headstoneID_2 OR bar.ID = hfl.headstoneID_3 OR bar.ID = hfl.headstoneID_4 OR bar.ID = hfl.headstoneID_5
            WHERE hfl.userLink=?"
        )) {
            $stmt->bind_param("s", $user_link);
            $stmt->execute();
            $result = $stmt->get_result();
            $headstones = [];
            while ($row = $result->fetch_assoc()) {
                $headstones[] = [
                    'SE_lat' => $row['SE_lat'],
                    'SE_long' => $row['SE_long'],
                    'NW_lat' => $row['NW_lat'],
                    'NW_long' => $row['NW_long'],
                    'block' => $row['block'],
                    'lot' => $row['lot'],
                    'plot' => $row['plot'],
                    'DoD' => isset($row['dateOfDeath']) ? date('m-d-Y', strtotime($row['dateOfDeath'])) : "not provided"
                ];
            }
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
                        <?if (!empty($name)) : ?>
                            <li><span class="icon" style="background-color: <?php echo $headstoneColors[$index] ?>;"></span> <?php echo htmlspecialchars($name) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="visitor-overlay">

</div>


<script>
    var block_layout = (<?php echo json_encode($block_layout); ?> === 'true');
    var help = (<?php echo json_encode($help); ?> === 'true');
    console.log("block_layout = " + block_layout + " has type " + typeof(block_layout));
    console.log("help = " + help + " has type " + typeof(help));

    // Define an array of headstones with their latitude and longitude
    const headstones = <?php echo json_encode($headstones); ?>;

        // Define an array of colors to use for the headstones
    const headstoneColors = ['#D45991', '#E35F63', '#E37F26', '#E0B816', '#8FBC27'];

    // Get the cemetery container and image elements
    const cemeteryContainer = document.querySelector('#cemetery-container');
    const cemeteryImage = document.querySelector('#cemetery-image');
    const overlay = document.querySelector('.visitor-overlay');

    const headstoneInfo = document.createElement('div');
    headstoneInfo.classList.add('headstone-info');
    headstoneInfo.style.display = "none";
    cemeteryContainer.appendChild(headstoneInfo);

    function updateHeadstones() {
        
        // Remove all existing headstone elements
        const existingHeadstoneElements = document.querySelectorAll('.headstone');
        existingHeadstoneElements.forEach(headstoneElement => {
            headstoneElement.remove();
        });

        // Get the width and height of the cemetery image
        const imageWidth = cemeteryImage.offsetWidth;
        const imageHeight = cemeteryImage.offsetHeight;

        const topLeftLat = 45.985970;
        const topLeftLong = -112.545465;
        const bottomRightLat = 45.982065;
        const bottomRightLong = -112.536416;

        // Loop through each headstone
        headstones.forEach((headstone, index) => {
            // Create a new div element for the headstone
            const headstoneElement = document.createElement('div');
            headstoneElement.classList.add('headstone');

            // Calculate the x position and width of the headstone on the image
            const x1 = (headstone.NW_long - topLeftLong) / (bottomRightLong - topLeftLong) * imageWidth;
            const x2 = (headstone.SE_long - topLeftLong) / (bottomRightLong - topLeftLong) * imageWidth;
            const minX = Math.min(x1, x2);
            const width = Math.abs(x1 - x2);
            const x = ((minX + imageWidth * 0.05) + width/2)
            

            // Calculate the y position and height of the headstone on the image
            const y1 = (topLeftLat - headstone.NW_lat) / (topLeftLat - bottomRightLat) * imageHeight;
            const y2 = (topLeftLat - headstone.SE_lat) / (topLeftLat - bottomRightLat) * imageHeight;
            const y = Math.min(y1, y2);
            const height = Math.abs(y1 - y2);

            // Set the CSS properties for the headstone element
            headstoneElement.style.left = x + 'px';
            headstoneElement.style.top = y + 'px';
            headstoneElement.style.width = width + 'px';
            headstoneElement.style.height = height + 'px';
            headstoneElement.style.backgroundColor = headstoneColors[index % headstoneColors.length];

            // Append the headstone element to the cemetery container
            cemeteryContainer.appendChild(headstoneElement);
            headstoneElement.addEventListener('mouseover', () => {
                const table1 = document.createElement('table');
                table1.innerHTML = `
                    <tr>
                        <th>Block</th>
                        <th>Lot</th>
                        <th>Plot</th>
                    </tr>
                    <tr>
                        <td>${headstone.block}</td>
                        <td>${headstone.lot}</td>
                        <td>${headstone.plot}</td>
                    </tr>
                `;
                const table2 = document.createElement('table');
                table2.innerHTML = `
                    <tr>
                        <th>Date of Death</th>
                    </tr>
                    <tr>
                        <td>${headstone.DoD}</td>
                    </tr>
                `;
                headstoneInfo.innerHTML = '';
                headstoneInfo.appendChild(table1);
                headstoneInfo.appendChild(table2);
                headstoneInfo.style.display = 'block';
                headstoneInfo.style.left = (x) + 'px';
                headstoneInfo.style.top = (y - headstoneInfo.offsetHeight - 10) + 'px';
            });
            headstoneElement.addEventListener('mouseout', () => {
                headstoneInfo.style.display = 'none';
            });
        });
    }
    updateHeadstones();

    // Add an event listener for the resize event on the window object
    window.addEventListener('resize', updateHeadstones);

    if (block_layout) {
        const blockLayoutImage = document.createElement('img');
        blockLayoutImage.src = "images/Block_Layout.jpg";
        overlay.appendChild(blockLayoutImage);
    } else if (help) {
        const helpImage = document.createElement('img');
        helpImage.src = "images/help.png";
        overlay.appendChild(helpImage);
    }

    overlay.style.display = 'none';

    if (block_layout || help) {
        overlay.style.display = 'flex';
    }

    // Hide the overlay when the user clicks anywhere outside the overlay content
    window.addEventListener('click', (event) => {
        if (event.target === overlay) {
            overlay.style.display = 'none';
        }
    });

</script>

<?php
    if ($block_records === 'true') {
        include("display_block_records.php");
    }