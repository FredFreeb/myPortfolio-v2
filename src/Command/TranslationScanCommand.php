<?php

namespace App\Command;

use App\Entity\Translation;
use App\Repository\TranslationRepository;
use App\Translation\DatabaseAwareTranslator;
use App\Translation\LocaleAvailability;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Scan les templates Twig et les fichiers PHP à la recherche de clés de
 * traduction (`{{ 'ma.cle'|trans }}`, `$translator->trans('ma.cle')`).
 *
 * Pour chaque clé non-encore présente en BDD, crée une entrée vide que
 * l'admin pourra remplir (avec le texte FR extrait si reconnaissable).
 *
 * Usage :
 *   php bin/console app:translations:scan          (dry-run par défaut)
 *   php bin/console app:translations:scan --write  (persiste en BDD)
 */
#[AsCommand(name: 'app:translations:scan', description: 'Scanne templates & PHP pour détecter les clés de traduction manquantes.')]
final class TranslationScanCommand extends Command
{
    /** @var array<string, string> Regex utilisés pour détecter les appels */
    private const PATTERNS = [
        // {{ 'ma.cle'|trans }} ou {{ "ma.cle"|trans }}
        'twig_filter_single' => "/\\{\\{\\s*'([a-z0-9_.\\-]+)'\\s*\\|\\s*trans/",
        'twig_filter_double' => '/\\{\\{\\s*"([a-z0-9_.\\-]+)"\\s*\\|\\s*trans/',
        // {% trans %}ma.cle{% endtrans %}
        'twig_block'         => '/\\{%\\s*trans[^%]*%\\}([a-z0-9_.\\-]+)\\{%\\s*endtrans/',
        // ->trans('ma.cle') ou ->trans("ma.cle")
        'php_trans_single'   => "/->trans\\(\\s*'([a-z0-9_.\\-]+)'/",
        'php_trans_double'   => '/->trans\\(\\s*"([a-z0-9_.\\-]+)"/',
    ];

    public function __construct(
        private readonly TranslationRepository $repo,
        private readonly TranslatorInterface $translator,
        private readonly LocaleAvailability $availability,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('write', null, InputOption::VALUE_NONE, 'Persiste les clés manquantes en BDD (sinon dry-run).')
            ->addOption('path', null, InputOption::VALUE_REQUIRED, 'Chemin à scanner (relatif au projet)', 'templates,src');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $write = (bool) $input->getOption('write');
        $paths = array_map('trim', explode(',', (string) $input->getOption('path')));

        $io->title('Scan des clés de traduction');
        $io->text($write ? '<fg=yellow>Mode écriture</> : les clés manquantes seront créées.' : '<fg=gray>Mode dry-run</> : ajouter --write pour persister.');

        $foundKeys = $this->scanPaths($paths, $io);

        // Clés déjà présentes en base
        $existing = [];
        foreach ($this->repo->findAll() as $t) {
            $existing[$t->getTranslationKey()] = true;
        }

        $missing = array_diff(array_keys($foundKeys), array_keys($existing));

        $io->section(\sprintf('%d clé(s) distincte(s) trouvée(s), %d nouvelle(s)', \count($foundKeys), \count($missing)));

        if (\count($missing) === 0) {
            $io->success('Rien à ajouter. La base est à jour.');
            return Command::SUCCESS;
        }

        $rows = [];
        foreach ($missing as $key) {
            $rows[] = [$key, $foundKeys[$key][0] ?? '(inconnu)', implode(', ', $foundKeys[$key])];
        }
        $io->table(['Clé', 'Premier fichier', 'Tous les fichiers'], $rows);

        if (!$write) {
            $io->note('Dry-run. Relancez avec --write pour créer ces clés.');
            return Command::SUCCESS;
        }

        $created = 0;
        foreach ($missing as $key) {
            $t = (new Translation())
                ->setTranslationKey($key)
                ->setContent('fr', ''); // à remplir via l'admin
            $this->repo->save($t, false);
            $created++;
        }
        $this->repo->getEntityManager()->flush();

        if ($this->translator instanceof DatabaseAwareTranslator) {
            $this->translator->invalidateCache();
        }
        $this->availability->invalidate();

        $io->success(\sprintf('%d clé(s) créée(s). Rendez-vous dans /chezmoi/espace/traductions/fr pour remplir les contenus.', $created));
        return Command::SUCCESS;
    }

    /**
     * @param string[] $paths
     * @return array<string, string[]>  key → [file1, file2...]
     */
    private function scanPaths(array $paths, SymfonyStyle $io): array
    {
        $finder = new Finder();
        $finder->files();

        foreach ($paths as $p) {
            $full = $this->projectDir . '/' . $p;
            if (is_dir($full)) {
                $finder->in($full);
            }
        }
        $finder->name(['*.twig', '*.php']);

        $keys = [];
        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            if ($content === false) continue;
            foreach (self::PATTERNS as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $k) {
                        $keys[$k][] = $file->getRelativePathname();
                    }
                }
            }
        }

        foreach ($keys as $k => $files) {
            $keys[$k] = array_values(array_unique($files));
        }

        return $keys;
    }
}
