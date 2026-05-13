<?php 
        include 'config.php';
        error_reporting(0);
        session_start();
            if (isset($_SESSION['username'])) {
                header("Location: dashboard_admin.php");
            }
 
            if (isset($_POST['submit'])) {
                $email = $_POST['email'];
                $password = md5($_POST['password']);
                    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
                    $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['username'] = $row['username'];
                header("Location: dashboard_admin.php");
    } else {
        echo "<script>alert('Email atau password Anda salah. Silahkan coba lagi!')</script>";
    }
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login.css">
    <title>LOGIN</title>
</head>
<body>
    <div class="alert alert-warning" role="alert">
        <?php echo $_SESSION['error']?>
    </div>
 
    <div class="box">
        <span class="borderline"></span>
        <form action="" method="POST">
        <img src="image\logobiru.png">
            <div class="inputBox">      
                <input type="email" placeholder="" name="email" value="<?php echo $email; ?>" required>
                <span>Email</span>
                <i></i>            
            </div>
            <div class="inputBox">
                <input type="password" placeholder="" name="password" value="<?php echo $_POST['password']; ?>" required>
                <span>Password</span>
                <i></i> 
            </div>

            <button name="submit" class="btn">Login</button>

            <div class="links">
                    <a href="register.php">Forgot Password</a>
            </div>

            
    </form>    
</div>   
</body>
</html>