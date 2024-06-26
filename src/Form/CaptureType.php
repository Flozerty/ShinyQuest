<?php

namespace App\Form;

use App\Entity\Capture;
use App\Entity\MethodeCapture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surnom')
            ->add('dateCapture', null, [
                'widget' => 'single_text',
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    '<i class="fa-solid fa-mars"></i>' => 'male',
                    'f' => 'femelle',
                    'x' => 'autre',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('ball')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
        ]);
    }
}
 