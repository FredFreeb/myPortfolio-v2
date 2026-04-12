<?php

namespace App\Form;

use App\Form\Model\AdminContactReply;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminContactReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Objet de la réponse',
                'constraints' => [
                    new NotBlank(message: 'Merci de renseigner un objet.'),
                    new Length(max: 180),
                ],
                'attr' => [
                    'maxlength' => 180,
                    'placeholder' => 'Ex: Re: Votre demande',
                ],
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Réponse',
                'constraints' => [
                    new NotBlank(message: 'Merci de rédiger une réponse.'),
                    new Length(min: 10, max: 10000),
                ],
                'attr' => [
                    'rows' => 10,
                    'placeholder' => "Bonjour,\n\nMerci pour votre message.\n\n...",
                ],
                'help' => 'Le message sera envoyé dans un gabarit HTML propre et lisible.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminContactReply::class,
        ]);
    }
}
