<?php

namespace App\Traits;

trait SanitizesInput
{
    protected function sanitizeInput($data)
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = strip_tags(trim($value));
                $sanitized[$key] = str_replace("\0", '', $sanitized[$key]);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}

