<?php
// Your reCAPTCHA secret key
$recaptcha_secret = '6LeOwPEpAAAAAHQD6_n9lOrdF1h_BXSl8gOnomd6';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if reCAPTCHA response is set
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha_response = $_POST['g-recaptcha-response'];
        
        // Verify reCAPTCHA response
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_response
        );

        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $response = file_get_contents($verify_url, false, $context);
        $result = json_decode($response, true);

        if ($result['success'] == true) {
            // reCAPTCHA verification passed, process form data

            if (isset($_POST['contact_send_mgs'])) {
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $subject = mysqli_real_escape_string($con, $_POST['subject']);
                $message = mysqli_real_escape_string($con, $_POST['message']);

                $query = "INSERT INTO contactus (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
                $query_run = mysqli_query($con, $query);

                if ($query_run) {
                    $_SESSION['message'] = "Message sent successfully";
                } else {
                    $_SESSION['message'] = "Message Failed";
                }

                header("Location: contact.php");
                exit(0);
            }

            // Add other form handlers here...

        } else {
            // reCAPTCHA verification failed
            $_SESSION['message'] = "reCAPTCHA verification failed!";
            header("Location: contact.php");
            exit(0);
        }
    } else {
        // reCAPTCHA response not set
        $_SESSION['message'] = "Please complete the reCAPTCHA";
        header("Location: contact.php");
        exit(0);
    }
}
?>?>
