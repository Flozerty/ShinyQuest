<?php

namespace App\Form;

use App\Entity\Capture;
use App\Entity\MethodeCapture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class CaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surnom')
            ->add('dateCapture', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d'), // Définir la date maximale à aujourd'hui
                ],
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'Désolé, vos dons de voyance ne sont pas admis ici.',
                    ])
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'male',
                    'femelle',
                    'autre',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
            'balls' => [],
        ]);
    }
}
