<#1>
<?php
require_once('./Customizing/global/plugins/Services/EventHandling/EventHook/UserDefaults/classes/UDFCheck/class.ilUDFCheck.php');
require_once('./Customizing/global/plugins/Services/EventHandling/EventHook/UserDefaults/classes/UserSetting/class.ilUserSetting.php');
ilUDFCheck::installDB();
ilUserSetting::installDB();
?>