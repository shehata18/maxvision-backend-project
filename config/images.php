<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Sizes
    |--------------------------------------------------------------------------
    |
    | The sizes (in pixels) to generate for responsive thumbnails.
    | Each size produces a square-fitted variant of the original image.
    |
    */
    'thumbnail_sizes' => [400, 800, 1200],

    /*
    |--------------------------------------------------------------------------
    | Image Quality
    |--------------------------------------------------------------------------
    |
    | JPEG / WebP quality level used when saving optimized images.
    | Range: 0 (worst) to 100 (best).
    |
    */
    'quality' => 85,

    /*
    |--------------------------------------------------------------------------
    | Max Upload Size
    |--------------------------------------------------------------------------
    |
    | Maximum allowed upload size in kilobytes.
    |
    */
    'max_upload_size' => 5120,

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    */
    'allowed_types' => [
        'image/jpeg',
        'image/png',
        'image/webp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Directories
    |--------------------------------------------------------------------------
    |
    | Base directories for each entity type inside the public disk.
    |
    */
    'directories' => [
        'products'     => 'products',
        'gallery'      => 'products/gallery',
        'solutions'    => 'solutions',
        'case-studies' => 'case-studies',
    ],

];
