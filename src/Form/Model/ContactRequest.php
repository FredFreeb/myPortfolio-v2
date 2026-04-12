<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequest
{
    #[Assert\NotBlank(message: 'Indique ton nom ou celui de ton organisation.')]
    #[Assert\Length(min: 2, max: 120)]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'Une adresse e-mail est nécessaire pour la réponse.')]
    #[Assert\Email(message: 'Cette adresse e-mail ne semble pas valide.')]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

    #[Assert\Length(max: 120)]
    private ?string $company = null;

    #[Assert\NotBlank(message: 'Ajoute un objet clair pour ton message.')]
    #[Assert\Length(min: 4, max: 180)]
    private ?string $subject = null;

    #[Assert\NotBlank(message: 'Explique brièvement ton besoin.')]
    #[Assert\Length(min: 10, max: 5000, minMessage: 'Ton message doit contenir au moins {{ limit }} caractères.')]
    private ?string $message = null;

    #[Assert\IsTrue(message: 'Merci de confirmer l\'envoi de tes données pour être recontacté.')]
    private bool $consent = false;

    #[Assert\Blank]
    private ?string $website = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isConsent(): bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): static
    {
        $this->consent = $consent;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }
}
