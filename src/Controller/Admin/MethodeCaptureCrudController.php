<?php

namespace App\Controller\Admin;

use App\Entity\MethodeCapture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MethodeCaptureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MethodeCapture::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Méthodes de capture')
            ->setEntityLabelInSingular('méthode');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom_methode'),
        ];
    }
}