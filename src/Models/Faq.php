<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use InvalidArgumentException;
use Vng\EvaCore\Casts\CleanedHtml;
use Vng\EvaCore\ElasticResources\SGR\FaqResource;

class Faq extends SearchableModel
{
    use HasFactory;

    protected static string $elasticResource = FaqResource::class;

    // Type constants
    public const TYPE_PORTAAL = 'portaal';
    public const TYPE_BEHEER = 'beheer';

    protected $fillable = [
        'question',
        'answer',
        'type',
        'category',
    ];

    protected $casts = [
        'answer' => CleanedHtml::class,
    ];

    /**
     * Set the type attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setTypeAttribute($value)
    {
        $validTypes = [self::TYPE_PORTAAL, self::TYPE_BEHEER];

        if (!in_array($value, $validTypes)) {
            throw new InvalidArgumentException("Invalid type: {$value}. Valid types are: " . implode(', ', $validTypes));
        }

        $this->attributes['type'] = $value;
    }
}
