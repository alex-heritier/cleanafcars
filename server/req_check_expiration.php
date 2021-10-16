<?

require_once 'lib/check_expiration.php';

$result = check_expiration();

echo json_encode($result);
