<?php

namespace App\Form;

use App\Form\Model\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $t = fn(string $key): string => $this->translator->trans($key);

        $builder
            ->add('name', TextType::class, [
                'label' => 'contact.field.name',
                'attr'  => [
                    'placeholder'  => $t('contact.field.name_placeholder'),
                    'autocomplete' => 'name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'contact.field.email',
                'attr'  => [
                    'placeholder'  => $t('contact.field.email_placeholder'),
                    'autocomplete' => 'email',
                ],
            ])
            ->add('company', TextType::class, [
                'label'    => 'contact.field.company',
                'required' => false,
                'attr'     => [
                    'placeholder'  => $t('contact.field.company_placeholder'),
                    'autocomplete' => 'organization',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'contact.field.subject',
                'attr'  => [
                    'placeholder' => $t('contact.field.subject_placeholder'),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact.field.message',
                'attr'  => [
                    'rows'        => 7,
                    'placeholder' => $t('contact.field.message_placeholder'),
                ],
            ])
            ->add('consent', CheckboxType::class, [
                'label'    => 'contact.field.consent',
                'required' => false,
            ])
            ->add('website', HiddenType::class, [
                'required' => false,
                'attr'     => [
                    'autocomplete' => 'off',
                    'tabindex'     => '-1',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequest::class,
        ]);
    }
}
