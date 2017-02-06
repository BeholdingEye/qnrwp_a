<?php
$rT = <<<EOT
<div id="contact-block">
  <form name="contact-form" id="contact-form" action="" method="post">
    <div id="contact-emailframe">
Your email:<br>
      <input type="email" name="email" id="email" class="user-entry" maxlength="60" required="required">
    </div>
    <div id="contact-textframe">
Message:<br>
      <textarea pattern=".{40,500}" required="required" cols="20" id="message" name="message" class="user-entry" rows="10" minlength="40" maxlength="500" onkeyup="CountTextarea(this,'count')" onblur="CountTextarea(this,'reset')"></textarea>
EOT;
$rT .= '<p id="user-info"><span id="textarea-count">Max 500 characters</span><span id="client-ip">Your IP: '.$_SERVER['REMOTE_ADDR'].'</span>';
$rT .= <<<EOT
</p>
    </div>
    <div id="contact-send-btn">
      <span id="form-send" onclick="SendEmail()">Send</span>
    </div>
  </form>
</div>
EOT;
return $rT;
?>