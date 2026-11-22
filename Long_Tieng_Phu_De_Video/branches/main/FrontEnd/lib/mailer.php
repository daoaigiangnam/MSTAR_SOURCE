<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

function mailer_instance(): PHPMailer
{
    if (!class_exists(PHPMailer::class)) {
        throw new RuntimeException('PHPMailer is not installed. Run composer install in FrontEnd/.');
    }
    $config = app_config()['mail'];
    foreach (['host', 'username', 'password', 'from_email'] as $key) {
        if (($config[$key] ?? '') === '') {
            throw new RuntimeException('SMTP configuration is incomplete. Check MAIL_* environment variables.');
        }
    }
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->Port = (int)$config['port'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = ($config['encryption'] ?? '') === 'ssl'
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($config['from_email'], $config['from_name']);
    return $mail;
}

function send_account_mail(string $to, string $subject, string $body): bool
{
    try {
        $mail = mailer_instance();
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);
        return $mail->send();
    } catch (Throwable $e) {
        error_log('VideoDubing mail error: ' . $e->getMessage());
        return false;
    }
}

function send_contact_mail(string $to, string $subject, string $body, ?array $attachment = null): bool
{
    try {
        $mail = mailer_instance();
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false);
        if ($attachment && ($attachment['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $tmp = (string)($attachment['tmp_name'] ?? '');
            if ($tmp === '' || !is_uploaded_file($tmp)) {
                throw new RuntimeException('Tệp biên nhận không còn hợp lệ sau khi upload.');
            }
            $name = basename((string)($attachment['name'] ?? 'bien-nhan'));
            $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $name) ?: 'bien-nhan';
            $mail->addAttachment($tmp, $safe);
        }
        return $mail->send();
    } catch (Throwable $e) {
        error_log('VideoDubing contact mail error: ' . $e->getMessage());
        return false;
    }
}
