<?php
session_start();

// Jika pengguna belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $id_user = $_SESSION['id_user'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $created_at = $_POST['created_at'];

    // Validasi password dan konfirmasi password
    if ($pass !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok!";
        exit;
    }

    // Hash password sebelum menyimpan
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Query untuk mengupdate data berdasarkan id
    $sql = "UPDATE user SET 
            email = :email, 
            username = :username, 
            pass = :pass,
            role = :role,
            created_at = :created_at
            WHERE id_user = :id_user";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':pass', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

    // Eksekusi query dan cek apakah update berhasil
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal mengupdate data!";
    }
}

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Query untuk mengambil data berdasarkan id
    $sql = "SELECT * FROM user WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    // Mengambil hasil query
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    // Jika tidak ada parameter id di URL, redirect ke halaman dashboard
    header("Location: dashboard.php");
    exit;
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
    <title>Edit Akun</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; 
    include 'sidenav.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4" style="padding-top: 30px;">Edit Akun</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Akun</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        Edit data yang anda inginkan
                    </div>
                </div>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card">
                                <div class="card-header">
                                    Edit Akun
                                </div>
                                <div class="card-body">
                                    <form action="setting.php" method="POST">
                                        <input type="hidden" name="id_user" id="id_user" value="<?=$row['id_user']?>">
                                        <input type="hidden" name="created_at" id="created_at" value="<?=$row['created_at']?>">
                                        <input type="hidden" name="role" id="role" value="<?=$row['role']?>">
                                        <div class="form-group">
                                            <label class="small mb-1" for="username">Username</label>
                                            <input class="form-control py-4" id="username" name="username" type="text" placeholder="Enter username" value="<?=$row['username']?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="email">Email</label>
                                            <input class="form-control py-4" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?=$row['email']?>" required />
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="pass">Password</label>
                                                    <div class="input-group">
                                                        <input class="form-control py-4" id="pass" name="pass" type="password" placeholder="Enter password" required />
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
                                                    <div class="input-group">
                                                        <input class="form-control py-4" id="inputConfirmPassword" name="confirm_password" type="password" placeholder="Confirm password" required />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="toggleConfirmPassword">
                                                                <i class="fas fa-eye"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </main>
        <footer class="py-4 bg-light mt-auto">
            <?php include 'footer.html'; ?>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#pass');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#inputConfirmPassword');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
