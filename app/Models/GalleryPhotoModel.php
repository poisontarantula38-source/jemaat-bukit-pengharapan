<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryPhotoModel extends Model
{
    protected $table = 'gallery_photos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['filename', 'caption', 'alt_text'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public static function isDatabaseConfigured(): bool
    {
        return SiteContentModel::isDatabaseConfigured();
    }

    public function latest(?int $limit = null): array
    {
        $builder = $this->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');

        return $limit === null ? $builder->findAll() : $builder->findAll($limit);
    }
}
