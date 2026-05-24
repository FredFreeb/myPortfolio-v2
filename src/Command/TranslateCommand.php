<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Translates missing YAML translation keys from FR to a target locale via DeepL.
 *
 * Requires:
 *   composer require symfony/http-client
 *   DEEPL_API_KEY=your-free-key in .env.local  (free key at https://www.deepl.com/pro#developer)
 *
 * Usage:
 *   php bin/console app:translate en           (dry-run)
 *   php bin/console app:translate en --write   (writes translations/messages.en.yaml)
 *   php bin/console app:translate en --all     (retranslates every key, not just missing ones)
 */
#[AsCommand(name: 'app:translate', description: 'Translate missing YAML keys from FR to a target locale via DeepL.')]
final class TranslateCommand extends Command
{
    private const DEEPL_FREE_URL  = 'https://api-free.deepl.com/v2/translate';
    private const DEEPL_PRO_URL   = 'https://api.deepl.com/v2/translate';
    private const BATCH_SIZE      = 50;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        #[Autowire('%env(default::DEEPL_API_KEY)%')]
        private readonly string $deeplApiKey,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('locale', InputArgument::REQUIRED, 'Target locale (e.g. en, de, es)')
            ->addOption('write', null, InputOption::VALUE_NONE, 'Write the result to messages.<locale>.yaml')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Retranslate all keys, not just missing ones');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $locale = strtolower((string) $input->getArgument('locale'));
        $write  = (bool) $input->getOption('write');
        $all    = (bool) $input->getOption('all');

        if ($this->deeplApiKey === '') {
            $io->error('DEEPL_API_KEY is not set. Add it to .env.local — free keys at https://www.deepl.com/pro#developer');
            return Command::FAILURE;
        }

        $dir    = $this->projectDir . '/translations';
        $source = $dir . '/messages.fr.yaml';
        $target = $dir . '/messages.' . $locale . '.yaml';

        if (!is_file($source)) {
            $io->error('Source file not found: ' . $source);
            return Command::FAILURE;
        }

        /** @var array<string, mixed> $sourceData */
        $sourceData = Yaml::parseFile($source) ?? [];
        /** @var array<string, mixed> $targetData */
        $targetData = is_file($target) ? (Yaml::parseFile($target) ?? []) : [];

        $flatSource = $this->flatten($sourceData);
        $flatTarget = $this->flatten($targetData);

        $toTranslate = $all
            ? $flatSource
            : array_diff_key($flatSource, $flatTarget);

        if (count($toTranslate) === 0) {
            $io->success('Nothing to translate — target file is already complete.');
            return Command::SUCCESS;
        }

        $io->title(sprintf(
            'Translating %d key(s) from FR → %s%s',
            count($toTranslate),
            strtoupper($locale),
            $write ? '' : ' [dry-run]'
        ));

        $translated = $this->translateBatch(array_values($toTranslate), strtoupper($locale), $io);

        if ($translated === null) {
            return Command::FAILURE;
        }

        $keys  = array_keys($toTranslate);
        $rows  = [];
        $merge = $flatTarget;

        foreach ($keys as $i => $key) {
            $value       = $translated[$i] ?? '';
            $merge[$key] = $value;
            $rows[]      = [$key, mb_strimwidth($flatSource[$key], 0, 40, '…'), mb_strimwidth($value, 0, 40, '…')];
        }

        $io->table(['Key', 'FR source', strtoupper($locale) . ' translation'], $rows);

        if (!$write) {
            $io->note('Dry-run. Add --write to save ' . basename($target));
            return Command::SUCCESS;
        }

        $nested = $this->unflatten($merge);
        file_put_contents($target, Yaml::dump($nested, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));

        $io->success(sprintf('%d key(s) written to %s', count($keys), $target));
        return Command::SUCCESS;
    }

    /**
     * @param string[] $texts
     * @return string[]|null  null on API error
     */
    private function translateBatch(array $texts, string $targetLang, SymfonyStyle $io): ?array
    {
        $results  = [];
        $batches  = array_chunk($texts, self::BATCH_SIZE);
        $endpoint = str_ends_with($this->deeplApiKey, ':fx') ? self::DEEPL_FREE_URL : self::DEEPL_PRO_URL;

        foreach ($batches as $batch) {
            $io->writeln(sprintf(' → Calling DeepL for %d text(s)…', count($batch)));

            try {
                $response = $this->httpClient->request('POST', $endpoint, [
                    'headers' => [
                        'Authorization' => 'DeepL-Auth-Key ' . $this->deeplApiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'text'        => $batch,
                        'source_lang' => 'FR',
                        'target_lang' => $targetLang,
                        'tag_handling' => 'xml',
                        'ignore_tags'  => ['x'],
                    ],
                ]);

                $data = $response->toArray();
            } catch (\Throwable $e) {
                $io->error('DeepL API error: ' . $e->getMessage());
                return null;
            }

            foreach ($data['translations'] ?? [] as $t) {
                $results[] = $t['text'] ?? '';
            }
        }

        return $results;
    }

    /**
     * Flattens ['a' => ['b' => 'v']] → ['a.b' => 'v']
     * @param array<string, mixed> $array
     * @return array<string, string>
     */
    private function flatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $fullKey = $prefix === '' ? (string) $key : $prefix . '.' . $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $fullKey));
            } else {
                $result[$fullKey] = (string) $value;
            }
        }
        return $result;
    }

    /**
     * Unflattens ['a.b' => 'v'] → ['a' => ['b' => 'v']]
     * @param array<string, string> $flat
     * @return array<string, mixed>
     */
    private function unflatten(array $flat): array
    {
        $result = [];
        foreach ($flat as $dotKey => $value) {
            $keys    = explode('.', $dotKey);
            $current = &$result;
            foreach ($keys as $k) {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
            $current = $value;
            unset($current);
        }
        return $result;
    }
}
