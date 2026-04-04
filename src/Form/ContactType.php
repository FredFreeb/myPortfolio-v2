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

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Ton nom ou ton organisation',
                    'autocomplete' => 'name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'bonjour@exemple.fr',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'Organisation',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Optionnel',
                    'autocomplete' => 'organization',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Objet',
                'attr' => [
                    'placeholder' => 'Exemple : site vitrine, mission, conférence...',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'rows' => 7,
                    'placeholder' => 'Décris le contexte, les délais et l’effet recherché.',
                ],
            ])
            ->add('consent', CheckboxType::class, [
                'label' => 'J’accepte que ces informations soient utilisées uniquement pour être recontacté.',
                'required' => false,
            ])
            ->add('website', HiddenType::class, [
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'tabindex' => '-1',
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
