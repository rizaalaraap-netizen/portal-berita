<?php

return [
    'disk' => env('MEDIA_DISK', 'public'),
    'max_upload_kb' => (int) env('MEDIA_MAX_UPLOAD_KB', 4096),
    'allowed_mimes' => ['jpg', 'jpeg', 'png', 'webp', 'svg'],
];
