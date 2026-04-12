<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TestimonialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('témoignage')
            ->setEntityLabelInPlural('témoignages')
            ->setSearchFields(['authorName', 'authorRole', 'companyName', 'content'])
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('authorName', 'Auteur');
        yield TextField::new('authorRole', 'Poste / Contexte')->hideOnIndex();
        yield TextField::new('companyName', 'Entreprise');
        yield TextareaField::new('content', 'Témoignage');
        yield TextField::new('theme', 'Thème CSS')->hideOnIndex();
        yield IntegerField::new('sortOrder', 'Ordre');
        yield BooleanField::new('isPublished', 'Publié');
    }
}
