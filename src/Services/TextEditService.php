<?php

namespace Vng\EvaCore\Services;

use DOMDocument;
use Exception;

class TextEditService
{
    const STYLE_TAGS = ['b', 'i', 'strong', 'em', 'a'];
    const STRUCTURE_TAGS = ['p', 'br', 'ul', 'ol', 'li'];

    public static function transformToPlainText(string $html): string
    {
        $html = static::manageHtmlEntities($html);
        $html = static::removeLists($html);
        return static::removeHtml($html);
    }

    public static function transformToTextHtml(string $html): string
    {
        $html = static::manageHtmlEntities($html);
        $html = static::removeLists($html);
        return static::removeStructureHtml($html);
    }

    public static function cleanHtml(string $html): string
    {
        $html = static::manageHtmlEntities($html);
//        $html = static::replaceEmptyParagraphsWithBreak($html);
//        $html = static::removeTripleBreaks($html);

        // connect adjacent lists?
        // remove p tags from list items?

        $html = static::removeEmptyLists($html);
        return static::removeUnallowedTags($html);
    }

    public static function hasOnlyEmptyParagraphs(string $html): bool
    {
        // Match paragraphs that are either empty or contain only whitespaces and/or &nbsp;
        $pattern = '/^(?:<p(?:\s?[^>]*)*>(?:\s|&nbsp;)*<\/p>)*$/i';

        return preg_match($pattern, $html) === 1;
    }

    public static function splitOnParagraphs(string $html): array
    {
        $html = static::removeEmptyParagraphs($html);
        // remove closing tags
        $html = str_replace('</p>', '', $html);
        // split on open tags
        $paragraphs = preg_split('#<p([^>])*>#', $html);
        return array_filter($paragraphs, fn($p) => !empty($p));
    }

    public static function extractLists(string $html): array
    {
        $html = static::removeEmptyLists($html);
        $html = preg_replace("/&(?!\S+;)/", "&amp;", $html);
        $dom = new DOMDocument;

        try {
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $domLists = $dom->getElementsByTagName('ul');
        } catch (Exception $e) {
            return [];
        }

        $resultLists = [];
        foreach ($domLists as $list) {
            $items = [];
            foreach($list->childNodes as $listItem) {
                $value = $listItem->nodeValue;
                $value = static::transformToTextHtml($value);
                array_push($items, $value);
            }
            $resultLists[] = $items;
        }

        return $resultLists;
    }

    private static function manageHtmlEntities(string $html): string
    {
        return str_replace("&nbsp;", ' ', $html);

//        Can't decode because this allows manual html input
//        $html = str_replace("&nbsp;", ' ', $html);
//        return html_entity_decode($html);
    }

    private static function removeLists(string $html): string
    {
        return preg_replace('/([\s\S]*)(<ul(?:\s?[^>])*>)([\s\S]*)(<\/ul>)([\s\S]*)/', '$1$5', $html);
    }

    private static function removeHtml(string $html): string
    {
        return strip_tags($html);
    }

    private static function removeStructureHtml(string $html): string
    {
        return strip_tags($html, self::STYLE_TAGS);
    }

    private static function removeUnallowedTags(string $html): string
    {
        return strip_tags($html, [
            ...self::STRUCTURE_TAGS,
            ...self::STYLE_TAGS
        ]);
    }

    private static function removeEmptyParagraphs(string $html): string
    {
        // either a attributeless paragraph like <p>
        // or a paragraph with attributes like <p attribute=''>
        // followed by some or none white spaces
        // closed by a paragraph tag
        $pattern = '/<p(?:\s?[^>])*>(?:\s|&nbsp;)*<\/p>/';
        return preg_replace($pattern, '', $html);
    }

    private static function replaceEmptyParagraphsWithBreak(string $html): string
    {
        // either a attributeless paragraph like <p>
        // or a paragraph with attributes like <p attribute=''>
        // followed by some or none white spaces
        // closed by a paragraph tag
        $pattern = '/<p(?:\s?[^>])*>(?:\s|&nbsp;)*<\/p>/';
        return preg_replace($pattern, '<br>', $html);
    }

    private static function removeTripleBreaks(string $html): string
    {
        $pattern = '/(?:<br(?:\s?\/)?>){3,}/';
        return preg_replace($pattern, '<br><br>', $html);
    }

    private static function removeEmptyLists(string $html): string
    {
        $html = static::removeEmptyListItems($html);
        $pattern = '/<ul(?:\s?[^>])*>(?:\s|&nbsp;)*<\/ul>/';
        return preg_replace($pattern, '', $html);
    }

    private static function removeEmptyListItems(string $html): string
    {
        $pattern = '/<li(?:\s?[^>])*>(?:\s|&nbsp;)*<\/li>/';
        return preg_replace($pattern, '', $html);
    }
}
