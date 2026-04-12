<?php

namespace App\Controller\Admin;

use App\Entity\ProfileLink;
use App\Enum\LinkCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProfileLinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProfileLink::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('lien profil')
            ->setEntityLabelInPlural('liens profil')
            ->setSearchFields(['title', 'subtitle', 'url', 'description'])
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield ChoiceField::new('category', 'Catégorie')
            ->setChoices(LinkCategory::choices());
        yield TextField::new('title', 'Titre');
        yield TextField::new('subtitle', 'Sous-titre')->hideOnIndex();
        yield UrlField::new('url', 'Lien')
            ->setRequired(false)
            ->hideOnIndex();
        yield TextareaField::new('description', 'Description')->hideOnIndex();
        yield TextField::new('badge', 'Badge')->hideOnIndex();
        yield TextField::new('theme', 'Thème CSS')->hideOnIndex();
        yield TextField::new('year', 'Année')->hideOnIndex();
        yield IntegerField::new('sortOrder', 'Ordre');
        yield BooleanField::new('isPublished', 'Publié');
    }
}
