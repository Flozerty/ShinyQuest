<?php

namespace App\Controller\Admin;

use App\Entity\Capture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CaptureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Capture::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Captures')
            ->setEntityLabelInSingular('capture');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('pokedex_id'),
            TextField::new('nom_pokemon'),
            TextField::new('surnom'),
            ImageField::new(propertyName: 'img_shiny')
                ->onlyOnIndex(),
            TextField::new(propertyName: 'img_shiny')
                ->onlyOnForms(),
            IntegerField::new('nb_rencontres'),
            DateField::new('date_capture'),
            BooleanField::new('charme_chroma'),
            AssociationField::new('methodeCapture'),
            BooleanField::new('termine'),
            TextField::new('jeu'),
            TextField::new('lieu'),
            TextField::new('sexe'),
            TextField::new('ball'),
            AssociationField::new(propertyName: 'user'),
        ];
    }
}