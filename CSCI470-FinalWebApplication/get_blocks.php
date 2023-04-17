<?php
    // get_blocks.php
    $search = $_GET['search'] ?? "";
    $blocks = [];
    if ($search) {
        $dir = "blocks/";
        $files = scandir($dir);
        foreach ($files as $file) {
            if (preg_match('/^Block (.+)\.JPG$/', $file, $matches)) {
                if (stripos($matches[1], $search) !== false) {
                    $blocks[] = $matches[1];
                }
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($blocks);
?>