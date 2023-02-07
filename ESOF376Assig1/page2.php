<html>
    <head>
        <meta charset="UTF-8">
        <title>CECS 544 Lab 1 Page 2</title>
    </head>
    <body>
        <h2>
            <?php
                $first = $_POST['first'];
                $last = $_POST['last'];
                printf("You entered %s %s as your name.<p>",$first,$last);
            ?>
            You have successfully completed Lab1!
            <p>
            <input type="button" value="Return" id=button1 name=button1 onclick="go_home()">    
        </h2>
        <script language=Javascript>
            function go_home(){
                window.location.replace("index.php");
            }
        </script>
            
    </body>
</html>
