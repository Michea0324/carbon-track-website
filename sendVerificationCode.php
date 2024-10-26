<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    require 'src/Exception.php';
    require 'src/PHPMailer.php';
    require 'src/SMTP.php';
require_once 'global_variables.php';
header('Content-Type: application/json');

// 开启新会话或继续会话
session_start();

// 清理和验证邮箱地址
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => '无效的邮箱地址。']);
    exit;
}

$verificationCode = rand(100000, 999999); // 生成6位数的验证码

// 将验证码保存在会话中，以便验证
$_SESSION['verification_code'] = $verificationCode;

$mail = new PHPMailer(true);

try {
    // 邮件服务器设置
    $mail = initializeMailer();

    // 收件人设置
    $mail->setFrom('jeffery.lyu@qq.com', 'Regsitery');
    $mail->addAddress($email); // 添加收件人

    // 邮件内容设置
    $mail->isHTML(true);
    $mail->Subject = '[CarbonTrack]Registration Verification Code 您的注册验证码';
    $mail->Body    = 'Thank you for your support. Your registration verification code is: ' . $verificationCode;

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
}
?>
