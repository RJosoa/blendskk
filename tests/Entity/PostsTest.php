<?php

namespace App\Tests\Entity;

use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Favorites;
use App\Entity\Likes;
use App\Entity\Posts;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    private Posts $post;

    protected function setUp(): void
    {
        $this->post = new Posts();
    }

    public function testSetFeatureImage()
    {
        $this->post->setFeatureImage('test-image.jpg');
        $this->assertEquals('test-image.jpg', $this->post->getFeatureImage());
    }

    public function testSetTitle()
    {
        $this->post->setTitle('Test Title');
        $this->assertEquals('Test Title', $this->post->getTitle());
    }

    public function testSetDescription()
    {
        $this->post->setDescription('Test Description');
        $this->assertEquals('Test Description', $this->post->getDescription());
    }

    public function testSetContent()
    {
        $this->post->setContent('Test Content');
        $this->assertEquals('Test Content', $this->post->getContent());
    }

    public function testSetSlug()
    {
        $this->post->setSlug('test-slug');
        $this->assertEquals('test-slug', $this->post->getSlug());
    }

    public function testSetReport()
    {
        $this->post->setReport(true);
        $this->assertTrue($this->post->isReport());
    }

    public function testSetCreatedAt()
    {
        $date = new \DateTimeImmutable();
        $this->post->setCreatedAt($date);
        $this->assertSame($date, $this->post->getCreatedAt());
    }

    public function testSetUpdatedAt()
    {
        $date = new \DateTimeImmutable();
        $this->post->setUpdatedAt($date);
        $this->assertSame($date, $this->post->getUpdatedAt());
    }
}
