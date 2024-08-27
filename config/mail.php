<?php

$mail = new \MLAB\SdkMailer\Service\Mail();
$mail->setHost(env('MAIL_HOST', 'mailhog'));
$mail->setDriver(env('MAIL_DRIVER', 'mailhog'));
$mail->setPassword(env('MAIL_PASSWORD', ''));
$mail->setUser(env('MAIL_USER', ''));
$mail->setEmailFromAddress(env('MAIL_FROM_ADDRESS'));