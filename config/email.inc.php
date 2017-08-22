<?php
$EMAIL['Password']['Reset'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Your BEAT password has been reset to {$password}<br><br>

Please login with this password and change it to something you will remember.<br><br>

Sincerely,<br>
BEAT team
"
;
$EMAIL['Password']['Link'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Click this link to reset your email password: <a href='{$reset_link}'>Reset Link</a><br><br>

Sincerely,<br>
BEAT team
";

$EMAIL['Pin']['Reset'] = "
Hi {$qData['name']} {$qData['last_name']},<br><br>

Here's your BEAT Personal Identification Number (PIN) as requested: {$pin}<br><br>

Sincerely,<br>
BEAT team
"
;
?>