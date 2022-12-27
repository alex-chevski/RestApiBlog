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
            'INSERT INTO posts (title, url_key, content, description, published_date) VALUES(:title, :url_key, :content, :description, :published_date)'
        );

        return $stm->execute(
            [
                'title' => $data['title'],
                'url_key' => str_replace(' ', '-', $data['title']),
                'content' => $data['content'],
                'description' => $data['description'],
                'published_date' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public function update(array $data, string $id_post)
    {
        $stm = $this->getConnection()->prepare(
            'UPDATE posts SET title = :title, url_key = :url_key, content = :content, description = :description, published_date = :published_date WHERE url_key = :id_post'
        );

        return $stm->execute([
            'title' => $data['title'],
            'url_key' => str_replace(' ', '-', $data['title']),
            'content' => $data['content'],
            'description' => $data['description'],
            'published_date' => date('Y-m-d H:i:s'),
            'id_post' => $id_post,
        ]);
    }

    public function getList(int $page = 1, int $limit = 3, string $keywords = '', string $direction = 'ASC'): ?array
    {
        $start = ($limit * $page) - $limit;
        $statement = $this->getConnection()->prepare(
            "SELECT * FROM posts WHERE title like :keywords ORDER BY published_date {$direction} LIMIT {$start},  {$limit}"
        );

        $statement->execute([
            'keywords' => "%{$keywords}%",
        ]);

        return $statement->fetchAll();
    }

    public function getByUrlKey(string $url_key)
    {
        $statement = $this->getConnection()->prepare(
            'SELECT * FROM posts WHERE url_key = :url_key'
        );

        $statement->execute([
            'url_key' => $url_key,
        ]);

        return array_shift($statement->fetchAll());
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
