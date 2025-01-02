<?php
include('common.php');

$employee_id = get('id');
$leave_type_id = intval($_GET['leave_type_id']);

// Fetch the current leave balance
$query = "SELECT days_balance,reserved_days FROM leave_balance WHERE employee_id = $employee_id AND leave_type_id = $leave_type_id AND year = $current_year";
$result = mq($query);


if ($row = mfa($result)) {
    $net_balance = $row[0] - $row[1];
    echo json_encode(['days_balance' => $net_balance]);
} else {
    echo json_encode(['days_balance' => 0]);
}
?>
