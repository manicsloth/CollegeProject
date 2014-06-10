<?php
        require '../init.php';
        $users->logged_in_protect();

//check for required session data
if (empty($_SESSION['activate_email']) || empty ($_SESSION['activate_email_code'])) {
        echo "There has been a error. Sorry.";
        exit();
}
//if email is not tied to an account.
if ($users->email_exists($_SESSION['activate_email']) === false) {
        $errors[] = 'Sorry, we couldn\'t find that email address.';
}
//try to active, display error if cannot (most likely due to account already being activated)
else if ($users->activate($_SESSION['activate_email'], $_SESSION['activate_email_code']) === false) {
        $errors[] = 'Sorry, we couldn\'t activate your account. (Have you already activated it?)';
}

if(empty($errors) === false){
        echo '<p>' . implode('</p><p>', $errors) . '</p>';	
} else {
        header('Location: ../../activate.php?success');
        exit();
}
?>