<?

if ($_POST["passcode"] == "welcome1") {
  require($_SERVER['DOCUMENT_ROOT'] . '/admin/dashboard.html');
} else {
  require($_SERVER['DOCUMENT_ROOT'] . '/admin/auth.html');
}
