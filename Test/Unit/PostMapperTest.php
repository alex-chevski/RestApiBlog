<?php

declare(strict_types=1);

namespace Alex\RestApiBlog\Test\Unit;

use Alex\RestApiBlog\Database;
use Alex\RestApiBlog\Models\PostMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class PostMapperTest extends TestCase
{
    private PostMapper $obj;
    private MockObject|\PDO $database;
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->obj = new PostMapper($this->database);

        $this->pdo = $this->createMock(\PDO::class);
        $this->database->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->pdo)
        ;

        $this->pdoStatement = $this->createMock(\PDOStatement::class);
    }

    public function testUpdate(): void
    {
        $url_key = 'test_post';
        $expectedResult = [
            'title' => 'test_post',
            'url_key' => 'test_post',
            'content' => 'test_post',
            'description' => 'test_post',
            'published_date' => date('Y-m-d H:i:s'),
            'id_post' => $url_key,
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE posts SET title = :title, url_key = :url_key, content = :content, description = :description, published_date = :published_date WHERE url_key = :id_post'))
            ->willReturn($this->pdoStatement)
        ;

        // $this->pdoStatement->expects($this->once())
        // ->method('bindParam')
        // ->with($this->equalTo(':id_post', $url_key))
        // ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($expectedResult))
            ->willReturn(true)
        ;

        $this->assertTrue($this->obj->update($expectedResult, $url_key));
    }

     public function testDelete(): void
     {
         $post_id = 'post_test';
         $this->pdo->expects($this->once())
             ->method('prepare')
             ->with('DELETE FROM posts WHERE posts.url_key = :url_key')
             ->willReturn($this->pdoStatement)
         ;

         $this->pdoStatement->expects($this->once())
             ->method('execute')
             ->with($this->equalTo(['url_key' => $post_id]))
             ->willReturn(true)
         ;

         $this->assertTrue($this->obj->delete($post_id));
     }

    public function testCreate(): void
    {
        $expectedResult = [
            'title' => 'test_post',
            'url_key' => 'test_post',
            'image_path' => 'storage/images/2023',
            'content' => 'test_post',
            'description' => 'test_post',
            'published_date' => date('Y-m-d H:i:s'),
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO posts (title, url_key, image_path, content, description, published_date) VALUES(:title, :url_key, :image_path,  :content, :description, :published_date)'))
            ->willReturn($this->pdoStatement)
        ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(
                $expectedResult
            ))
            ->willReturn(true)
        ;

        $this->assertTrue($this->obj->create($expectedResult), 'метод create выдал false');
    }

    public function testGetByUrlKey(): void
    {
        $url_key = 'test_post';
        $expectedResult = [
            'title' => 'test_post',
            'content' => 'content',
            'description' => 'description',
        ];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM posts WHERE url_key = :url_key LIMIT 1'))
            ->willReturn($this->pdoStatement)
        ;
        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['url_key' => $url_key]))
        ;
        $this->pdoStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getByUrlKey($url_key);
        $this->assertNotEmpty($result);
        $this->assertEquals('test_post', $result['title']);
        $this->assertEquals('content', $result['content']);
        $this->assertEquals('description', $result['description']);
        $this->assertCount(3, $result);
    }

    public function testGetByUrlKeyEmpty(): void
    {
        $url_key = 'test_post';
        $expectedResult = [];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM posts WHERE url_key = :url_key LIMIT 1'))
            ->willReturn($this->pdoStatement)
        ;
        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['url_key' => $url_key]))
        ;
        $this->pdoStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getByUrlKey($url_key);
        $this->assertEmpty($result);
    }

    public function testGetListEmpty(): void
    {
        $expectedResult = [];
        $direction = 'ASC';
        $keywords = 'post_test';
        $page = 1;
        $start = 0;
        $limit = 3;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo("SELECT * FROM posts WHERE title like :keywords ORDER BY published_date {$direction} LIMIT {$start}, {$limit}"))
            ->willReturn($this->pdoStatement)
        ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['keywords' => "%{$keywords}%"]))
        ;

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getList($page, $limit, $keywords, $direction);
        $this->assertEmpty($result);
    }

    public function testGetList(): void
    {
        $expectedResult = ['title' => 'post_test', 'title' => 'post_test', 'content' => 'post_test'];
        $direction = 'ASC';
        $keywords = 'post_test';
        $page = 1;
        $start = 0;
        $limit = 3;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo("SELECT * FROM posts WHERE title like :keywords ORDER BY published_date {$direction} LIMIT {$start}, {$limit}"))
            ->willReturn($this->pdoStatement)
        ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['keywords' => "%{$keywords}%"]))
        ;

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getList($page, $limit, $keywords, $direction);
        $this->assertNotEmpty($result);
    }

    public function testGetTotalCountEmpty(): void
    {
        $expectedResult = [];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT count(post_id) as total  FROM posts'))
            ->willReturn($this->pdoStatement)
        ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
        ;

        $this->pdoStatement->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getTotalCount();
        $this->assertEmpty($result);
    }

    public function testGetTotalCount(): void
    {
        $expectedResult = [
            'title' => 'test_post',
            'content' => 'test_post',
            'description' => 'test_post',
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
        // для проверки запроса чтобы он не изменялся
            ->with($this->equalTo('SELECT count(post_id) as total  FROM posts'))
            ->willReturn($this->pdoStatement)
        ;

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            // ->with($this->equalTo(':limit'), $this->equalTo($limit))
        ;

        $this->pdoStatement->expects($this->once())
            ->method('fetchColumn')
            ->willReturn($expectedResult)
        ;

        $result = $this->obj->getTotalCount();
        $this->assertNotEmpty($result, 'Ожидаемый результат должен быть не пустым');
    }
}
