<!DOCTYPE html>
<h1>Welcome to TicTacToe!</h1>

<?php
    // Create board variable equal to empty board
	$board = array(
		array('?', '?', '?'),
		array('?', '?', '?'),
		array('?', '?', '?'));

    // Creates singleline string representation of board variable
	$board_string = convertBoardToString($board);

    /*
    * Checks if the current board has a winner.
    *
    * Input: The board to be evaluated
    *
    * Output: 'X' if computer won, 'O' if player won, null if no one has won yet
    */
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
    
    /*
    * Converts a board (array of arrays) into a single-line string representation
    *
    * Input: The board to be converted into a string
    *
    * Output: The string representation of the board
    */
	function convertBoardToString($board) {
        $board_string = "";
        $board_string .= implode("", $board[0]);
        $board_string .= implode("", $board[1]);
        $board_string .= implode("", $board[2]);
		return $board_string;
    }

    /*
    * Converts a single-line string representation of a board into an array of arrays board
    *
    * Input: The string representatino of the board to be converted into an array
    *
    * Output: The array representation of the board
    */
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
    
    function playerCheated($currBoard){
        if (!isset($_SERVER['HTTP_REFERER'])) {
            return TRUE;
        }
        $requestURI = $_SERVER['REQUEST_URI'];
        // $cacheControl = $_SERVER['HTTP_CACHE_CONTROL'];
        // echo "cacheControl: '" . $cacheControl . "'<br>";
        // echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . " VS " . $_SERVER['HTTP_REFERER'] . "<br>";
        // // if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) {
        // //     return TRUE;
        // // }
        // // if (!isset($_SERVER['HTTP_REFERER'])) {
        // //     // if ($requestURI != '') {
        // //     //     return TRUE;
        // //     // }
        // //     $prevBoard = '';
        // // }
        // $pageFrom = $_SERVER['HTTP_REFERER'];
        // $prevBoard = substr(strstr(strstr($pageFrom, '&', true), '='), 1);
        // // if (isset($_SERVER['HTTP_REFERER']) && (($requestURI != '') && ($prevBoard == ''))) {
        // //     return TRUE;
        // // }
        // echo "'" . $prevBoard . "' -> '" . $currBoard . "'<br>";
        $numOfChanges = 0;
        if (empty($prevBoard)) {
            if ((substr_count($currBoard, "?") != 8) && ($requestURI != '')) {
                return TRUE;
            }
            return FALSE;
        }
        for ($i=0 ; $i<9 ; $i++) {
            if (($prevBoard[$i] === '?') && ($currBoard[$i] != '?')) {
                $numOfChanges++;
            }
        }
        echo "numOfChanges: " . $numOfChanges . "<br>";
        return FALSE;
    }

    if (isset($_GET['board']) && isset($_GET['row']) && isset($_GET['col'])) {
        // echo "</br>SERVER[HTTP_REFERER]: " . $_SERVER['HTTP_REFERER'] . "</br>";
        // echo "SERVER[HTTP_HOST]: " . $_SERVER['HTTP_HOST'] . "</br>";
        // if (!(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false)) {
        //     echo "<br><h2>WOW! We got a cheater!</h2>";
        //     return;
        // }
        
		$board_string = $_GET['board'];
		$board = convertStringToBoard($board_string);
        if (playerCheated($board_string)) {
            echo "<font size='30'>You naughty child. No cheating!</font>";
            return;
        }
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

    if (substr_count($board_string, '?') === 0) {
        echo "It's a draw!";
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

