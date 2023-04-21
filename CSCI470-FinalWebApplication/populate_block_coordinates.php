<?php
    $db_server = "localhost";
    $db_username = "ButteArchives";
    $db_password= "password";
    $db_database= "CemeteryLocatorApplication";

    // Connect to the database
    $conn = new mysqli( $db_server, $db_username, $db_password, $db_database );

    // Check for errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM BlockCoordinates WHERE block = 1";
    $result = $conn->query($sql);
    if ($result->num_rows === 0) {

        // Open the xlsx file
        $handle = fopen('data\BlockCorners Finished v2.csv', 'r');

        // Skip the first row (header row)
        fgetcsv($handle);

        $prevBlock = "1";
        $SE_lat = 0.0;
        $SE_long = 0.0;
        $NW_lat = 0.0;
        $NW_long = 0.0;

        // Loop through the rows
        while (($data = fgetcsv($handle)) !== false) {
            $currBlock = $data[0];
            $blockChange = ($prevBlock !== $currBlock);
            if ($blockChange) {
                // Insert the data into the database
                $stmt = $conn->prepare("INSERT INTO BlockCoordinates (block, SE_lat, SE_long, NW_lat, NW_long) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sdddd", $prevBlock, $SE_lat, $SE_long, $NW_lat, $NW_long);
                $stmt->execute();
                $stmt->close();
                $prevBlock = $currBlock;
            }
            if ($data[1] === "SE") {
                $SE_lat = (float) $data[2];
                $SE_long = (float) $data[3];
            } else if ($data[1] === "NW") {
                $NW_lat = (float) $data[2];
                $NW_long = (float) $data[3];
            }

        }
        echo "Ending block: $prevBlock<br>";
        // Close the file
        fclose($handle);
    }

    $conn->close();
?>