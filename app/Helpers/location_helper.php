<?php

if (! function_exists('location_image_url')) {
    /**
     * Public URL for a location photo stored in public/uploads/locations/.
     */
    function location_image_url(?string $filename): ?string
    {
        if ($filename === null || $filename === '') {
            return null;
        }

        return base_url('uploads/locations/' . $filename);
    }
}
