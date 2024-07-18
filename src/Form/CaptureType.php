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
                    'male',
                    'femelle',
                    'autre',
                ],
                'expanded' => true,
                'multiple' => false,
            ])

            // ->add('ball', ChoiceType::class, [
            //     'choices' => $this->getBallChoices($options['balls']),
            //     // 'choice_label' => function($choice, $key, $value) {
            //     //     return $choice['name'];
            //     // },
            //     // 'group_by' => function($choice, $key, $value) {
            //     //     return $choice['categoryName'];
            //     // },
            //     'expanded' => false,
            //     'multiple' => false,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
            'balls' => [],
        ]);
    }

    // private function getBallChoices(array $balls): array
    // {
    //     $choices = [];
    //     foreach ($balls as $category) {
    //         foreach ($category['ballsData'] as $ball) {
    //             $choices[$ball['name']] = [
    //                 'name' => $ball['name'],
    //                 'categoryName' => $category['categoryName'],
    //                 'sprite' => $ball['sprite'],
    //             ];
    //         }
    //     }
    //     // dd($choices);
    //     return $choices;
    
    // }
}