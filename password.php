<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Password Recovery - Bajumi</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<?php
// Fungsi untuk mengirim email reset password
function sendPasswordResetEmail($email, $reset_link) {
    $to = $email;
    $subject = 'Password Reset Link';
    $message = '
    <html>
    <head>
        <title>Password Reset Link</title>
    </head>
    <body>
        <p>Dear User,</p>
        <p>You have requested to reset your password. Please click the link below to reset your password:</p>
        <p><a href="'.$reset_link.'">Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Thank you,</p>
        <p>Your Company Name</p>
    </body>
    </html>
    ';

    // Headers untuk email HTML
    $headers = array(
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html; charset=iso-8859-1',
        'From' => 'your_email@example.com',
        'Reply-To' => 'your_email@example.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    // Mengirim email
    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Periksa apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sertakan file koneksi ke database
    include 'db_config.php';
    
    // Ambil nilai dari form
    $email = $_POST['email'];

    try {
        // Query untuk memeriksa keberadaan pengguna dengan email yang sesuai
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jika pengguna ditemukan
        if ($user) {
            // Generate unique reset link (dummy link generation, replace with your logic)
            $reset_link = 'http://yourdomain.com/reset_password.php?token=' . uniqid();

            // Kirim email reset password
            if (sendPasswordResetEmail($email, $reset_link)) {
                echo '<div class="alert alert-success" role="alert">Reset password link has been sent to your email.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Failed to send reset password link. Please try again later.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Email not found. Please enter a valid email address.</div>';
        }
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
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Recovery</h3></div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input required class="form-control py-4" id="inputEmailAddress" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                                        </div>
                                        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="login.php">Return to login</a>
                                            <button type="submit" class="btn btn-primary">Reset Password</button>
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
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; Bajumi 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
