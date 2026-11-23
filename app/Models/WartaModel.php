<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class WartaModel extends Model
{
    protected $table = 'warta_articles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'slug',
        'category',
        'summary',
        'image_filename',
        'image_alt',
        'content',
        'is_published',
        'published_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public static function isDatabaseConfigured(): bool
    {
        $database = config(Database::class);
        $connection = $database->default;

        return trim((string) ($connection['hostname'] ?? '')) !== ''
            && trim((string) ($connection['database'] ?? '')) !== '';
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->builder()->where('slug', $slug);
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    public function publishedForPublic(?int $limit = null): array
    {
        $builder = $this->where('is_published', 1)
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('published_at', 'DESC');

        $articles = $limit === null ? $builder->findAll() : $builder->findAll($limit);

        return array_map([$this, 'formatForPublic'], $articles);
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $article = $this->where('slug', $slug)
            ->where('is_published', 1)
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->first();

        return $article === null ? null : $this->formatForPublic($article);
    }

    private function formatForPublic(array $article): array
    {
        $publishedAt = $article['published_at'] ?? null;
        $timestamp = $publishedAt === null ? false : strtotime((string) $publishedAt);
        $imageFilename = (string) ($article['image_filename'] ?? '');
        $hasValidImageFilename = basename($imageFilename) === $imageFilename
            && preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $imageFilename) === 1;

        return [
            'id' => (int) $article['id'],
            'slug' => (string) $article['slug'],
            'judul' => (string) $article['title'],
            'kategori' => (string) ($article['category'] ?? ''),
            'ringkasan' => (string) ($article['summary'] ?? ''),
            'foto' => $hasValidImageFilename
                ? 'uploads/warta/' . $imageFilename
                : '',
            'foto_alt' => (string) ($article['image_alt'] ?? ''),
            'isi' => (string) $article['content'],
            'tanggal' => $timestamp === false ? '' : date('d-m-Y', $timestamp),
            'tanggal_iso' => $timestamp === false ? '' : date('Y-m-d', $timestamp),
        ];
    }
}
