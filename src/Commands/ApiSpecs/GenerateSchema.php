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

        $specsPath = resource_path('openapi/');
        $schemaFilePath = $specsPath . 'schemas.yml';

        if (!file_exists($schemaFilePath)) {
            $this->warn('Schema file does not exist');
            return 1;
        }

        // Laad de hoofd YAML-bestand
        $schema = Yaml::parseFile($schemaFilePath);

        // Vervang alle $ref verwijzingen
        $schema = $this->dereferenceSchema($schema, $specsPath);

        // Sla de aangepaste schema op in een nieuw bestand
        $newSchemaPath = $specsPath . 'merged_schema.yml';
        file_put_contents($newSchemaPath, Yaml::dump($schema, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK));

        $this->info("Schema generated at '{$newSchemaPath}'.");

        return 0;
    }

    protected function dereferenceSchema(array $schema, string $basePath): array
    {
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

    protected function extractEntityNameFromPath(string $path): ?string
    {
        $filename = basename($path, '.yml');

        // Converteer de bestandsnaam naar CamelCase als standaard voor entiteitsnamen
        $entityName = str_replace(' ', '', ucwords(str_replace('-', ' ', $filename)));

        return $entityName ?: null;
    }
}
