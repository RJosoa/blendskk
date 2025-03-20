<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testEmail()
    {
        $this->user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $this->user->getEmail());
    }

    public function testAgreeTerms()
    {
        $this->user->setAgreeTerms(true);
        $this->assertTrue($this->user->isAgreeTerms());

        $this->user->setAgreeTerms(false);
        $this->assertFalse($this->user->isAgreeTerms());
    }

    public function testBio()
    {
        $this->user->setBio('This is a test bio');
        $this->assertEquals('This is a test bio', $this->user->getBio());
    }

}
