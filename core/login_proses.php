<?php
require_once('../config/db.php');

// Login Proses
if (isset($_POST['signin'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $hasil = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if (mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_array($hasil);
        $stored_password = $data['password'];
        
        $login_success = false;
        
        if (password_get_info($stored_password)['algo'] !== 0) {
            if (password_verify($pass, $stored_password)) {
                $login_success = true;
            }
        } else {
            if ($pass === $stored_password) {
                $login_success = true;
                
                $new_hash = password_hash($pass, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password='$new_hash' WHERE username='$user'");
            }
        }
        
        if ($login_success) {
            session_start();
            $_SESSION['id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['foto'] = $data['foto'];
            $_SESSION['access'] = $data['access'];
            $_SESSION['nomor_induk'] = $data['nomor_induk'];

            header('Location: ../dashboard/index.php');
            exit();
        } else {
            echo "<script>alert('Username atau Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location='login.php';</script>";
    }
}
?>