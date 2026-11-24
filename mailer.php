<?php
/**
 * mailer.php
 * Email utility using PHPMailer for TrendCart / MCA_2026.
 * Values are loaded from .env file via env.php.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include environment loader and PHPMailer classes
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

// SMTP Configuration Constants fetched from .env
if (!defined('SMTP_HOST'))       define('SMTP_HOST', env('SMTP_HOST', 'smtp.gmail.com'));
if (!defined('SMTP_PORT'))       define('SMTP_PORT', (int) env('SMTP_PORT', 465));
if (!defined('SMTP_SECURE'))     define('SMTP_SECURE', env('SMTP_SECURE', 'ssl'));
if (!defined('SMTP_USERNAME'))   define('SMTP_USERNAME', env('SMTP_USERNAME', 'kansagrajanki@gmail.com'));
if (!defined('SMTP_PASSWORD'))   define('SMTP_PASSWORD', env('SMTP_PASSWORD', ''));
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', env('SMTP_FROM_EMAIL', env('SMTP_USERNAME', 'kansagrajanki@gmail.com')));
if (!defined('SMTP_FROM_NAME'))  define('SMTP_FROM_NAME', env('SMTP_FROM_NAME', env('APP_NAME', 'TrendCart')));

// Global variable to store last error message for easy debugging
$last_mail_error = '';

/**
 * Send an email using PHPMailer.
 *
 * @param string      $to      Recipient email address
 * @param string      $subject Email subject line
 * @param string      $body    HTML or plain text email content
 * @param string|null $file    Optional file path for attachment
 * @return bool Returns true on success, false on failure
 */
function sendEmail($to, $subject, $body, $file = null)
{
    global $last_mail_error;
    $last_mail_error = '';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        // $mail->SMTPDebug = 2; // Uncomment for debugging SMTP connection
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        // SSL options to avoid certificate validation issues on local server (Laragon / XAMPP)
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ];

        // Sender & Recipient settings
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);

        // Attachment
        if (!empty($file) && file_exists($file)) {
            $mail->addAttachment($file);
        }

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        // Send mail
        if ($mail->send()) {
            return true;
        }

        $last_mail_error = $mail->ErrorInfo;
        return false;
    } catch (Exception $e) {
        $last_mail_error = $mail->ErrorInfo ?: $e->getMessage();
        return false;
    }
}

/**
 * Get the last email error message if sendEmail fails.
 *
 * @return string
 */
function getMailError()
{
    global $last_mail_error;
    return $last_mail_error;
}