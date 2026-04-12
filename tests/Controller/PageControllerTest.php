<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional smoke tests for public routes.
 *
 * Each test verifies that the page renders (HTTP 200) and that key content
 * is present, catching regressions in routing, template errors or missing
 * controller dependencies.
 */
class PageControllerTest extends WebTestCase
{
    // -------------------------------------------------------------------------
    // Smoke tests — every public route must return 200
    // -------------------------------------------------------------------------

    public function testHomeReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.site-header');
    }

    public function testAboutReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
    }

    public function testCivitalismeReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/civitalisme');

        $this->assertResponseIsSuccessful();
    }

    public function testCivitalismeInstitutionnelReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/civitalisme/cadre-institutionnel');

        $this->assertResponseIsSuccessful();
    }

    public function testContactReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testMentionsLegalesReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/mentions-legales');

        $this->assertResponseIsSuccessful();
    }

    public function testPolitiqueConfidentialiteReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/politique-confidentialite');

        $this->assertResponseIsSuccessful();
    }

    // -------------------------------------------------------------------------
    // Audience redirects
    // -------------------------------------------------------------------------

    public function testCivitalismeGrandPublicRedirects(): void
    {
        $client = static::createClient();
        $client->request('GET', '/civitalisme/grand-public');

        $this->assertResponseRedirects('/civitalisme');
    }

    public function testCivitalismeInstitutionnelRedirects(): void
    {
        $client = static::createClient();
        $client->request('GET', '/civitalisme/institutionnel');

        $this->assertResponseRedirects('/civitalisme/cadre-institutionnel');
    }

    public function testCivitalismeUnknownAudienceReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/civitalisme/inconnu');

        $this->assertResponseStatusCodeSame(404);
    }

    // -------------------------------------------------------------------------
    // Contact form — invalid submissions must NOT redirect
    // -------------------------------------------------------------------------

    public function testContactFormShowsErrorsOnEmptySubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $form = $crawler->selectButton('Envoyer')->form();
        $client->submit($form, [
            'contact[name]' => '',
            'contact[email]' => '',
            'contact[subject]' => '',
            'contact[message]' => '',
            'contact[consent]' => '0',
        ]);

        // Invalid form must stay on /contact (no redirect)
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testContactFormShowsErrorOnInvalidEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $form = $crawler->selectButton('Envoyer')->form();
        $client->submit($form, [
            'contact[name]' => 'Test User',
            'contact[email]' => 'not-a-valid-email',
            'contact[subject]' => 'Test',
            'contact[message]' => 'Un message de test suffisamment long.',
            'contact[consent]' => '1',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    // -------------------------------------------------------------------------
    // Security headers — every public response must include them
    // -------------------------------------------------------------------------

    public function testSecurityHeadersArePresentOnHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertNotEmpty($response->headers->get('Content-Security-Policy'), 'CSP header must be set');
        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertNotEmpty($response->headers->get('Referrer-Policy'));
    }
}
