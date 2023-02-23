<!DOCTYPE html>
<h1>Welcome to TicTacToe!</h1>
<!-- <form method="post">
    <input type="submit" name="startGameButton" value="Start Game"/>
</form> -->
<br>
<br>

<?php
	ini_set('max_execution_time', '2');
    $gameStarted = false;
	$board = array(
		array('?', '?', '?'),
		array('?', '?', '?'),
		array('?', '?', '?'));

	$board_string = convertBoardToString($board);

	function convertBoardToString($board) {
		echo "Type of array: ".gettype($board)."</br>";
        $board_string = "";
        $board_string .= implode("", $board[0]);
        $board_string .= implode("", $board[1]);
        $board_string .= implode("", $board[2]);
		echo "board_string: " . $board_string . "</br>";
		return $board_string;
    }

    function convertStringToBoard($string) {
        $array = str_split($string, 3);
        $board = array(
			$array[0],
			$array[1],
			$array[2]
		);
		return $board;
    }

    // Initialize the game board
    // if (!isset($board)) {
    //     $board = array(
    //         array('?', '?', '?'),
    //         array('?', '?', '?'),
    //         array('?', '?', '?'));
    // }
	// // Initialize the game board
    // if (!isset($board_string)) {
    //     $board_string = convertBoardToString($board);
    // }

    function getRandomSquare($board) {
        $row = array_rand($board, 1);
        $whole_row = $board[$row];
        $col = array_rand($whole_row, 1);
        return [$row, $col];
    }

    function getComputerInput($board) {
        $rowAndCol = getRandomSquare($board);
        $row = $rowAndCol[0];
        $col = $rowAndCol[1];
        while ($board[$row][$col] != '?') {
            $rowAndCol = getRandomSquare($board);
            $row = $rowAndCol[0];
            $col = $rowAndCol[1];
        }
        $board[$row][$col] = 'X';
		$GLOBALS['board'] = $board;
        $GLOBALS['board_string'] = convertBoardToString($board);
        // $board_string = "";
        // $board_string .= convertBoardToString($board[0]);
        // $board_string .= convertBoardToString($board[1]);
        // $board_string .= convertBoardToString($board[2]);
        // echo "board string: " . $board_string . "</br>";
        // convertStringToBoard($board_string);
    }

    if (isset($_GET['board']) && isset($_GET['row']) && isset($_GET['col'])) {
		// echo "</br>GET['board']: '" . $_GET['board'] . "'</br>";
		// echo "GET['row']: '" . $_GET['row'] . "'</br>";
		// echo "<pre>\tGET['row'] type: " . gettype($_GET['row']) . "\n";
		// echo "GET['col']: '" . $_GET['col'] . "'</br>";
		// echo "<pre>\tGET['row'] type: " . gettype($_GET['row']) . "\n\n";
		$board_string = $_GET['board'];
		$board = convertStringToBoard($board_string);
        $GLOBALS['board_string'] = $board_string;
		// echo "GLOBALS['board_string']: '" . $board_string . "'</br>";
		
		$GLOBALS['board'] = $board;
		// echo "<pre>GLOBALS['board']:\n\t";
		// print_r($board);
		// echo "\n</pre>";

		$row = (int) $_GET['row'];
		// echo "row: '" . $row . "' of type " . gettype($row) . "</br>";
		$col = (int) $_GET['col'];
		// echo "col: '" . $col . "' of type " . gettype($col) . "</br>";
		// $selected_col = $board[1];
		// echo "</br>selected_col has type '" . gettype($selected_col) . "' and equals:</br>&emsp;";
		// print_r($selected_col);

		// $selected_val = $selected_col[0];
		// echo "</br></br>selected_val has type '" . gettype($selected_val) . "' and equals:</br>&emsp;";
		// echo $selected_val . "</br></br>";
		
        // $board = $_SERVER['board'];
        // $board_string = "";
        // $board_string .= convertBoardToString($board[0]);
        // $board_string .= convertBoardToString($board[1]);
        // $board_string .= convertBoardToString($board[2]);
        // echo "board string: " . $board_string . "</br>";
        // convertStringToBoard($board_string);
        // Check if the selected cell is empty
		echo "board[row][col]: '" . $board[$row][$col] . "'</br>";
        if ($board[$row][$col] == '?') {
			echo "Selected a valid square! </br>";
            // Update the board
            $board[$row][$col] = 'O';
            // $board_string = convertBoardToString($board);
            getComputerInput($board);
        }
		else {
			echo "ERROR: Invalid square clicked!</br>";
		}
    }

    if ($gameStarted == false) {
        getComputerInput($board);
        $gameStarted = true;
    }
?>

<table>
    <?php foreach ($board as $row => $cells): ?>
        <tr>
            <?php foreach ($cells as $col => $cell): ?>
                <td>
                    <?php if ($cell == '?'): ?>
                        <?php echo '<a href="?board='.$board_string.'&row='.$row.'&col='.$col.'">?</a>'?>
                    <?php else: ?>
                        <?php echo $cell ?>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

