<?php

namespace App\Controller\Admin;

use App\Entity\Training;
use App\Service\WebpImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class TrainingCrudController extends AbstractCrudController
{
    public function __construct(private readonly WebpImageUploader $webpImageUploader)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Training::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('formation')
            ->setEntityLabelInPlural('formations')
            ->setSearchFields(['schoolName', 'program', 'summary'])
            ->setDefaultSort(['sortOrder' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('schoolName', 'Établissement');
        yield TextField::new('program', 'Intitulé');
        yield TextField::new('period', 'Période');
        yield TextField::new('theme', 'Thème CSS')->hideOnIndex();
        yield ImageField::new('imagePath', 'Aperçu')
            ->setBasePath('/')
            ->onlyOnIndex();
        yield Field::new('imageFile', 'Image (jpg, jpeg, png, webp)')
            ->setFormType(FileType::class)
            ->setFormTypeOptions([
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new File(
                        maxSize: '6M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Formats autorisés: JPG, JPEG, PNG, WEBP.'
                    ),
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.webp',
                ],
            ])
            ->onlyOnForms();
        yield TextField::new('imagePath', 'Chemin public')
            ->setFormTypeOption('disabled', true)
            ->onlyOnForms();
        yield TextEditorField::new('summary', 'Résumé')->hideOnIndex();
        yield IntegerField::new('sortOrder', 'Ordre');
        yield BooleanField::new('isPublished', 'Publié');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Training) {
            $this->handleImageUpload($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Training) {
            $this->handleImageUpload($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handleImageUpload(Training $training): void
    {
        $imageFile = $training->getImageFile();
        if (!$imageFile instanceof UploadedFile) {
            return;
        }

        $relativePath = $this->webpImageUploader->upload(
            $imageFile,
            'trainings',
            $training->getImagePath(),
            $training->getSchoolName()
        );

        $training->setImagePath($relativePath);
        $training->setImageFile(null);
    }
}
