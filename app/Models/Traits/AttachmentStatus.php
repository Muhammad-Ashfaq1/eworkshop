<?php

namespace App\Models\Traits;

use App\Helpers\FileUploadManager;

trait AttachmentStatus
{
    const ACTIVE_STATUS = 1;

    const INACTIVE_STATUS = 0;

    public function scopeActive($query)
    {
        return $query->where('is_active', self::ACTIVE_STATUS);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', self::INACTIVE_STATUS);
    }

    // get image url Attribute
    public function getImageUrlAttribute($value)
    {
        return $this->getImageUrl($value);
    }

    public function getImageOneUrlAttribute($value)
    {
        return $this->getImageUrl($value);
    }

    public function getImageTwoUrlAttribute($value)
    {
        return $this->getImageUrl($value);
    }

    // get Attachment url Attribute
    public function getAttachmentUrlAttribute($value)
    {
        return $this->getImageUrl($value);
    }

    // delete image
    public function deleteImage($column = 'image_url')
    {
        // Use getRawOriginal to bypass the accessor and get the raw value
        $rawImageUrl = $this->getRawOriginal($column);

        if ($rawImageUrl) {
            FileUploadManager::deleteFile($rawImageUrl);
        }
    }

    // delete attachment
    public function deleteAttachment()
    {
        // Use getRawOriginal to bypass the accessor and get the raw value
        $rawAttachmentUrl = $this->getRawOriginal('attachment_url');

        if ($rawAttachmentUrl) {
            FileUploadManager::deleteFile($rawAttachmentUrl);
        }
    }

    // Purchase order attribute
    protected function getPurchaseOrderAttribute($value)
    {
        return $this->getImageUrl($value);
    }

    // Common helper method
    protected function getImageUrl($value)
    {
        return $value ? asset('storage/'.$value) : null;
    }
}
