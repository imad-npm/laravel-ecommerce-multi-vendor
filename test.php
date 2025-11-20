<?php
$directory = __DIR__ . '/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

$replaceMap = [
    // Indigo → Primary
    '/indigo-\d{2,3}/' => 'primary',
    '/text-indigo-\d{2,3}/' => 'text-primary',
    '/bg-indigo-\d{2,3}/' => 'bg-primary',
    '/border-indigo-\d{2,3}/' => 'border-primary',
    '/hover:bg-indigo-\d{2,3}/' => 'hover:bg-primary/80',

    // Blue → Primary
    '/blue-\d{2,3}/' => 'primary',
    '/text-blue-\d{2,3}/' => 'text-primary',
    '/bg-blue-\d{2,3}/' => 'bg-primary',
    '/hover:bg-blue-\d{2,3}/' => 'hover:bg-primary/80',

    // Slate/Zinc → Gray
    '/slate-\d{2,3}/' => 'gray',
    '/zinc-\d{2,3}/' => 'gray',
];

foreach ($files as $file) {
    if ($file->isFile() && preg_match('/\.blade\.php$/', $file)) {
        $content = file_get_contents($file);
        foreach ($replaceMap as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        file_put_contents($file, $content);
    }
}

echo "Migration done!";
