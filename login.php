<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
} else {
    $baseurl = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . "?") . "/";
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <!--Head-->

    <head>
        <meta charset="utf-8" />
        <title>Login - Sistem Informasi Rapor</title>

        <meta name="description" content="login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo $baseurl; ?>dashboard/assets/img/favicon.png" type="image/x-icon">

        <!--Basic Styles-->
        <link href="<?php echo $baseurl; ?>dashboard/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo $baseurl; ?>dashboard/assets/css/font-awesome.min.css" rel="stylesheet" />
    </head>
    <!--Head Ends-->
    <!--Body-->

    <body style="padding-top: 150px;">
        <div class="container">
            <div class="card card-container">
                <img id="profile-img" class="profile-img-card" src="assets/images/logo1.jpg" />
                <p id="profile-name" class="profile-name-card">Sistem Informasi Rapor</p>
                <form class="form-signin" method="post" action="core/login_proses.php">
                    <span id="reauth-email" class="reauth-email"></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="login">Masuk</button>
                </form>
                <?php if (isset($_GET['pesan'])): ?>
                    <div class="alert alert-danger">
                        <?php
                        if ($_GET['pesan'] == "gagal") {
                            echo "Username dan Password tidak sesuai!";
                        } else if ($_GET['pesan'] == "logout") {
                            echo "Anda telah berhasil keluar sistem";
                        } else if ($_GET['pesan'] == "belum_login") {
                            echo "Anda harus login terlebih dahulu";
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!--Basic Scripts-->
        <script src="<?php echo $baseurl; ?>dashboard/assets/js/jquery-2.0.3.min.js"></script>
        <script src="<?php echo $baseurl; ?>dashboard/assets/js/bootstrap.min.js"></script>
    </body>
    <!--Body Ends-->

    </html>

    <?php
}
?>