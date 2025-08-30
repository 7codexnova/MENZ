<?php
// === CONFIG ==================================================
$TO_EMAIL = "zorg@dagbestedingmenz.nl"; // ontvanger
$BCC_EMAIL = "";              // optioneel eigen BCC
$FROM_NAME = "Website Dagbesteding MENZ";
$SUBJECT_PREFIX = "[Website] ";
$SITE_URL = '/menz/contact.html';         // pas dit aan als je map anders heet
// =============================================================

// Alleen POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$SITE_URL}?error=1");
    exit;
}

// Honeypot veld tegen spam
if (!empty($_POST['website'] ?? '')) {
    header("Location: {$SITE_URL}?sent=1");
    exit;
}

// Data ophalen
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$consent = 'Ja'; // geen checkbox nodig, kan aangepast

// Validatie
$errors = [];
if ($name === '') $errors[] = "Naam is verplicht.";
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "E-mail is ongeldig.";
if ($subject === '') $errors[] = "Onderwerp/activiteit is verplicht.";

if ($errors) {
    header("Location: {$SITE_URL}?error=1");
    exit;
}

// Mail opstellen
$subjectLine = $SUBJECT_PREFIX . $subject;
$body = "Nieuwe inzending via het formulier:\n\n"
      . "Naam: {$name}\n"
      . "E-mail: {$email}\n"
      . "Telefoon: {$phone}\n"
      . "Activiteit/Onderwerp: {$subject}\n\n"
      . "Bericht:\n{$message}\n\n"
      . "--\nVerzonden vanaf {$SITE_URL}";

// Headers
$headers = [];
$headers[] = "From: {$FROM_NAME} <no-reply@" . $_SERVER['SERVER_NAME'] . ">";
$headers[] = "Reply-To: {$name} <{$email}>";
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-Type: text/plain; charset=UTF-8";
if ($BCC_EMAIL) $headers[] = "Bcc: {$BCC_EMAIL}";

// Versturen
$sent = @mail($TO_EMAIL, $subjectLine, $body, implode("\r\n", $headers));

if ($sent) {
    header("Location: {$SITE_URL}?sent=1");
} else {
    header("Location: {$SITE_URL}?error=1");
}
exit;
?>
