<?php

namespace App\Controller\Admin;

use App\Entity\Amis;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AmisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Amis::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural("Liens d'amitié")
            ->setEntityLabelInSingular("lien d'amitié");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('userDemande'),
            AssociationField::new('userRecoit'),
            BooleanField::new('statut'),
            DateField::new('date_demande'),
        ];
    }
}