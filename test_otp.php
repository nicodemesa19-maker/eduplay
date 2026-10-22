<?php
// Generate test OTP
$otp = '123456';
$hash = password_hash($otp, PASSWORD_BCRYPT, ['cost' => 12]);

echo "Test OTP: " . $otp . "\n";
echo "Hashed: " . $hash . "\n";
echo "Verify: " . (password_verify($otp, $hash) ? 'YES' : 'NO') . "\n";
?>
