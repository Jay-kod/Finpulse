<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = str_replace(
        ["route('datasets.", "route('reviews.", "route('analytics.", "route('preprocessing.", "route('export."],
        ["route('analyst.datasets.", "route('analyst.reviews.", "route('analyst.analytics.", "route('analyst.preprocessing.", "route('analyst.export."],
        $content
    );
    
    // Handle reports
    if (str_contains($path, 'views\analyst\\')) {
        $newContent = str_replace("route('reports.", "route('analyst.reports.", $newContent);
    } elseif (str_contains($path, 'views\reports\\') || str_contains($path, 'views\search\\')) {
        $newContent = str_replace("route('reports.", "route('viewer.reports.", $newContent);
    }
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated: " . $path . "\n";
    }
}
