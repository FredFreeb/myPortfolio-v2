<?php

namespace App\Tests\Form;

use App\Form\ContactType;
use App\Form\Model\ContactRequest;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Unit tests for the ContactType form.
 *
 * Validates that:
 *  - valid data produces a valid form
 *  - each required field triggers an error when blank
 *  - the honeypot field blocks submission when filled
 *  - email format is properly validated
 *  - length constraints are enforced
 */
class ContactTypeTest extends TypeTestCase
{
    // -------------------------------------------------------------------------
    // Happy path
    // -------------------------------------------------------------------------

    public function testValidDataProducesValidForm(): void
    {
        $formData = [
            'name' => 'Frédéric',
            'email' => 'fred@example.fr',
            'company' => 'Acme',
            'subject' => 'Demande de mission',
            'message' => 'Bonjour, je souhaite discuter d\'un projet web avec vous.',
            'consent' => true,
            'website' => '',
        ];

        $form = $this->factory->create(ContactType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid(), implode(', ', array_map(
            static fn ($e) => $e->getMessage(),
            iterator_to_array($form->getErrors(true))
        )));

        /** @var ContactRequest $data */
        $data = $form->getData();
        $this->assertSame('Frédéric', $data->getName());
        $this->assertSame('fred@example.fr', $data->getEmail());
        $this->assertSame('Acme', $data->getCompany());
    }

    // -------------------------------------------------------------------------
    // Required fields
    // -------------------------------------------------------------------------

    public function testBlankNameIsInvalid(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => '',
            'email' => 'fred@example.fr',
            'subject' => 'Objet',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('name')->getErrors());
    }

    public function testBlankEmailIsInvalid(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => '',
            'subject' => 'Objet',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('email')->getErrors());
    }

    public function testBlankSubjectIsInvalid(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => 'fred@example.fr',
            'subject' => '',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('subject')->getErrors());
    }

    public function testBlankMessageIsInvalid(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => 'fred@example.fr',
            'subject' => 'Objet',
            'message' => '',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('message')->getErrors());
    }

    public function testUncheckedConsentIsInvalid(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => 'fred@example.fr',
            'subject' => 'Objet',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'consent' => false,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('consent')->getErrors());
    }

    // -------------------------------------------------------------------------
    // Format & length constraints
    // -------------------------------------------------------------------------

    public function testInvalidEmailFormatIsRejected(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => 'pas-un-email',
            'subject' => 'Objet',
            'message' => 'Un message suffisamment long pour passer la validation.',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('email')->getErrors());
    }

    public function testMessageTooShortIsRejected(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Fred',
            'email' => 'fred@example.fr',
            'subject' => 'Objet',
            'message' => 'Court',
            'consent' => true,
            'website' => '',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('message')->getErrors());
    }

    // -------------------------------------------------------------------------
    // Honeypot
    // -------------------------------------------------------------------------

    public function testFilledHoneypotIsRejected(): void
    {
        $form = $this->factory->create(ContactType::class);
        $form->submit([
            'name' => 'Bot',
            'email' => 'bot@spam.com',
            'subject' => 'Pub',
            'message' => 'Achetez nos produits maintenant en ligne.',
            'consent' => true,
            'website' => 'http://spam.example.com',
        ]);

        $this->assertFalse($form->isValid());
        $this->assertCount(1, $form->get('website')->getErrors());
    }
}
