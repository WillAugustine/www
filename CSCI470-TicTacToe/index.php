<!DOCTYPE html>
<h1>Welcome to TicTacToe!</h1>

<?php
    // echo "SERVER[HTTP_REFERER]: " . $_SERVER["HTTP_REFERER"] . "</br>";
	$board = array(
		array('?', '?', '?'),
		array('?', '?', '?'),
		array('?', '?', '?'));

	$board_string = convertBoardToString($board);

    function checkWinner($board) {
        // Check for a horizontal winner
        for ($row = 0; $row < 3; $row++) {
            if ($board[$row][0] != '?' && $board[$row][0] == $board[$row][1] && $board[$row][1] == $board[$row][2]) {
                return $board[$row][0]; // Returns the player that won ('X' or 'O')
            }
        }
    
        // Check for a vertical winner
        for ($col = 0; $col < 3; $col++) {
            if ($board[0][$col] != '?' && $board[0][$col] == $board[1][$col] && $board[1][$col] == $board[2][$col]) {
                return $board[0][$col]; // Returns the player that won ('X' or 'O')
            }
        }
    
        // Checks for a top left to bottom right diagonal winner
        if ($board[0][0] != '?' && $board[0][0] == $board[1][1] && $board[1][1] == $board[2][2]) {
            return $board[0][0]; // Returns the player that won ('X' or 'O')
        }

        // Checks for a bottom left to top right diagonal winner
        if ($board[0][2] != '?' && $board[0][2] == $board[1][1] && $board[1][1] == $board[2][0]) {
            return $board[0][2]; // Returns the player that won ('X' or 'O')
        }

        // Otherwise, no winner yet
        return null;
    }
    

	function convertBoardToString($board) {
        $board_string = "";
        $board_string .= implode("", $board[0]);
        $board_string .= implode("", $board[1]);
        $board_string .= implode("", $board[2]);
		return $board_string;
    }

    function convertStringToBoard($string) {
        $array = str_split($string, 3);
        $board = array(
			str_split($array[0], 1),
			str_split($array[1], 1),
			str_split($array[2], 1)
		);
		return $board;
    }

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
    }

    function printFinalBoard($board) {
        ?>
        <table>
            <?php foreach ($board as $row => $cells): ?>
                <tr>
                    <?php foreach ($cells as $col => $cell): ?>
                        <td>
                            <?php echo $cell ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }
    

    if (isset($_GET['board']) && isset($_GET['row']) && isset($_GET['col'])) {
        echo "</br>SERVER[HTTP_REFERER]: " . $_SERVER['HTTP_REFERER'] . "</br>";
        echo "SERVER[HTTP_HOST]: " . $_SERVER['HTTP_HOST'] . "</br>";
        // if (!(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false)) {
        //     echo "<br><h2>WOW! We got a cheater!</h2>";
        //     return;
        // }
        
		$board_string = $_GET['board'];
		$board = convertStringToBoard($board_string);
        $GLOBALS['board_string'] = $board_string;
		$GLOBALS['board'] = $board;

		$row = (int) $_GET['row'];
		$col = (int) $_GET['col'];
        if ($board[$row][$col] == '?') {
            $board[$row][$col] = 'O';
        }
		else {
			echo "ERROR: Invalid square clicked!</br>";
		}
        
        // CHECK FOR WINNER BEFORE COMPUTER MAKES THEIR MOVE
        $winner = checkWinner($board);
        if (!isset($winner)){
            getComputerInput($board);
        }
        // CHECK FOR WINNER AFTER COMPUTER MAKES THEIR MOVE
        $winner = checkWinner($board);
        if ($winner) {
            if ($winner == 'X') {
                echo 'Sorry, the computer beat you! </br></br>';
            }
            else {
                echo 'Yay, you won! </br></br>';
            }
            printFinalBoard($board);
            return;
        }
    }

    if ($board_string == '?????????') {
        getComputerInput($board);
    }
?>

<br>
<br>
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

