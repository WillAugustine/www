<?php
require_once 'functions.php';
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $querySearch = queryDB("SELECT * FROM searches WHERE id = '$id'");
    $searchVals = $querySearch->fetch_assoc();
    if($searchVals) {

        $yval2 = $searchVals['y'];
        $xval2 = $searchVals['x'];
        $block = $searchVals['block'];
        $link = $searchVals['link'];
        $queryBlocks = queryDB("SELECT * FROM block_locations Where Block = '$block';");
        $values = $queryBlocks->fetch_assoc();
        $xval = $values['x'];
        $yval = $values['y'];
        echo <<<_END
        <html>
            
            <style>
                img {
                    max-width: 100%;
                    height: auto;
                    margin-top: 20px;
                    position: relative;
                }
                #canvas {
            
                    width: 100%;
                    height: 100%;
                    
                }
                
            </style>
            <head>
                <title>Saint Patrick Cemetary Gravesite Locator</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <h1>Saint Patrick Cemetery Gravesite Locator</h1>
                <form class="search-form" action="main.php" method="POST">
                    <div class="form-group">
                        <label>Block #:</label>
                        <input type="text" name="block" required>
                    </div>
                    <div class="form-group">
                        <label>Lot:</label>
                        <input type="text" name="lot">
                    </div>
                    <div class="form-group">
                        <label>Plot:</label>
                        <input type="text" name="plot">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search">
                    </div>
                </form>
                <div class="link-container">
                    <a href="index.php">Main page</a>
                    <br>
                    <a href="feedback.php?id='$id'">Feedback</a>
                    
                </div>
                <div >
                    <canvas id="myCanvas" width="2000" height="630"></canvas>
                    <script>
                        const canvas = document.getElementById('myCanvas');
                        const context = canvas.getContext('2d');
                        context.fillStyle = "#FF0000";
                        const img = new Image();
                        img.src = './MapPic.jpg';
                        img.onload = () => {
                            context.drawImage(img, 0, 0);
                            context.fillRect('$xval', '$yval', 10, 10);
                        };
                    </script>
                    
                </div>
                <div>
                
                    <canvas id="myCanvas2" width="800" height="630"></canvas>
                    <script>
                        const canvas2 = document.getElementById('myCanvas2');
                        const context2 = canvas2.getContext('2d');
                        
                        const img2 = new Image();
                        img2.src = '$link';
                        img2.onload = () => {
                            // Draw the image on the canvas
                            var scale = Math.min(canvas2.width / img2.width, canvas2.height / img2.height);
                            // Calculate the center of the canvas
                            var x = (canvas2.width - img2.width * scale) / 2;
                            var y = (canvas2.height - img2.height * scale) / 2;
                            context2.drawImage(img2, x, y, img2.width * scale, img2.height * scale);
                            context2.beginPath();
                            context2.arc($xval2, $yval2, 5, 0, 2 * Math.PI, false);
                            context2.fillStyle = 'red';
                            context2.fill();
                            context2.closePath();
                        };
                    </script>
                    
                </div>
            </body>
        </html>
        _END;
    } else {
        header("location: main.php");
    }
} else {



    if(isset($_POST['block'])) {
        $block = cleanString($_POST['block']);
        $block = preg_replace("/[^0-9]/", "", $block );
        $queryBlocks = queryDB("SELECT * FROM block_locations Where Block = '$block';");
        $count = $queryBlocks->num_rows; 
        if ($count < 1) {
            
            echo <<<_END
            <html>
                <head>
                    <title>Saint Patrick Cemetary Gravesite Locator</title>
                    <link rel="stylesheet" href="style.css">
                </head>
                <body>
                    <h1>Saint Patrick Cemetery Gravesite Locator</h1>
                    <form class="search-form" action="main.php" method="POST">
                        <div class="form-group">
                            <label>Block # (not found):</label>
                            <input type="text" name="block" required>
                        </div>
                        <div class="form-group">
                            <label>Lot:</label>
                            <input type="text" name="lot">
                        </div>
                        <div class="form-group">
                            <label>Plot:</label>
                            <input type="text" name="plot">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Search">
                        </div>
                    </form>
                    <div class="link-container">
                        <a href="index.php">Main page</a>
                    </div>
                </body>
            </html>
            _END;
        }
        else {
            $values = $queryBlocks->fetch_assoc();
            $xval = $values['x'];
            $yval = $values['y'];
            echo <<<_END
            <html>
                <head>
                    <title>Saint Patrick Cemetary Gravesite Locator</title>
                    <link rel="stylesheet" href="style.css">
                </head>
                <body>
                    <h1>Saint Patrick Cemetery Gravesite Locator</h1>
                    <form class="search-form" action="main.php" method="POST">
                        <div class="form-group">
                            <label>Block #:</label>
                            <input type="text" name="block" required>
                        </div>
                        <div class="form-group">
                            <label>Lot:</label>
                            <input type="text" name="lot">
                        </div>
                        <div class="form-group">
                            <label>Plot:</label>
                            <input type="text" name="plot">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Search">
                        </div>
                    </form>
                    <div class="link-container">
                        <a href="index.php">Main page</a>
                    </div>
                    <canvas id="myCanvas" width="2000" height="2000"></canvas>
                    <script>
                        const canvas = document.getElementById('myCanvas');
                        const context = canvas.getContext('2d');
                        context.fillStyle = "#FF0000";
                        const img = new Image();
                        img.src = './MapPic.jpg';
                        img.onload = () => {
                            context.drawImage(img, 0, 0);
                            context.fillRect('$xval', '$yval', 10, 10);
                        };
                    </script>
                </body>
            </html>
            _END;
        }
    } else {
        echo <<<_END
        <html>
            <head>
                <title>Saint Patrick Cemetary Gravesite Locator</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <h1>Saint Patrick Cemetery Gravesite Locator</h1>
                <form class="search-form" action="main.php" method="POST">
                    <div class="form-group">
                        <label>Block #:</label>
                        <input type="text" name="block" required>
                    </div>
                    <div class="form-group">
                        <label>Lot:</label>
                        <input type="text" name="lot">
                    </div>
                    <div class="form-group">
                        <label>Plot:</label>
                        <input type="text" name="plot">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Search">
                    </div>
                </form>
                <div class="link-container">
                    <a href="index.php">Main page</a>
                </div>
            </body>
        </html>
        _END;
    }
}
?>




