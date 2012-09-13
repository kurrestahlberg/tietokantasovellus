<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="basestyle.css" />
    </head>
    <body>
        <h1>
            Register to Nutrition management
        </h1>
        
        <div class="form_div">
            <form action="mainpage.php?reg=1" method="post" class="registration_form">
                Email address: <input type="text" name="email" /><br />
                Age: <input type="text" name="age" /><br />
                Weight: <input type="text" name="weight" /><br />
                Height: <input type="text" name="height" /><br />
                Password: <input type="password" name="password" /><br />
                Password again: <input type="password" name="password2" /><br />
                <input type="submit" value="Register" />
            </form>
        </div>
    </body>
</html>
