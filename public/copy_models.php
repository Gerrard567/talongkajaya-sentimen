<?php
// Copy files
$srcDir = __DIR__ . '/../python-api/models/';
$destDir = __DIR__ . '/../app/Python/';

if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

$m1 = copy($srcDir . 'model_naive_bayes.pkl', $destDir . 'model_naive_bayes.pkl');
$m2 = copy($srcDir . 'vectorizer.pkl', $destDir . 'vectorizer.pkl');
$m3 = copy(__DIR__ . '/../python-api/metrics.json', $destDir . 'metrics.json');

echo json_encode([
    'model_copied' => $m1,
    'vectorizer_copied' => $m2,
    'metrics_copied' => $m3,
]);
unlink(__FILE__); // self-destruct after run!
