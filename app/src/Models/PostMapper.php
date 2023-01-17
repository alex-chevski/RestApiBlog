<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Models;

use Alex\RestApiBlog\Database;

class PostMapper
{
    public function __construct(private Database $database)
    {
        $this->database = $database;
    }

    public function create(array $data): bool
    {
        $stm = $this->getConnection()->prepare(
            'INSERT INTO posts (title, url_key, image_path, content, description, published_date, author) VALUES(:title, :url_key, :image_path,  :content, :description, :published_date, :author)'
        );

        return $stm->execute(
            [
                'title' => $data['title'],
                'url_key' => str_replace(' ', '-', $data['title']),
                'image_path' => $data['image_path'],
                'content' => $data['content'],
                'description' => $data['description'],
                'published_date' => date('Y-m-d H:i:s'),
                'author' => $data['author'],
            ]
        );
    }

    public function update(array $data, string $id_post): bool
    {
        $stm = $this->getConnection()->prepare(
            'UPDATE posts SET title = :title, url_key = :url_key, content = :content, description = :description, published_date = :published_date, author = :author WHERE url_key = :id_post'
        );

        return $stm->execute([
            'title' => $data['title'],
            'url_key' => str_replace(' ', '-', $data['title']),
            'content' => $data['content'],
            'description' => $data['description'],
            'published_date' => date('Y-m-d H:i:s'),
            'author' => $data['author'],
            'id_post' => $id_post,
        ]);
    }

    public function getList(int $page = 1, int $limit = 3, string $keywords = '', string $direction = 'ASC'): ?array
    {
        $start = ($limit * $page) - $limit;
        $statement = $this->getConnection()->prepare(
            "SELECT * FROM posts WHERE title like :keywords ORDER BY published_date {$direction} LIMIT {$start}, {$limit}"
        );

        $statement->execute([
            'keywords' => "%{$keywords}%",

            // 'direction' => $direction,
            // 'start' => $start,
            // 'limit' => $limit,
        ]);

        return $statement->fetchAll();
    }

    public function getByUrlKey(string $url_key)
    {
        $statement = $this->getConnection()->prepare(
            'SELECT * FROM posts WHERE url_key = :url_key LIMIT 1'
        );

        $statement->execute([
            'url_key' => $url_key,
        ]);

        return $statement->fetch();
    }

    public function delete(string $post_id): bool
    {
        $stm = $this->getConnection()->prepare(
            'DELETE FROM posts WHERE posts.url_key = :url_key'
        );

        return $stm->execute([
            'url_key' => $post_id,
        ]);
    }

    public function getTotalCount(): int
    {
        $stm = $this->getConnection()->prepare(
            'SELECT count(post_id) as total  FROM posts'
        );

        $stm->execute();

        return (int) ($stm->fetchColumn() ?? 0);
    }

    private function getConnection(): \PDO
    {
        return $this->database->getConnection();
    }
}
