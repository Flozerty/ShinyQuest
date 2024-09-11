<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Dresseurs')
            ->setEntityLabelInSingular('dresseur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo')->setFormTypeOption('disabled', 'disabled'),
            ArrayField::new('roles'),
            BooleanField::new('is_verified'),
            TextField::new('email')->setFormTypeOption('disabled', 'disabled'),
            DateField::new('date_inscription')->setFormTypeOption('disabled', 'disabled'),
            ImageField::new(propertyName: 'avatar')->setBasePath('img/avatars/')->onlyOnIndex(),
            TextField::new(propertyName: 'avatar')->onlyOnForms(),
            BooleanField::new('ban'),
            AssociationField::new('captures')
        ];
    }
}