<?php

namespace App\Controller\Admin;

use App\Entity\Translation;
use App\Repository\TranslationRepository;
use App\Translation\DatabaseAwareTranslator;
use App\Translation\LocaleAvailability;
use App\Translation\LocaleCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Administration des traductions multilingues.
 *
 * Trois vues :
 *   - /chezmoi/espace/traductions              → liste des 24 langues + progression
 *   - /chezmoi/espace/traductions/{locale}     → édition en masse pour une langue
 *   - /chezmoi/espace/traductions/cle/nouvelle → création d'une nouvelle clé + FR
 */
#[Route('/chezmoi/espace/traductions', name: 'admin_translations_')]
#[IsGranted('ROLE_ADMIN')]
class TranslationAdminController extends AbstractController
{
    public function __construct(
        private readonly TranslationRepository $repo,
        private readonly LocaleAvailability $availability,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $locales  = LocaleCatalog::supportedLocales();
        $stats    = $this->repo->getCompletionStats($locales);
        $available = $this->availability->availableLocales();

        return $this->render('admin/translation/index.html.twig', [
            'locales'   => $locales,
            'names'     => LocaleCatalog::LOCALES,
            'stats'     => $stats,
            'available' => $available,
            'threshold' => LocaleCatalog::AVAILABILITY_THRESHOLD,
            'total'     => $stats['fr']['total'] ?? 0,
        ]);
    }

    #[Route('/cle/nouvelle', name: 'new_key', methods: ['GET', 'POST'])]
    public function newKey(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $key     = trim((string) $request->request->get('translation_key', ''));
            $section = trim((string) $request->request->get('section', '')) ?: null;
            $content = trim((string) $request->request->get('content_fr', ''));
            $notes   = trim((string) $request->request->get('notes', '')) ?: null;

            if ($key === '' || $content === '') {
                $this->addFlash('warning', 'La clé et le contenu français sont obligatoires.');
                return $this->redirectToRoute('admin_translations_new_key');
            }
            if ($this->repo->findOneByKey($key)) {
                $this->addFlash('warning', \sprintf('La clé « %s » existe déjà.', $key));
                return $this->redirectToRoute('admin_translations_new_key');
            }

            $t = (new Translation())
                ->setTranslationKey($key)
                ->setSection($section)
                ->setNotes($notes)
                ->setContent('fr', $content);

            $this->repo->save($t);
            $this->invalidateCaches();
            $this->addFlash('success', \sprintf('Clé « %s » créée.', $key));
            return $this->redirectToRoute('admin_translations_index');
        }

        return $this->render('admin/translation/new.html.twig');
    }

    #[Route('/{locale}', name: 'edit', requirements: ['locale' => '[a-z]{2}'], methods: ['GET', 'POST'])]
    public function edit(string $locale, Request $request): Response
    {
        if (!LocaleCatalog::isSupported($locale)) {
            throw $this->createNotFoundException(\sprintf('Locale "%s" non supporté.', $locale));
        }

        if ($request->isMethod('POST')) {
            return $this->handleSave($locale, $request);
        }

        $grouped = $this->repo->findAllGroupedBySection();
        $stats   = $this->repo->getCompletionStats([$locale])[$locale];

        return $this->render('admin/translation/edit.html.twig', [
            'locale'      => $locale,
            'localeName'  => LocaleCatalog::getName($locale),
            'isReference' => $locale === Translation::REFERENCE_LOCALE,
            'grouped'     => $grouped,
            'stats'       => $stats,
        ]);
    }

    #[Route('/{locale}/cle/{id}/supprimer', name: 'delete_key', requirements: ['locale' => '[a-z]{2}', 'id' => '\d+'], methods: ['POST'])]
    public function deleteKey(string $locale, int $id, Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('tr_delete_' . $id, (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_translations_edit', ['locale' => $locale]);
        }
        $t = $this->repo->find($id);
        if ($t) {
            $key = $t->getTranslationKey();
            $this->repo->remove($t);
            $this->invalidateCaches();
            $this->addFlash('success', \sprintf('Clé « %s » supprimée.', $key));
        }
        return $this->redirectToRoute('admin_translations_edit', ['locale' => $locale]);
    }

    private function handleSave(string $locale, Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('tr_edit_' . $locale, (string) $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_translations_edit', ['locale' => $locale]);
        }

        /** @var array<int, string> $values  id → contenu */
        $values = $request->request->all('tr');
        $updated = 0;

        foreach ($values as $id => $content) {
            $t = $this->repo->find((int) $id);
            if (!$t) continue;
            $old = $t->getContents()[$locale] ?? null;
            $new = is_string($content) ? trim($content) : '';
            if ($new === $old || ($new === '' && $old === null)) {
                continue;
            }
            $t->setContent($locale, $new !== '' ? $new : null);
            $this->repo->save($t, false);
            $updated++;
        }

        if ($updated > 0) {
            $this->repo->getEntityManager()->flush();
            $this->invalidateCaches();
            $this->addFlash('success', \sprintf('%d traduction(s) mise(s) à jour pour %s.', $updated, LocaleCatalog::getName($locale)));
        } else {
            $this->addFlash('info', 'Aucun changement détecté.');
        }
        return $this->redirectToRoute('admin_translations_edit', ['locale' => $locale]);
    }

    private function invalidateCaches(): void
    {
        if ($this->translator instanceof DatabaseAwareTranslator) {
            $this->translator->invalidateCache();
        }
        $this->availability->invalidate();
    }
}
