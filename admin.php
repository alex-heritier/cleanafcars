<?

if ($_POST["passcode"] == "welcome1") {
  require('dashboard.html');
} else {
  require('auth.html');
}
