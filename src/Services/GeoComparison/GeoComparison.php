<?php

namespace Vng\EvaCore\Services\GeoComparison;

use Vng\EvaCore\Services\GeoData\BasicGeoModel;
use Vng\EvaCore\Services\GeoData\BasicRegionModel;
use Vng\EvaCore\Services\GeoData\BasicTownshipModel;
use Exception;

abstract class GeoComparison
{
    const STATUS_MISSING = 'missing item';
    const COMPARABLE_ATTRIBUTES = [
        'name',
        'slug',
    ];

    protected ?BasicGeoModel $modelA;
    protected ?BasicGeoModel $modelB;
    private array $compareAttributes;

    public function __construct(BasicGeoModel $modelA = null, BasicGeoModel $modelB = null, array $attributes = null)
    {
        $this->modelA = $modelA;
        $this->modelB = $modelB;

        $this->compareAttributes = static::COMPARABLE_ATTRIBUTES;
        if (!is_null($attributes)) {
            $this->setCompareAttributes($attributes);
        }
    }

    public static function create(BasicGeoModel $modelA = null, BasicGeoModel $modelB = null, array $attributes = null)
    {
        return new static($modelA, $modelB, $attributes);
    }

    /**
     * @return BasicGeoModel | BasicTownshipModel | BasicRegionModel | null
     */
    public function getModelA()
    {
        return $this->modelA;
    }

    /**
     * @return BasicGeoModel | BasicTownshipModel | BasicRegionModel | null
     */
    public function getModelB()
    {
        return $this->modelB;
    }

    public function getCompareAttributes(): array
    {
        return $this->compareAttributes;
    }

    public function setCompareAttributes(array $attributes): self
    {
        $this->compareAttributes = [];
        foreach ($attributes as $attribute) {
            $this->addCompareAttribute($attribute);
        }
        return $this;
    }

    public function addCompareAttribute(string $attribute): self
    {
        if (!in_array($attribute, static::COMPARABLE_ATTRIBUTES)) {
            throw new Exception('invalid attribute given');
        }
        if (!in_array($attribute, $this->compareAttributes)) {
            $this->compareAttributes[] = $attribute;
        }
        return $this;
    }

    public function removeCompareAttribute(string $attribute): self
    {
        $i = array_search($attribute, $this->compareAttributes);
        if ($i) {
            unset($this->compareAttributes[$i]);
        }
        return $this;
    }

    public function getItemsCode()
    {
        if (is_null($this->modelA) && is_null($this->modelB)) {
            return null;
        }
        if (is_null($this->modelA)) {
            return $this->modelB->getCode();
        }
        return $this->modelA->getCode();
    }

    public function hasDeviations(): bool
    {
        return count($this->getDeviations()) > 0;
    }

    public function getDeviations(): array
    {
        if (is_null($this->modelA) || is_null($this->modelB)) {
            return [
                $this::STATUS_MISSING
            ];
        }
        return array_filter(
            $this->compareAttributes,
            fn ($attribute) => $this->attributeDeviates($attribute)
        );
    }

    public function attributeDeviates(string $attribute): bool
    {
        if (is_null($this->modelA) || is_null($this->modelB)) {
            return true;
        }
        return $this->modelA->$attribute !== $this->modelB->$attribute;
    }

    public function getAttributeComparison($attribute)
    {
        if ($attribute === static::STATUS_MISSING) {
            return [
                $this->getItemsCode(),
                null,
                is_null($this->modelA) ? 'MISSING' : $this->modelA->getName(),
                is_null($this->modelB) ? 'MISSING' : $this->modelB->getName(),
            ];
        }

        return [
            $this->getItemsCode(),
            $attribute,
            $this->modelA->$attribute,
            $this->modelB->$attribute
        ];
    }
}
