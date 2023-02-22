<?php
session_start();

// Initialize the game board
if (!isset($_SERVER['board'])) {
    $_SERVER['board'] = [
        ['', '', ''],
        ['', '', ''],
        ['', '', ''],
    ];
}

// Handle user input
if (isset($_POST['row']) && isset($_POST['col'])) {
    $row = $_POST['row'];
    $col = $_POST['col'];
    $board = $_SERVER['board'];

    // Check if the selected cell is empty
    if ($board[$row][$col] == '') {
        // Alternate between X and O
        $player = ($_SERVER['player'] == 'X') ? 'O' : 'X';
        $_SERVER['player'] = $player;

        // Update the board
        $board[$row][$col] = $player;
        $_SERVER['board'] = $board;
    }
}

// Check for a winner
function checkWinner($board) {
    // Check rows
    for ($row = 0; $row < 3; $row++) {
        if ($board[$row][0] != '' && $board[$row][0] == $board[$row][1] && $board[$row][1] == $board[$row][2]) {
            return $board[$row][0];
        }
    }

    // Check columns
    for ($col = 0; $col < 3; $col++) {
        if ($board[0][$col] != '' && $board[0][$col] == $board[1][$col] && $board[1][$col] == $board[2][$col]) {
            return $board[0][$col];
        }
    }

    // Check diagonals
    if ($board[0][0] != '' && $board[0][0] == $board[1][1] && $board[1][1] == $board[2][2]) {
        return $board[0][0];
    }
    if ($board[0][2] != '' && $board[0][2] == $board[1][1] && $board[1][1] == $board[2][0]) {
        return $board[0][2];
    }

    // No winner yet
    return null;
}

// Check for a winner
$winner = checkWinner($_SERVER['board']);

// Render the game board
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tic Tac Toe</title>
</head>
<body>
    <h1>Tic Tac Toe</h1>
    <?php if ($winner): ?>
        <h2>Winner: <?php echo $winner ?></h2>
    <?php else: ?>
        <h2>Player: <?php echo $_SERVER['player'] ?></h2>
    <?php endif; ?>
    <table>
        <?php foreach ($_SERVER['board'] as $row => $cells): ?>
            <tr>
                <?php foreach ($cells as $col => $cell): ?>
                    <td>
                        <?php if ($cell == ''): ?>
                            <form method="post">
                                <input type="hidden" name="row" value="<?php echo $row ?>">
                                <input type="hidden" name="col" value="<?php echo $col ?>">
                                <input type="submit" value="">
                            </form>
                        <?php else: ?>
                            <?php echo $cell ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
