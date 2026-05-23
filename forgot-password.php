<?php
include_once("layout/header.php");
require_once("include/userClass.php");
require_once("include/loginFunction.php");

if(@$_SESSION['acct_no']){
    header("Location:./user/dashboard.php");
    exit;
}

$success = false;
$error   = '';

if(isset($_POST['reset'])){
    $acct_email = inputValidation($_POST['acct_email']);

    $sql = "SELECT * FROM users WHERE acct_email = :acct_email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['acct_email' => $acct_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        $chars       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $temp_pass   = substr(str_shuffle($chars), 0, 10);
        $hashed      = password_hash($temp_pass, PASSWORD_BCRYPT);

        $upd = $conn->prepare("UPDATE users SET acct_password = :p WHERE acct_email = :e");
        $upd->execute(['p' => $hashed, 'e' => $acct_email]);

        $full_name  = $user['firstname']." ".$user['lastname'];
        $APP_NAME   = WEB_TITLE;
        $APP_URL    = WEB_URL;
        $subject    = "Password Reset — ".$APP_NAME;
        $body       = "
<p>Hello {$full_name},</p>
<p>Your temporary password is: <strong>{$temp_pass}</strong></p>
<p>Please log in and change your password immediately.</p>
<p>{$APP_NAME}</p>";
        $email_message->send_mail($acct_email, $body, $subject);
    }

    $success = true;
}
?>

<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <?php
                    $logoSrc = '/assets/images/logo/'.htmlspecialchars($page['image'] ?? 'logo.png');
                    $logoName = htmlspecialchars($pageTitle ?? WEB_TITLE);
                    ?>
                    <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:20px;">
                        <img src="<?= $logoSrc ?>" alt="<?= $logoName ?>" style="height:56px;width:auto;object-fit:contain;margin-bottom:8px;">
                        <span style="font-size:1.1rem;font-weight:700;color:#3b82f6;letter-spacing:-0.01em;"><?= $logoName ?></span>
                    </div>

                    <h1>Reset Password</h1>
                    <p>Enter your account email and we'll send you a temporary password.</p>

                    <?php if($success): ?>
                    <div class="alert alert-success" role="alert" style="border-radius:10px;margin-bottom:20px;">
                        If that email is on file, a temporary password has been sent. Check your inbox.
                    </div>
                    <div style="text-align:center;margin-top:10px;">
                        <a href="./login.php" style="color:#3b82f6;font-weight:600;text-decoration:none;">← Back to Sign In</a>
                    </div>
                    <?php else: ?>
                    <form class="text-left" method="POST">
                        <div class="form">
                            <div id="email-field" class="field-wrapper input">
                                <label for="acct_email">Email Address</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,12 2,6"></polyline></svg>
                                <input id="acct_email" name="acct_email" type="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                            <div class="field-wrapper">
                                <button type="submit" class="btn btn-primary" name="reset">Send Reset Link</button>
                            </div>
                            <div style="text-align:center;margin-top:16px;">
                                <a href="./login.php" style="color:#888ea8;font-size:13px;text-decoration:none;">← Back to Sign In</a>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("layout/footer.php"); ?>
