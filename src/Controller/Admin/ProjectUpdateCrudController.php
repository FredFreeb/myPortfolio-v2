<?php

namespace App\Controller\Admin;

use App\Entity\ProjectUpdate;
use App\Enum\ProjectAudience;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProjectUpdateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectUpdate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('bloc')
            ->setEntityLabelInPlural('blocs')
            ->setSearchFields(['title', 'summary', 'statusLabel', 'outcome'])
            ->setDefaultSort(['audience' => 'ASC', 'sortOrder' => 'ASC', 'publishedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield ChoiceField::new('audience', 'Audience')
            ->setChoices(ProjectAudience::choices());
        yield TextField::new('title', 'Titre');
        yield TextField::new('statusLabel', 'Statut');
        yield TextEditorField::new('summary', 'Résumé')->hideOnIndex();
        yield TextEditorField::new('body', 'Contenu')->hideOnIndex();
        yield TextField::new('outcome', 'Impact')->hideOnIndex();
        yield TextField::new('ctaLabel', 'Libellé CTA')->hideOnIndex();
        yield UrlField::new('ctaUrl', 'URL CTA')->hideOnIndex();
        yield BooleanField::new('isFeatured', 'Mise en avant');
        yield BooleanField::new('isPublished', 'Publié');
        yield IntegerField::new('sortOrder', 'Ordre');
        yield DateTimeField::new('publishedAt', 'Publication')->hideOnIndex();
    }
}
