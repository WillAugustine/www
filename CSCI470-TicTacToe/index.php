<!DOCTYPE html>
<h1>Welcome to TicTacToe!</h1>
<!-- <form method="post">
    <input type="submit" name="startGameButton" value="Start Game"/>
</form> -->
<br>
<br>

<?php

    $gameStarted = false;
    
    // Initialize the game board
    if (!isset($_SERVER['board'])) {
        $_SERVER['board'] = array(
            array('', '', ''),
            array('', '', ''),
            array('', '', ''));
    }

    function getRandomSquare() {
        $board = $_SERVER['board'];
        $row = array_rand($board, 1);
        $whole_row = $board[$row];
        $col = array_rand($whole_row, 1);
        return [$row, $col];
    }

    function getComputerInput() {
        $board = $_SERVER['board'];
        [$row, $col] = getRandomSquare();
        while ($board[$row][$col] != '') {
            [$row, $col] = getRandomSquare();
        }
        $board[$row][$col] = 'X';
        $_SERVER['board'] = $board;
    }

    if (isset($_GET['row']) && isset($_GET['col'])) {
        $row = $_GET['row'];
        $col = $_GET['col'];
        echo "row: " . $row . "</br>";
        echo "col: " . $col . "</br>";
        $board = $_SERVER['board'];
        // Check if the selected cell is empty
        if ($board[$row][$col] == '') {
            // Update the board
            $board[$row][$col] = 'O';
            $_SERVER['board'] = $board;
            getComputerInput();
        }
    }

    if ($gameStarted == false) {
        getComputerInput();
        $gameStarted = true;
    }
?>

<table>
    <?php foreach ($_SERVER['board'] as $row => $cells): ?>
        <tr>
            <?php foreach ($cells as $col => $cell): ?>
                <td>
                    <?php if ($cell == ''): ?>
                        <?php echo '<a href="?row='.$row.'&col='.$col.'">?</a>'?>
                    <?php else: ?>
                        <?php echo $cell ?>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

