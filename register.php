<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Create Account - BAJUMI</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<?php
// Pastikan formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sertakan file koneksi database
    include 'db_config.php';
    
    // Ambil nilai dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validasi bahwa password dan konfirmasi password sesuai
    if ($pass != $confirm_password) {
        echo "Password dan konfirmasi password tidak sesuai.";
        exit;
    }

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // SQL untuk menambahkan pengguna baru ke dalam database
        $sql = "INSERT INTO user (username, email, pass, role) VALUES (:username, :email, :pass, :role)";
        
        // Persiapkan statement PDO
        $stmt = $conn->prepare($sql);
        
        // Bind parameter ke statement
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hashed_password);
        $stmt->bindParam(':role', $role);
        
        // Jalankan statement
        $stmt->execute();

        // Jika berhasil di-insert, arahkan ke halaman login
        header("Location: login.php");
        exit;
    } catch(PDOException $e) {
        // Tangani error jika terjadi
        echo "Error: " . $e->getMessage();
    }

    // Tutup koneksi database
    $conn = null;
}
?>
<body class="bg-primary">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">BAJUMI</a>
        <figure class="navbar-nav ml-auto">
            <img src="assets/img/LOGO.jpg" width="42px"/>
        </figure>
    </nav>
    <header>
        <h1 class="text-center mt-4" style="padding-top: 30px;">Sistem Perencanaan Produksi dan Bahan Baku Batik</h1>
    </header>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                                <div class="card-body">
                                    <form action="register.php" method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputUserName">Username</label>
                                            <input class="form-control py-4" id="inputUserName" name="username" type="text" placeholder="Enter username" required/>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input class="form-control py-4" id="inputEmailAddress" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" required/>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                    <input class="form-control py-4" id="inputConfirmPassword" name="confirm_password" type="password" placeholder="Confirm password" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="InputRole" class="small mb-1">Role</label>
                                            <select name="role" id="InputRole" class="form-control">
                                                <option value="Owner">Owner</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-4 mb-0"><button type="submit" class="btn btn-primary btn-block">Create Account</button></div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <?php include 'footer.html'; ?>
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
