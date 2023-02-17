<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CECS 544 Lab 1</title>
    </head>
    <body>
        <h1>Lab 1</h1>
        <form action="page2.php" method="post" onsubmit="return validate(this)">
            <table>
                <tr><td>First name:</td><td><input type="Text" name="first"</td></tr>
                <tr><td>Last name:</td><td><input type="Text" name="last"</td></tr>     
            </table>
            <input type="submit" name="submit" value="Next">
        </form>
        <script language=Javascript>
            function validate(theform) {
                if(theform.first.value === ""){
                    alert ("First name field must contain characters");
                    return false;
                }
                if(theform.last.value === ""){
                    alert ("Last name field must contain characters");
                    return false;
                }
                return true;
            }
</script>
    </body>
</html>
