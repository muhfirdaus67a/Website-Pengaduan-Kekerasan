<?php 
    include 'config.php'; 
    error_reporting(0);
 
session_start();
 
if (isset($_SESSION['username'])) {
    header("Location: login.php");
}
 
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);
 
    if ($password == $cpassword) {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if (!$result->num_rows > 0) {
            $sql = "INSERT INTO users (username, email, password)
                    VALUES ('$username', '$email', '$password')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script>alert('Selamat, registrasi berhasil!')</script>";
                $username = "";
                $email = "";
                $_POST['password'] = "";
                $_POST['cpassword'] = "";
            } else {
                echo "<script>alert('Woops! Terjadi kesalahan.')</script>";
            }
        } else {
            echo "<script>alert('Woops! Email Sudah Terdaftar.')</script>";
        }
         
    } else {
        echo "<script>alert('Password Tidak Sesuai')</script>";
    }
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
    <link rel="stylesheet" type="text/css" href="regsister.css">
 
    <title>REGISTER</title>
</head>
<body>
    <div class="box">
    <span class="borderline"></span>
        <form action="" method="POST">
        <h2>Register</h2>
            <div class="inputBox">
                <input type="text" placeholder="" name="username" value="<?php echo $username; ?>" required>
                <span>Username</span>
                <i></i>
            </div>
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
            <div class="inputBox">
                <input type="password" placeholder="Confirm Password" name="cpassword" value="<?php echo $_POST['cpassword']; ?>" required>
                <span>Confirm Password</span>
                    <i></i>
            </div>
            
                <button name="submit" class="btn">Register</button>
            <p class="text">Anda sudah punya akun? <a href="login.php" class="login">Login </a></p>
        </form>
    </div>
</body>
</html>