<?php


if (!function_exists('getFileIcon')) {
    function getFileIcon($extension)
    {
        $extension = strtolower($extension);

        switch ($extension) {
            case 'pdf':
                return 'pdf';
            case 'doc':
            case 'docx':
                return 'word';
            case 'ppt':
            case 'pptx':
                return 'powerpoint';
            case 'xls':
            case 'xlsx':
                return 'excel';
            case 'zip':
            case 'rar':
                return 'archive';
            default:
                return 'alt';
        }
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

function renderAverageStars($avg)
{
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= '<i class="fas fa-star' . ($i <= $avg ? ' text-warning' : ' text-muted') . '"></i>';
    }
    return $stars;
}


