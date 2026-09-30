<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $dataFile = __DIR__ . '/data.json';
    $activities = json_decode(file_get_contents($dataFile), true);
    
    $id = (int)$_POST['id'];
    $professor = trim($_POST['professor'] ?? '');
    $applied_at = trim($_POST['applied_at'] ?? '');
    $observations = trim($_POST['observations'] ?? '');
    
    foreach ($activities as &$act) {
        if ($act['id'] === $id) {
            $act['professor'] = $professor;
            $act['applied_at'] = $applied_at;
            $act['observations'] = $observations;
            break;
        }
    }
    file_put_contents($dataFile, json_encode($activities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

header('Location: index.php');
exit;