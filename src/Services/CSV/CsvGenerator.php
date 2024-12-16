<?php

namespace Vng\EvaCore\Services\CSV;

class CsvGenerator
{
    protected array $data = [];
    protected array $headers = [];

    /**
     * Stel de headers van de CSV in.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Voeg rijen toe aan de CSV.
     *
     * @param array $rows
     * @return $this
     */
    public function addRows(array $rows): self
    {
        $this->data = array_merge($this->data, $rows);
        return $this;
    }

    /**
     * Genereer de CSV-inhoud als string.
     *
     * @return string
     */
    public function generate(): string
    {
        $output = '';

        // Voeg de headers toe, indien ingesteld
        if (!empty($this->headers)) {
            $output .= $this->arrayToCsvRow($this->headers);
        }

        // Voeg de data toe
        foreach ($this->data as $row) {
            $output .= $this->arrayToCsvRow($row);
        }

        return $output;
    }

    /**
     * Converteer een array naar een CSV-rij.
     *
     * @param array $row
     * @return string
     */
    protected function arrayToCsvRow(array $row): string
    {
        return implode(',', array_map(fn($value) => $this->escapeValue($value), $row)) . "\n";
    }

    /**
     * Escape speciale waarden voor CSV.
     *
     * @param mixed $value
     * @return string
     */
    protected function escapeValue(mixed $value): string
    {
        if (str_contains($value, ',') || str_contains($value, '"') || str_contains($value, "\n")) {
            $value = '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }
}
