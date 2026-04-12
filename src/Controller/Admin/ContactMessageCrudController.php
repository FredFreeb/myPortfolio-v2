<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Form\AdminContactReplyType;
use App\Form\Model\AdminContactReply;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class ContactMessageCrudController extends AbstractCrudController
{
    private const REPLY_FROM_EMAIL = 'frederic.fribel@gmail.com';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return ContactMessage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('message')
            ->setEntityLabelInPlural('messages')
            ->setDefaultSort(['isAnswered' => 'ASC', 'isRead' => 'ASC', 'createdAt' => 'DESC'])
            ->setPaginatorPageSize(25)
            ->setSearchFields(['name', 'email', 'company', 'subject', 'message']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $replyAction = Action::new('reply', 'Répondre', 'fa fa-reply')
            ->linkToCrudAction('reply')
            ->setCssClass('btn btn-primary')
            ->displayIf(static fn (ContactMessage $message): bool => '' !== trim($message->getEmail()));

        return $actions
            ->disable(Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $replyAction)
            ->add(Crud::PAGE_DETAIL, $replyAction);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isRead', 'Lu'))
            ->add(BooleanFilter::new('isAnswered', 'Répondu'))
            ->add(DateTimeFilter::new('createdAt', 'Reçu le'))
            ->add(DateTimeFilter::new('repliedAt', 'Répondu le'))
            ->add(TextFilter::new('email', 'E-mail'))
            ->add(TextFilter::new('subject', 'Objet'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield DateTimeField::new('createdAt', 'Reçu le')->hideOnForm();
        yield DateTimeField::new('repliedAt', 'Répondu le')->hideOnForm();
        yield TextField::new('name', 'Nom')->setFormTypeOption('disabled', true);
        yield EmailField::new('email', 'E-mail')->setFormTypeOption('disabled', true);
        yield TextField::new('company', 'Organisation')->setFormTypeOption('disabled', true);
        yield TextField::new('subject', 'Objet')->setFormTypeOption('disabled', true);
        yield TextEditorField::new('message', 'Message')
            ->setFormTypeOption('disabled', true)
            ->hideOnIndex();
        yield BooleanField::new('isRead', 'Lu')->hideOnForm();
        yield BooleanField::new('isAnswered', 'Répondu')->hideOnForm();
        yield TextField::new('replySubject', 'Objet de la dernière réponse')
            ->setFormTypeOption('disabled', true)
            ->hideOnIndex();
        yield TextEditorField::new('replyMessage', 'Dernière réponse envoyée')
            ->setFormTypeOption('disabled', true)
            ->hideOnIndex();
    }

    #[AdminRoute(
        path: '/{entityId}/reply',
        name: 'reply',
        options: ['methods' => ['GET', 'POST']]
    )]
    public function reply(AdminContext $context): Response
    {
        $entity = $context->getEntity()->getInstance();
        if (!$entity instanceof ContactMessage) {
            throw $this->createNotFoundException('Message de contact introuvable.');
        }

        $replyFormData = new AdminContactReply(
            $entity->getReplySubject() ?? sprintf('Re: %s', $entity->getSubject()),
            $entity->getReplyMessage() ?? $this->buildDefaultReplyBody($entity)
        );

        $form = $this->createForm(AdminContactReplyType::class, $replyFormData);
        $form->handleRequest($context->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $sentAt = new \DateTimeImmutable();

            $mail = (new TemplatedEmail())
                ->to($entity->getEmail())
                ->from(self::REPLY_FROM_EMAIL)
                ->replyTo(self::REPLY_FROM_EMAIL)
                ->subject($replyFormData->getSubject())
                ->htmlTemplate('email/contact_reply.html.twig')
                ->context([
                    'contactName' => $entity->getName(),
                    'companyName' => $entity->getCompany(),
                    'replySubject' => $replyFormData->getSubject(),
                    'replyBody' => $replyFormData->getBody(),
                    'originalSubject' => $entity->getSubject(),
                    'originalBody' => $entity->getMessage(),
                    'sentAt' => $sentAt,
                ]);

            try {
                $this->mailer->send($mail);
            } catch (TransportExceptionInterface $exception) {
                $this->addFlash('danger', sprintf('Impossible d’envoyer la réponse: %s', $exception->getMessage()));

                return $this->redirect($this->getReplyUrl($entity));
            }

            $entity
                ->setReplySubject($replyFormData->getSubject())
                ->setReplyMessage($replyFormData->getBody())
                ->setIsAnswered(true)
                ->setIsRead(true)
                ->setRepliedAt($sentAt);

            $this->entityManager->flush();

            $this->addFlash('success', sprintf('Réponse envoyée à %s.', $entity->getEmail()));

            return $this->redirect($this->getDetailUrl($entity));
        }

        return $this->render('admin/contact_reply.html.twig', [
            'contactMessage' => $entity,
            'replyForm' => $form,
            'backToDetailUrl' => $this->getDetailUrl($entity),
            'backToIndexUrl' => $this->getIndexUrl(),
        ]);
    }

    public function detail(AdminContext $context): KeyValueStore|Response
    {
        $entity = $context->getEntity()->getInstance();
        if ($entity instanceof ContactMessage && !$entity->isRead()) {
            $entity->setIsRead(true);
            $this->entityManager->flush();
        }

        return parent::detail($context);
    }

    private function buildDefaultReplyBody(ContactMessage $contactMessage): string
    {
        return sprintf(
            "Bonjour %s,\n\nMerci pour votre message.\nJ’ai bien reçu votre demande concernant « %s » et je reviens vers vous rapidement avec des éléments concrets.\n\nBien à vous,\nFrédéric Fribel",
            $contactMessage->getName(),
            $contactMessage->getSubject()
        );
    }

    private function getReplyUrl(ContactMessage $contactMessage): string
    {
        return $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('reply')
            ->setEntityId((string) $contactMessage->getId())
            ->generateUrl();
    }

    private function getDetailUrl(ContactMessage $contactMessage): string
    {
        return $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId((string) $contactMessage->getId())
            ->generateUrl();
    }

    private function getIndexUrl(): string
    {
        return $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->generateUrl();
    }
}
