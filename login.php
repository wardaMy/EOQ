<?php
session_start();
// Jika pengguna sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Sertakan file koneksi ke database
include 'db_config.php';

// Periksa apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari form
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    try {
        // Query untuk memeriksa keberadaan pengguna dengan email yang sesuai
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika pengguna ditemukan
        if ($user) {
            // Verifikasi password
            if (password_verify($pass, $user['pass'])) {
                // Sesuaikan sesi pengguna atau lakukan tindakan lain
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect ke halaman dashboard atau halaman lainnya
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Password yang Anda masukkan salah.";
                exit;
            }
        } else {
            echo "Email yang Anda masukkan tidak terdaftar.";
            exit;
        }
    } catch(PDOException $e) {
        // Tangani error jika terjadi
        echo "Error: " . $e->getMessage();
    }

    // Tutup koneksi database
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Bajumi</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">BAJUMI</a>
        <figure class="navbar-nav ml-auto">
            <img src="assets/img/LOGO.jpg" width="42px"/>
        </figure>
    </nav>    
    <header>
        <h1 class="text-center text-shadow: 2px 2px 8px #FF0000 mt-4" style="padding-top: 30px;">Sistem Perencanaan Produksi dan Bahan Baku Batik</h1>
    </header>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <form action="login.php" method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input class="form-control py-4" id="inputEmailAddress" name="email" type="email" placeholder="Enter email address" required/>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <div class="input-group">
                                                <input class="form-control py-4" id="inputPassword" name="pass" type="password" placeholder="Enter password" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" name="remember"/>
                                                <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <!-- <a class="small" href="password.php">Forgot Password?</a> -->
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <?php include 'footer.html' ; ?>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#inputPassword');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
