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

        $this->handleSchema('schemas');
        $this->newLine(3);
        $this->handleSchema('schemas-old');

        $this->line('Done!');
        return 0;
    }

    protected static function getSpecsPath()
    {
        return resource_path('openapi/');
    }

    protected function handleSchema($fileName = 'schemas')
    {
        $this->newLine(1);
        $this->info("Handling schema {$fileName}");
        $schema = $this->getSchema($fileName);
        if (is_null($schema)) {
            $this->warn('Schema file does not exist');
            return;
        }

        $schema = $this->dereferenceSchema($schema);

        $newFileName = 'merged_' . $fileName;
        $this->saveMergedSchema($schema, $newFileName);

        $this->newLine(1);
        $this->info("Handled schema {$fileName}");
    }

    protected function getSchema($fileName)
    {
        $fileName = $this->completeYmlFileName($fileName);
        $schemaFilePath = self::getSpecsPath() . $fileName;
        if (!file_exists($schemaFilePath)) {
            return null;
        }
        return Yaml::parseFile($schemaFilePath);
    }

    protected function dereferenceSchema(array $schema): array
    {
        $this->newLine(1);
        $basePath = self::getSpecsPath();
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

    protected function saveMergedSchema($schema, $newFileName)
    {
        $newFileName = $this->completeYmlFileName($newFileName);
        // Sla de aangepaste schema op in een nieuw bestand
        $newSchemaPath = self::getSpecsPath() . $newFileName;
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

    private function completeYmlFileName($fileName)
    {
        $fileInfo = pathinfo($fileName);
        if (!isset($fileInfo['extension'])) {
            $fileName .= '.yml';
        }
        return $fileName;
    }
}
