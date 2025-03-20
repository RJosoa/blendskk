<?php

namespace App\Tests\E2E;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Panther\PantherTestCase;

class LoginTest extends PantherTestCase
{
    public function testUserCanLogin(): void
    {
        // Create a test user first
        $user = $this->createTestUser('user@test.com', 'UserTest1234*');

        // Start the browser
        $client = static::createPantherClient();

        // Go to the login page
        $crawler = $client->request('GET', '/login');

        // Fill out the login form with correct field names
        $form = $crawler->selectButton('Sign in')->form();
        $form['_username'] = 'user@test.com';
        $form['_password'] = 'UserTest1234*';

        // Submit the form
        $client->submit($form);

        // Wait a moment for the page to load
        $client->waitFor('body');

        // Take screenshot for debugging (uncomment if needed)
        // $client->takeScreenshot('var/login-result.png');

        // Check if we're redirected to the explorer page
        $currentUrl = $client->getCurrentURL();
        $this->assertStringContainsString('/explorer', $currentUrl, 'Not redirected to explorer page after login');

        // Check for the expected H1 text on explorer page
        $this->assertSelectorTextContains('h1', 'Explore more.', 'Explorer page heading not found');

        // Additional check - we should no longer be on the login page
        $this->assertSelectorNotExists('form input[name="_username"]');
    }

    private function createTestUser(string $email, string $password): User
    {
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Check if user already exists to avoid duplicates
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return $existingUser;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername('panther_user');
        $user->setRoles(['ROLE_USER']);
        $user->setAgreeTerms(true);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

}
