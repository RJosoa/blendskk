<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    // Test pour un utilisateur normal (doit rediriger)
    public function testRedirectIfRegularUserAuthenticated()
    {
        $client = static::createClient();

        // Créer un utilisateur avec tous les champs obligatoires
        $user = new User();
        $user->setEmail('user@test.com');
        $user->setUsername('test_user');
        $user->setRoles(['ROLE_USER']);
        $user->setAgreeTerms(true); // Ajouter cette ligne pour le champ obligatoire

        // Récupérer le service de hachage de mot de passe
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $hashedPassword = $passwordHasher->hashPassword($user, 'test_password');
        $user->setPassword($hashedPassword);

        // Persister l'utilisateur en base de données
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Connecter l'utilisateur
        $client->loginUser($user);

        // Faire une requête
        $client->request('GET', '/login');

        // Vérifier la redirection
        $this->assertResponseRedirects('/');

        // Nettoyer après le test
        $entityManager->remove($user);
        $entityManager->flush();

    }

    // Test pour un utilisateur non connecté
    public function testLoginPageForUnauthenticatedUser()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        // Vérifie que la page se charge correctement
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Sign in');

        // Vérifie que le formulaire est présent
        $this->assertSelectorExists('form');
    }
}
