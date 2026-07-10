<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/tests');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = str_replace(
        ["route('datasets.", "route('reviews.", "route('analytics.", "route('preprocessing.", "route('export."],
        ["route('analyst.datasets.", "route('analyst.reviews.", "route('analyst.analytics.", "route('analyst.preprocessing.", "route('analyst.export."],
        $content
    );
    
    // For reports, testing usually involves analyst routes because viewer doesn't create reports.
    // If the test checks if a viewer can access 'reports.index', it might need 'viewer.reports.index'
    $newContent = str_replace("route('reports.", "route('analyst.reports.", $newContent);
    // Let's replace 'analyst.reports.index' with 'viewer.reports.index' if we're testing the viewer route specifically,
    // but the test is likely using the analyst route.
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated test: " . $path . "\n";
    }
}
