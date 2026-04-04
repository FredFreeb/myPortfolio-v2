<?php

namespace App\Controller\Admin;

use App\Entity\Work;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class WorkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Work::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('travail')
            ->setEntityLabelInPlural('travaux')
            ->setSearchFields(['title', 'clientName', 'roleLabel', 'stackSummary'])
            ->setDefaultSort(['sortOrder' => 'ASC', 'publishedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title', 'Titre');
        yield TextField::new('clientName', 'Client')->hideOnIndex();
        yield TextField::new('roleLabel', 'Rôle')->hideOnIndex();
        yield TextField::new('stackSummary', 'Stack');
        yield TextEditorField::new('excerpt', 'Résumé court')->hideOnIndex();
        yield TextEditorField::new('description', 'Description')->hideOnIndex();
        yield UrlField::new('projectUrl', 'Lien projet')->hideOnIndex();
        yield UrlField::new('repositoryUrl', 'Lien dépôt')->hideOnIndex();
        yield TextField::new('imagePath', 'Image')->hideOnIndex();
        yield BooleanField::new('isFeatured', 'Mise en avant');
        yield BooleanField::new('isPublished', 'Publié');
        yield IntegerField::new('sortOrder', 'Ordre');
        yield DateTimeField::new('publishedAt', 'Publication')->hideOnIndex();
    }
}
