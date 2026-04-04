<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequest
{
    #[Assert\NotBlank(message: 'Indique ton nom ou celui de ton organisation.')]
    #[Assert\Length(min: 2, max: 120)]
    public ?string $name = null;

    #[Assert\NotBlank(message: 'Une adresse e-mail est nécessaire pour la réponse.')]
    #[Assert\Email(message: 'Cette adresse e-mail ne semble pas valide.')]
    #[Assert\Length(max: 180)]
    public ?string $email = null;

    #[Assert\Length(max: 120)]
    public ?string $company = null;

    #[Assert\NotBlank(message: 'Ajoute un objet clair pour ton message.')]
    #[Assert\Length(min: 4, max: 180)]
    public ?string $subject = null;

    #[Assert\NotBlank(message: 'Explique brièvement ton besoin.')]
    #[Assert\Length(min: 20, max: 5000)]
    public ?string $message = null;

    #[Assert\IsTrue(message: 'Merci de confirmer l’envoi de tes données pour être recontacté.')]
    public bool $consent = false;

    #[Assert\Blank]
    public ?string $website = null;
}
