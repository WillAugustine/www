<!DOCTYPE html>
<?php
    include_once('header.php');

    function getRandomString($n)
    {
        $possibleChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($possibleChars) - 1);
            $randomString  .= $possibleChars[$index];
        }
        return $randomString;
    }

    define("DB_SERVER", "localhost");
    define("DB_USER", "ButteArchives");
    define("DB_PASSWORD", 'password');
    define("DB_DATABASE", "CemeteryLocatorApplication");

    if (isset($_REQUEST['submit_headstone'])) {
        if (isset($_SESSION['headstone_index'])) {
            $_SESSION['headstone_index'] += 1; 
        } else {
            $_SESSION['headstone_index'] = 0;
        }
        $block = isset($_POST['block']) ? $_POST['block'] : '';
        header("Location: highlight_record.php?block=$block");
    }


    if (isset($_REQUEST['add_headstones']))
    {
        $visitor_name = isset($_SESSION['visitor_name']) ? $_SESSION['visitor_name'] : "the user";
        $index = isset($_SESSION['headstone_index']) ? $_SESSION['headstone_index'] : 0;
        $block = isset($_SESSION['block']) ? $_SESSION['block'] : "";
        echo '
        <div class="visitor_information">
            <h3>Where would '.$visitor_name.' like to visit?</h3>
            <p>Current headstones: '.$index.' / 5
            <form action="create_new_user.php?submit_headstone" method="post">
                
                <div class="form-group">
                    <label for="name"><span class="required">Name</span>:</label>
                    <input type="text" name="name" id="name" required />
                </div>
                <div class="form-group">
                    <label for="block"><span class="required">Block</span>:</label>
                    <div class="input-group">
                        <input type="text" name="block" id="block" required readonly/>
                        <span class="input-group-addon" onclick="showOverlay()">&#128269;</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lot">Lot:</label>
                    <input type="text" name="lot" id="lot" />
                </div>
                <div class="form-group">
                    <label for="plot">Plot:</label>
                    <input type="text" name="plot" id="plot" />
                </div>
                <div class="form-group">
                    <label for="dateOfDeath">Date of Death:</label>
                    <input type="date" name="dateOfDeath" id="dateOfDeath" />
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="text" name="age" id="age" />
                </div>
                <div class="form-group">
                    <label for="undertaker">Undertaker:</label>
                    <input type="text" name="undertaker" id="undertaker" />
                </div>
                <div class="required-text">* means required</div>
                <input type="submit" value="Add Headstone" />
            </form>
        </div>
        <div id="overlay" style="display: none;">
            <div id="overlay-content">
                <input type="text" id="overlay-search" placeholder="Search for a block..." />
                <ul id="overlay-block-list"></ul>
            </div>
        </div>
        <script>
            function showOverlay() {
                document.querySelector("#overlay").style.display = "block";
                fetch(`get_blocks.php`)
                    .then(response => response.json())
                    .then(blocks => {
                        const blockList = document.querySelector("#overlay-block-list");
                        blockList.innerHTML = "";
                        blocks.forEach(block => {
                            const li = document.createElement("li");
                            li.textContent = block;
                            li.addEventListener("click", () => {
                                document.querySelector("#block").value = block;
                                hideOverlay();
                            });
                            blockList.appendChild(li);
                        });
                    });
            }
            
            function hideOverlay() {
                document.querySelector("#overlay").style.display = "none";
            }
            
            document.querySelector("#overlay-search").addEventListener("input", () => {
                const value = document.querySelector("#overlay-search").value;
                if (value) {
                    fetch(`get_blocks.php?search=${value}`)
                        .then(response => response.json())
                        .then(blocks => {
                            const blockList = document.querySelector("#overlay-block-list");
                            blockList.innerHTML = "";
                            blocks.forEach(block => {
                                const li = document.createElement("li");
                                li.textContent = block;
                                li.addEventListener("click", () => {
                                    document.querySelector("#block").value = block;
                                    hideOverlay();
                                });
                                blockList.appendChild(li);
                            });
                        });
                } else {
                    showOverlay();
                }
            });
        </script>';
        exit();
    }

    //echo sha1('aslam');
    if (isset($_REQUEST['attempt']))
    {
        $newUserCode = getRandomString(25);

        $user_firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
        $user_lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $user_DoV = isset($_POST['dateOfVisit']) && !empty($_POST['dateOfVisit']) ? $_POST['dateOfVisit'] : null;


        // connect to the database
        $conn = new mysqli( DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE );
        if ( $conn->connect_error ) exit( 'connection failed: ' . $conn->connect_error );
        $stmt = $conn->prepare("INSERT INTO HeadstonesForLinks (userLink)
            VALUES (?)");
        $stmt->bind_param("s", $newUserCode);
        $stmt -> execute();
        echo $stmt->error;
        $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, dateOfVisit, uniqueLink)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $user_firstName, $user_lastName, $user_email, $user_DoV, $newUserCode);
        if ( $stmt->execute() ) {
            $_SESSION['user_link'] = $newUserCode;
            $_SESSION['headstone_index'] = 0;
            $_SESSION['visitor_name'] = $user_firstName;
            header("Location: create_new_user.php?add_headstones");
            
        }
        else {
            echo $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }


?>

<div class="visitor_information">
    <h3>Input visitor information:</h3>
    <br>
    <form action="create_new_user.php?attempt" method="post">
        <!-- <pre> -->
            <div class="form-group">
                <label for="firstName"><span class="required">First Name</span>:</label>
                <input type="text" name="firstName" id="firstName" />
            </div>
            <div class="form-group">
                <label for="lastName"><span class="required">Last Name</span>:</label>
                <input type="text" name="lastName" id="lastName" />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" />
            </div>
            <div class="form-group">
                <label for="dateOfVisit">Date of Visit:</label>
                <input type="date" name="dateOfVisit" id="dateOfVisit" value="<?php echo date("Y-m-d"); ?>" />
            </div>
            <div class="required-text">* means required</div>
            <input type="submit" value="Create User" />
        <!-- </pre> -->
    </form>
</div>