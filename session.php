<?php
session_start();

function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

function clearMessages() {
    unset($_SESSION['success_message']);
    unset($_SESSION['error_message']);
}
?>