<link href="styles.css" rel="stylesheet" />
<?php

    $block = isset($_GET['block']) ? $_GET['block'] : null;
    $plot = isset($_GET['plot']) ? $_GET['plot'] : null;
    $lot = isset($_GET['lot']) ? $_GET['lot'] : null;
    $name = isset($_GET['name']) ? $_GET['name'] : null;

    if ($block !== null) {
        $imagePath = 'blocks/Block ' . $block . '.JPG';
        if (file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="Block Image" class="block-image">';
        } else {
            echo 'Image not found.';
        }
    } else {
        echo 'Block data not provided.';
    }
?>