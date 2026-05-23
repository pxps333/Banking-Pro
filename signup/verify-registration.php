<?php
$pageName  = "Registration";
require_once './layout/header.php';

if (isset($_POST['regSubmit'])) {
    $acct_no = "9909" . (substr(number_format(time() * rand(), 0, '', ''), 0, 6));
    $acct_type = $_POST['acct_type'];
    $acct_currency = $_POST['acct_currency'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $acct_occupation = $_POST['occupation'];
    $acct_status = "hold";
    $country = $_POST['country'];
    $acct_gender = $_POST['radio-name'];
    $address = $_POST['address'];
    $suite = $_POST['suite'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $acct_address = $address . " " . $suite . " " . $city . " " . $state . " " . $zipcode;
    $acct_email = $_POST['acct_email'];
    $acct_phone = $_POST['phoneNumber'];
    $acct_username = $_POST['username'];
    $acct_password = $_POST['acct_password'];
    $confirmPassword = $_POST['confirmPassword'];
    $ssn = $_POST['ssn'];
    $confirm_ssn = $_POST['confirm-ssn'];
    $acct_dob = $_POST['dob'];
    $acct_pin = inputValidation($_POST['acct_pin']);

    if ($acct_password !== $confirmPassword) {
        notify_alert('Password not matched', 'danger', '3000', 'close');
    } elseif ($ssn !== $confirm_ssn) {
        notify_alert('SSN / TIN not matched', 'danger', '3000', 'close');
    } else {
        $usersVerified = "SELECT * FROM users WHERE acct_email=:acct_email or acct_username=:acct_username";
        $stmt = $conn->prepare($usersVerified);
        $stmt->execute([
            'acct_email' => $acct_email,
            'acct_username' => $acct_username
        ]);

        if ($stmt->rowCount() > 0) {
            notify_alert('Email or Username Already Exist', 'danger', '3000', 'close');
        } else {
            $profilePic = 'default.png';
            if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $profileFile = $_FILES['profile_pic'];
                $profileName = time() . '_' . basename($profileFile['name']);
                $profileDest = '../assets/profile/' . $profileName;
                if (move_uploaded_file($profileFile['tmp_name'], $profileDest)) {
                    $profilePic = $profileName;
                }
            }

            $frontId = '';
            if (!empty($_FILES['frontID']['name']) && $_FILES['frontID']['error'] === UPLOAD_ERR_OK) {
                $frontFile = $_FILES['frontID'];
                $frontName = time() . '_front_' . basename($frontFile['name']);
                $frontDest = '../assets/idcard/' . $frontName;
                if (move_uploaded_file($frontFile['tmp_name'], $frontDest)) {
                    $frontId = $frontName;
                }
            }

            $backId = '';
            if (!empty($_FILES['backID']['name']) && $_FILES['backID']['error'] === UPLOAD_ERR_OK) {
                $backFile = $_FILES['backID'];
                $backName = time() . '_back_' . basename($backFile['name']);
                $backDest = '../assets/idcard/' . $backName;
                if (move_uploaded_file($backFile['tmp_name'], $backDest)) {
                    $backId = $backName;
                }
            }

            $registered = "INSERT INTO users (acct_username, firstname, lastname, acct_email, acct_password, acct_no, acct_type, acct_gender, acct_currency, acct_status, acct_phone, acct_occupation, country, state, acct_address, acct_dob, acct_pin, ssn, frontID, backID, image) VALUES (:acct_username, :firstname, :lastname, :acct_email, :acct_password, :acct_no, :acct_type, :acct_gender, :acct_currency, :acct_status, :acct_phone, :acct_occupation, :country, :state, :acct_address, :acct_dob, :acct_pin, :ssn, :frontID, :backID, :image)";

            $reg = $conn->prepare($registered);
            $reg->execute([
                'acct_username'   => $acct_username,
                'firstname'       => $firstname,
                'lastname'        => $lastname,
                'acct_email'      => $acct_email,
                'acct_password'   => password_hash((string)$acct_password, PASSWORD_BCRYPT),
                'acct_no'         => $acct_no,
                'acct_type'       => $acct_type,
                'acct_gender'     => $acct_gender,
                'acct_currency'   => $acct_currency,
                'acct_status'     => $acct_status,
                'acct_phone'      => $acct_phone,
                'acct_occupation' => $acct_occupation,
                'country'         => $country,
                'state'           => $state,
                'acct_address'    => $acct_address,
                'acct_dob'        => $acct_dob,
                'acct_pin'        => $acct_pin,
                'ssn'             => $ssn,
                'frontID'         => $frontId,
                'backID'          => $backId,
                'image'           => $profilePic,
            ]);

            toast_alert('success', 'Account created successfully. Please proceed to login.', 'Approved');
        }
    }
}
?>

<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="../assets/images/logo/logo.png" alt="Logo" style="height: 60px; width: auto;">
                        <h1 style="font-size: 1.8rem; font-weight: bold;">Create Your Account</h1>
                        <p style="font-size: 1rem; color: #6b7280;">Fast, secure & 100% online banking</p>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="field-wrapper">
                            <label for="firstname">First Name*</label>
                            <input id="firstname" name="firstname" type="text" class="form-control" placeholder="Enter your first name">
                        </div>

                        <div class="field-wrapper">
                            <label for="lastname">Last Name*</label>
                            <input id="lastname" name="lastname" type="text" class="form-control" placeholder="Enter your last name">
                        </div>

                        <div class="field-wrapper">
                            <label for="acct_email">Email Address*</label>
                            <input id="acct_email" name="acct_email" type="email" class="form-control" placeholder="Enter your email">
                        </div>

                        <div class="field-wrapper">
                            <label for="acct_password">Password*</label>
                            <input id="acct_password" name="acct_password" type="password" class="form-control" placeholder="Enter your password">
                        </div>

                        <div class="field-wrapper">
                            <label for="confirmPassword">Confirm Password*</label>
                            <input id="confirmPassword" name="confirmPassword" type="password" class="form-control" placeholder="Re-enter your password">
                        </div>

                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary" name="regSubmit">Sign Up</button>
                        </div>

                        <div style="text-align: center; margin-top: 10px;">
                            <a href="../login.php" style="color: #3b82f6; text-decoration: none;">Already have an account? Log in here.</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>