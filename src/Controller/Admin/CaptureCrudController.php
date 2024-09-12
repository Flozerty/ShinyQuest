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
            IntegerField::new('pokedex_id')
                ->onlyOnForms(),
            TextField::new('nom_pokemon')
                ->onlyOnForms(),
            TextField::new('surnom'),
            ImageField::new(propertyName: 'img_shiny')
                ->onlyOnIndex(),
            TextField::new(propertyName: 'img_shiny')
                ->onlyOnForms(),
            IntegerField::new('nb_rencontres'),
            BooleanField::new('charme_chroma'),
            TextField::new('jeu'),
            TextField::new('lieu'),
            TextField::new('sexe')
                ->onlyOnForms(),
            TextField::new('ball')
                ->onlyOnForms(),
            BooleanField::new('termine'),
            DateField::new('date_capture'),
            AssociationField::new('methodeCapture'),
            AssociationField::new(propertyName: 'user'),
        ];
    }
}