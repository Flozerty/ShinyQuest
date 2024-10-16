<?php

namespace App\Form;

use App\Entity\Capture;
use App\Entity\MethodeCapture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShasseStartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbRencontres', IntegerType::class, [
                'required' => false,
                "label" => "Compteur :",
            ])

            ->add('charmeChroma', null, [
                "label" => "Charme chroma"
            ])

            ->add('lieu', TextType::class, [
                "label" => "Lieu :",
                'required' => false,
            ])

            ->add('methodeCapture', EntityType::class, [
                'class' => MethodeCapture::class,
                "label" => "Méthode de capture<span class='asterisk'>*</span> :",
                'label_html' => true,
                'choice_label' => 'nom_methode',
                'placeholder' => 'Choisissez votre méthode de capture'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capture::class,
            // 'games' => [],  // initialisation par défaut.
        ]);
    }
}
