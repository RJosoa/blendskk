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
        // Créer d'abord un utilisateur de test
        $this->createTestUser('user@test.com', 'UserTest1234*');

        // Démarrer le navigateur
        $client = static::createPantherClient();

        // Aller à la page de connexion
        $crawler = $client->request('GET', '/login');

        // Remplir le formulaire de connexion avec les noms de champs corrects
        $form = $crawler->selectButton('Sign in')->form();
        $form['_username'] = 'user@test.com';
        $form['_password'] = 'UserTest1234*';

        // Soumettre le formulaire
        $client->submit($form);

        // Attendre un moment pour que la page se charge
        $client->waitFor('body');

        // Prendre une capture d'écran pour le débogage
        // $client->takeScreenshot('var/login-result.png');

        // Vérifier si nous sommes redirigés vers la page explorer
        $currentUrl = $client->getCurrentURL();
        $this->assertStringContainsString('/explorer', $currentUrl, 'Pas redirigé vers la page explorer après connexion');

        // Vérifier le texte H1 attendu sur la page explorer
        $this->assertSelectorTextContains('h1', 'Explore more.', 'Titre de la page explorer non trouvé');

        // Vérification supplémentaire - nous ne devrions plus être sur la page de connexion
        $this->assertSelectorNotExists('form input[name="_username"]');
    }

    private function createTestUser(string $email, string $password): User
    {
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Vérifier si l'utilisateur existe déjà pour éviter les doublons
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
