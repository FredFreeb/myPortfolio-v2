<?php

namespace App\Form;

use App\Form\Model\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de contact institutionnel — page Civitalisme / cadre institutionnel.
 * Réutilise le modèle ContactRequest existant, étend le formulaire standard
 * avec des champs propres au contexte institutionnel (fonction, type de structure).
 */
class CivitalismeContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom et prénom',
                'attr'  => [
                    'placeholder'  => 'Marie Dupont',
                    'autocomplete' => 'name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail professionnel',
                'attr'  => [
                    'placeholder'  => 'marie.dupont@institution.eu',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('company', TextType::class, [
                'label'    => 'Institution ou organisation',
                'required' => false,
                'attr'     => [
                    'placeholder'  => 'Parlement européen, ministère, université…',
                    'autocomplete' => 'organization',
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label'   => 'Type de demande',
                'choices' => [
                    'Audition ou atelier de travail'          => 'Civitalisme — Audition ou atelier',
                    'Relecture critique d\'un rapport'         => 'Civitalisme — Relecture rapport',
                    'Partenariat institutionnel'               => 'Civitalisme — Partenariat institutionnel',
                    'Question juridique ou réglementaire'     => 'Civitalisme — Question juridique',
                    'Simulation ou modélisation économique'   => 'Civitalisme — Simulation économique',
                    'Presse et communication institutionnelle' => 'Civitalisme — Presse',
                    'Autre'                                   => 'Civitalisme — Autre',
                ],
                'placeholder' => 'Sélectionnez un type de demande',
                'attr'        => ['class' => 'civ-select'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr'  => [
                    'rows'        => 6,
                    'placeholder' => 'Décrivez votre démarche, le cadre institutionnel et les suites souhaitées.',
                ],
            ])
            ->add('consent', CheckboxType::class, [
                'label'    => 'J\'accepte que ces informations soient utilisées uniquement pour être recontacté dans le cadre du projet Civitalisme.',
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
