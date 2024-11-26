<?php

namespace Vng\EvaCore\Commands\ApiSpecs;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class GenerateSchema extends Command
{
    protected $signature = 'api-specs:generate-schema';
    protected $description = 'Merges Schema files by dereferencing $refs';
    protected $dereferencedEntities = [];

    public function handle(): int
    {
        $this->getOutput()->writeln('Generating schema');

        $this->handleSchema('v1');
        $this->newLine(3);
        $this->handleSchema('v2');

        $this->line('Done!');
        return 0;
    }

    protected static function getSpecsPath(string $version)
    {
        return resource_path("openapi/{$version}/");
    }

    protected function handleSchema($version = 'v2', $fileName = 'schemas')
    {
        $this->newLine(1);
        $this->info("Handling schema {$version} - {$fileName}");
        $schema = $this->getSchema($version, $fileName);
        if (is_null($schema)) {
            $this->warn('Schema file does not exist');
            return;
        }

        $schema = $this->dereferenceSchema($version, $schema);

        $newFileName = 'merged_' . $fileName;
        $this->saveMergedSchema($version, $schema, $newFileName);

        $this->newLine(1);
        $this->info("Handled schema {$fileName}");
    }

    protected function getSchema(string $version, string $fileName)
    {
        $fileName = $this->completeYmlFileName($fileName);
        $schemaFilePath = self::getSpecsPath($version) . $fileName;
        if (!file_exists($schemaFilePath)) {
            return null;
        }
        return Yaml::parseFile($schemaFilePath);
    }

    protected function dereferenceSchema(string $version, array $schema): array
    {
        $this->newLine(1);
        $basePath = self::getSpecsPath($version);
        foreach ($schema as $entity => &$entityValue) {
            $this->output->writeln("Dereferencing {$entity}");
            if (is_array($entityValue) && count($entityValue) ==1 && key_exists('$ref', $entityValue)) {
                $refPath = realpath($basePath . ltrim($entityValue['$ref'], './'));
                if ($refPath && file_exists($refPath)) {
                    // Het referentiebestand bestaat, parse het en vervang de waarde
                    $entityYaml = Yaml::parseFile($refPath);
                    $entityYaml = $this->updateRefPaths($entityYaml);
                    $entityValue = $entityYaml;
                } else {
                    $this->error("Referentiebestand {$entity} niet gevonden op pad {$refPath}.");
                }
            }
        }
        unset($entityValue); // verbreek de referentie naar het laatste element
        return $schema;
    }

    protected function updateRefPaths(array $schema): array
    {
        foreach ($schema as $key => &$value) {
            if (is_array($value)) {
                $value = $this->updateRefPaths($value);
            } elseif (is_string($value) && $key == '$ref') {
                if (preg_match('/\.yml$/', $value)) {
                    // Extract de entiteitsnaam uit het pad
                    $entityName = $this->extractEntityNameFromPath($value);

                    // Vervang de verwijzing door de nieuwe format
                    if ($entityName !== null) {
                        $value = "#/components/schemas/{$entityName}";
                    }
                }
            }
        }
        unset($value); // verbreek de referentie naar het laatste element

        return $schema;
    }

    protected function saveMergedSchema(string $version, $schema, string $newFileName)
    {
        $newFileName = $this->completeYmlFileName($newFileName);
        // Sla de aangepaste schema op in een nieuw bestand
        $newSchemaPath = self::getSpecsPath($version) . $newFileName;
        file_put_contents($newSchemaPath, Yaml::dump($schema, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));

        $this->info("Schema generated at '{$newSchemaPath}'.");
    }

    protected function extractEntityNameFromPath(string $path): ?string
    {
        $filename = basename($path, '.yml');

        // Converteer de bestandsnaam naar CamelCase als standaard voor entiteitsnamen
        $entityName = str_replace(' ', '', ucwords(str_replace('-', ' ', $filename)));

        return $entityName ?: null;
    }

    private function completeYmlFileName(string $fileName): string
    {
        $fileInfo = pathinfo($fileName);
        if (!isset($fileInfo['extension'])) {
            $fileName .= '.yml';
        }
        return $fileName;
    }
}
