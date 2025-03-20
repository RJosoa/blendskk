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

    public function testUsername()
    {
        $this->user->setUsername('testuser');
        $this->assertEquals('testuser', $this->user->getUsername());
    }

    public function testPassword()
    {
        $this->user->setPassword('hashedpassword123');
        $this->assertEquals('hashedpassword123', $this->user->getPassword());
    }

    public function testRoles()
    {
        // Par défaut, devrait avoir ROLE_USER
        $this->assertContains('ROLE_USER', $this->user->getRoles());

        // Test avec rôles personnalisés
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
        $this->assertContains('ROLE_USER', $this->user->getRoles());

        // Vérifie que les rôles sont uniques
        $this->user->setRoles(['ROLE_USER', 'ROLE_USER']);
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
    }

    public function testAvatar()
    {
        $this->user->setAvatar('avatar.jpg');
        $this->assertEquals('avatar.jpg', $this->user->getAvatar());
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

    public function testGetUserIdentifier()
    {
        $this->user->setEmail('identifier@example.com');
        $this->assertEquals('identifier@example.com', $this->user->getUserIdentifier());
    }
}
