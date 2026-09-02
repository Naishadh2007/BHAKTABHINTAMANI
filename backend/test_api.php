<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'API PHP Script is working perfectly on InfinityFree!',
    'time' => date('Y-m-d H:i:s')
]);
