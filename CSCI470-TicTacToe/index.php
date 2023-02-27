<!DOCTYPE html>
<!--

    Author: Will Augustine

    Description: A server-side only application to play a game of TicTacToe

-->

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
        $board_string .= implode("", $board[0]); // Converts top row into a string and adds it to the end of board_string
        $board_string .= implode("", $board[1]); // Converts middle row into a string and adds it to the end of board_string
        $board_string .= implode("", $board[2]); // Converts bottom row into a string and adds it to the end of board_string
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
        $array = str_split($string, 3); // Splits the board_string into three arrays, each 3 characters long
        $board = array(
			str_split($array[0], 1), // Splits the top row string into an array
			str_split($array[1], 1), // Splits the middle row string into an array
			str_split($array[2], 1) // Splits the bottom row string into an array
		);
		return $board;
    }

    /*
    * Returns a random row and column value in the board
    *
    * Input: The board to pick a random row/column from
    *
    * Output: A list of the row as the first element, and the column as the second
    */
    function getRandomSquare($board) {
        $row = array_rand($board, 1); # Gets a random row index
        $whole_row = $board[$row]; # Gets the whole row from the selected row
        $col = array_rand($whole_row, 1); # Gets a random column index from the selected row
        return [$row, $col];
    }

    /*
    * Gets the computer's guess. validates it, and updates the board with the guess
    *
    * Input: The board that the computer is making a move on
    *
    * Output: None
    */
    function getComputerInput($board) {
        $rowAndCol = getRandomSquare($board); // Gets a random row and column index from the board
        $row = $rowAndCol[0]; // Pulls the row index from the getRandomSquare output
        $col = $rowAndCol[1]; // Pulls the column index from the getRandomSquare output
        while ($board[$row][$col] != '?') { // While the computer has not selected a valid row and column index
            $rowAndCol = getRandomSquare($board); // Get new row and column index values
            $row = $rowAndCol[0]; // Update row index with new value
            $col = $rowAndCol[1]; // Update column index with new value
        }
        $board[$row][$col] = 'X'; // Set the computer guess location equal to 'X'
		$GLOBALS['board'] = $board; // Update the global variable for board
        $GLOBALS['board_string'] = convertBoardToString($board); // Update the global variable for board_string
    }

    /*
    * Prints the inputted board, but without links to the ?
    *
    * Input: The board to print
    *
    * Output: None
    */
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
    
    /*
    * Creates a button that asks the player if they want to play again. If clicked, it resets the index.php
    *
    * Input: None
    *
    * Output: None
    */
    function askToPlayAgain(){
        echo "<br><br><a href='./'><button type='button'>Play Again</button></a>";
    }

    /*
    * Checks to see if the player has cheated
    *
    * Input: The string representation of the current board
    *
    * Output: TRUE if the player has cheated
    *   FALSE if the player has not cheated
    */
    function playerCheated($currBoard){
        // Checks to see if the URL was typed in (if the user did not come from a link)
        if (!isset($_SERVER['HTTP_REFERER'])) {
            return TRUE;
        }
        $requestURI = $_SERVER['REQUEST_URI']; // RequestURI is the current URL
        $pageFrom = $_SERVER['HTTP_REFERER']; // pageFrom is the URL the user came from
        $prevBoard = substr(strstr(strstr($pageFrom, '&', true), '='), 1); // Extracts the string representation of the baord form the URL
        $numOfChanges = 0; // Sets counter variable to 0
        if (empty($prevBoard)) { // If the last board is empty
            // If the game has not just initialized
            if ((substr_count($currBoard, "?") != 8) && ($requestURI == '')) {
                return TRUE; // Player has cheated
            }
            return FALSE; // Player has not cheated
        }
        // Loop to count the number of changes from the last board to the current
        for ($i=0 ; $i<9 ; $i++) {
            if (($prevBoard[$i] === '?') && ($currBoard[$i] != '?')) {
                $numOfChanges++;
            }
        }
        // If more than two changes have occured from the previous board to the current one
        if ($numOfChanges != 2) {
            return TRUE; // Player has cheated
        }
        return FALSE; // If completed all checks, player has not cheated
    }

    // If the URL contains 'board=', 'row=', and 'col='
    if (isset($_GET['board']) && isset($_GET['row']) && isset($_GET['col'])) {
		$board_string = $_GET['board']; // Get the board string form the board variable
		$board = convertStringToBoard($board_string); // Convert board string into array
        if (playerCheated($board_string)) { // Determine if player has cheated
            echo "<font size='30'>No cheating!</font>";
            askToPlayAgain();
            return;
        }
        $GLOBALS['board_string'] = $board_string; // Update board_string global variable
		$GLOBALS['board'] = $board; // Update board global variable
		$row = (int) $_GET['row']; // Get row index user selected from the row variable
		$col = (int) $_GET['col']; // Get column index user selected from the col variable
        if ($board[$row][$col] == '?') { // If the user selected a valid box
            $board[$row][$col] = 'O'; // Update the board
        }
		else { // If the user selected an invalid square, let the user know
			echo "ERROR: Invalid square clicked!</br>";
		}
        
        // Checks to see if there is a winner before the computer makes their move
        $winner = checkWinner($board);
        if (!isset($winner)){ // If there is no winner yet
            getComputerInput($board); // Computer makes their move
        }
        // Checks to see if there is a winner after the computer makes their move
        $winner = checkWinner($board);
        if ($winner) { // If there is a winner
            if ($winner == 'X') { // If the winning player is 'X'
                echo 'Sorry, the computer beat you! </br></br>'; // Display the computer beat the user
            }
            else { // Otherwise
                echo 'Yay, you won! </br></br>'; // Display you beat the comptuer
            }
            printFinalBoard($board); // Print the final board (without links)
            askToPlayAgain(); // Ask if the user wants to play again
            return;
        }
    }

    // If the board is completely full and there is no winner
    if (substr_count($board_string, '?') === 0) {
        echo "It's a draw!</br></br>"; // Say that it is a draw
        printFinalBoard($board); // Print the final board
        askToPlayAgain(); // Ask if the user wants to play again
        return;
    }
    if ($board_string == '?????????') { // If the board is empty (the game has just started)
        getComputerInput($board); // Computer makes their move since they go first
    }
?>

<br>
<br>
<!-- Prints the board with links on each ? cell -->
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

