<?php

/*
 * Name:    Brandon Mitchell
 * Description: The landing page of the site.  Includes a bit of info about the
 *              application and a log on form for the archives.
 */

require_once("functions.php");
createHeader(false);

?>
        <div class="centerBox">
            <h2>St. Patrick's Cemetery Grave Locator</h2>
        </div>
        <div class="infoBox">
            St. Patrick's Cemetery is an old, Irish Catholic cemetery located in Butte, MT.
            The cemetery is full of history, but has fallen into disrepair the past few decades.
            The Ancient Order of Hibernians, an Irish Catholic fraternal group, has been working to 
            revitalize the cemetery the past few years, and this website is a part of that 
            plan.  Searching for individuas buried in St. Patrick's Cemetery can be
            difficult due to the age and layout of the cemetery.  This web application 
            is to aid people in locating individuals in the cemetery and hopefully make
            visits to the cemetery a more common occurance.  Contact the Butte-Silver Bow 
            Public Archives at <a href="mailto:example@example.com">example@example.com</a>
            for help or to request a search.<br><br>Archives employees can sign in below.
        </div><br>
        <form action="login.php" method="post" autocomplete="off">
            <p class="errorText">
                <?php
                    // Display an error message to the user informing them of 
                    // problem with their input
                    if (isset($_REQUEST['loginFailed']))
                    {
                        echo "Invalid username or password.";
                    }
                    elseif (isset($_REQUEST['notLoggedIn']))
                    {
                        echo "Authentication required.  Please log in.";
                    }    
                    elseif (isset($_REQUEST['missingData']))
                    {
                        echo "Missing username or password.";
                    }    
                    elseif (isset($_REQUEST['missingSearch']))
                    {
                        echo "A search ID is required to access that.";
                    }   
                    elseif (isset($_REQUEST['invalidSearch']))
                    {
                        echo "Search ID is invalid.";
                    }
                ?>
            </p>
            <h3>Archives Sign In</h3>
            Username: <input type="text" name="username" required /><br /><br />
            Password: <input type="password" name="password" required /><br /><br />
            <div class="centerBox">
                <input class="submitButton" type="submit" value="Login" />
            </div>
        </form>
    
    </div>
</body>
</html>