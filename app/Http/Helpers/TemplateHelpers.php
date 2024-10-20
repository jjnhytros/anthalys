<?php

namespace App\Http\Helpers;

use App\Models\Anthaleja\Wiki\WikiTemplate;
use App\Models\Anthaleja\Wiki\WikiInfoboxTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TemplateHelpers
{
    /**
     * Analizza i template e gli infobox nel contenuto e li sostituisce con dei placeholder.
     *
     * @param string $content Il contenuto da analizzare
     * @return array Ritorna il contenuto modificato con i match dei template e degli infobox
     */
    public static function parseTemplatesWithPlaceholders($content)
    {
        // Cerca i tag template e infobox nel contenuto
        preg_match_all('/\{\{\s*template:(.*?)\s*\}\}/', $content, $templateMatches);
        preg_match_all('/\{\{\s*infobox_(.*?)\s*\[(.*?)\]\s*\}\}/s', $content, $infoboxMatches);

        // Sostituzione dei tag template con placeholder
        foreach ($templateMatches[0] as $index => $match) {
            $placeholder = '###TEMPLATE_PLACEHOLDER_' . $index . '###';
            $content = str_replace($match, $placeholder, $content);
        }

        // Sostituzione dei tag infobox con placeholder
        foreach ($infoboxMatches[0] as $index => $match) {
            $placeholder = '###INFOBOX_PLACEHOLDER_' . $index . '###';
            $content = str_replace($match, $placeholder, $content);
        }

        return [
            'content' => $content,
            'templateMatches' => $templateMatches,
            'infoboxMatches' => $infoboxMatches
        ];
    }

    /**
     * Ripristina i placeholder di template e infobox con i loro rispettivi contenuti HTML.
     *
     * @param string $content Il contenuto con placeholder
     * @param array $templateMatches I template trovati nel contenuto
     * @param array $infoboxMatches Gli infobox trovati nel contenuto
     * @return string Contenuto finale con template e infobox ripristinati
     */
    public static function restorePlaceholders($content, $templateMatches, $infoboxMatches)
    {
        // Sostituzione dei placeholder dei template con il loro HTML
        foreach ($templateMatches[1] as $index => $templateName) {
            $templateHTML = self::getTemplateHTML($templateName);
            $placeholder = '###TEMPLATE_PLACEHOLDER_' . $index . '###';
            $content = str_replace($placeholder, $templateHTML, $content);
        }

        // Sostituzione dei placeholder degli infobox con il loro HTML
        foreach ($infoboxMatches[2] as $index => $infoboxData) {
            $category = $infoboxMatches[1][$index];
            $parsedData = self::parseInfoboxData($infoboxData);
            if ($parsedData) {
                $infoboxHTML = self::renderInfobox($category, $parsedData);
                $placeholder = '###INFOBOX_PLACEHOLDER_' . $index . '###';
                $content = str_replace($placeholder, $infoboxHTML, $content);
            }
        }

        return $content;
    }

    /**
     * Elabora i template nel contenuto e aggiunge un messaggio per i template mancanti.
     *
     * @param string $content Il contenuto da analizzare
     * @return string Il contenuto con i template elaborati e, eventualmente, un messaggio per i template mancanti
     */
    public static function processTemplatesInContent($content)
    {
        // Trova tutti i tag {{ template:nometemplate }} nel contenuto
        preg_match_all('/{{\s*template:(\w+)\s*}}/', $content, $matches);

        // Recupera tutti i nomi dei template trovati
        $templatesInContent = $matches[1];
        $missingTemplates = [];

        // Controlla nel database se ogni template esiste
        foreach ($templatesInContent as $templateName) {
            $templateExists = WikiTemplate::where('title', $templateName)->exists();

            // Se il template non esiste, aggiungilo alla lista dei template mancanti
            if (!$templateExists) {
                $missingTemplates[] = $templateName;
            }
        }

        // Se ci sono template mancanti, aggiungi il messaggio alla fine del contenuto
        if (!empty($missingTemplates)) {
            $content .= "<h3>Templates used in this article:</h3><ul>";
            foreach ($missingTemplates as $templateName) {
                $content .= "<li>$templateName - <a href='/templates/create?name=$templateName'>Create this template</a></li>";
            }
            $content .= "</ul>";
        }

        return $content;
    }

    /**
     * Analizza il contenuto, rimuove i blocchi preservati e sostituisce i template con HTML.
     *
     * @param string $content Il contenuto da elaborare
     * @return string Contenuto finale con i template e infobox elaborati
     */
    public static function parseTemplates($content)
    {
        // Trova il contenuto racchiuso nei tag <noremove> e sostituiscilo con placeholder temporanei
        $preservePattern = '/\$\$\$\$(.*?)\$\$\$\$/s';
        $preservePlaceholders = [];
        preg_match_all($preservePattern, $content, $matches);

        foreach ($matches[0] as $index => $fullMatch) {
            $placeholder = '###PRESERVE_' . $index . '###';
            $preservePlaceholders[$placeholder] = htmlspecialchars($matches[1][$index], ENT_NOQUOTES);
            $content = str_replace($fullMatch, $placeholder, $content);
        }

        // Elabora i template generici
        preg_match_all('/\{\{\s*template:(.*?)\s*\}\}/', $content, $matches);

        foreach ($matches[1] as $index => $templateName) {
            $templateHTML = self::getTemplateHTML($templateName);
            $content = str_replace($matches[0][$index], $templateHTML, $content);
        }

        // Elabora gli infobox
        preg_match_all('/\{\{\s*infobox_(.*?)\s*\[(.*?)\]\s*\}\}/s', $content, $infoboxMatches);

        foreach ($infoboxMatches[2] as $index => $infoboxData) {
            $category = $infoboxMatches[1][$index];
            $parsedData = self::parseInfoboxData($infoboxData);

            if ($parsedData) {
                $infoboxHTML = self::renderInfobox($category, $parsedData);
                $content = str_replace($infoboxMatches[0][$index], $infoboxHTML, $content);
            }
        }

        // Ripristina i blocchi preservati
        foreach ($preservePlaceholders as $placeholder => $originalContent) {
            $content = str_replace($placeholder, $originalContent, $content);
        }

        return $content;
    }

    /**
     * Analizza il contenuto per trovare gli infobox e li sostituisce con placeholder.
     *
     * @param string $content Il contenuto da elaborare
     * @return array Contenuto con infobox placeholder e infobox elaborati
     */
    public static function parseInfobox($content)
    {
        preg_match_all('/\{\{\s*infobox_(\w+)\s*\|\s*(.*?)\s*\}\}/s', $content, $infoboxMatches);
        foreach ($infoboxMatches[0] as $index => $match) {
            $placeholder = '###INFOBOX_PLACEHOLDER_' . $index . '###';
            $infoboxType = $infoboxMatches[1][$index];
            $infoboxData = self::parseInfoboxData($infoboxMatches[2][$index]);

            $content = str_replace($match, $placeholder, $content);

            $parsedInfoboxes[$placeholder] = [
                'type' => $infoboxType,
                'data' => $infoboxData,
            ];
        }

        return [
            'content' => $content,
            'infoboxes' => $parsedInfoboxes ?? [],
        ];
    }

    /**
     * Estrae informazioni sui template dal contenuto fornito.
     *
     * @param string $content Contenuto da analizzare
     * @return array Un array di template estratti
     */
    public static function getTemplatesInfo($content)
    {
        $templates = []; // Array per memorizzare i template trovati

        // Esegui il parsing per trovare i template nel contenuto
        preg_match_all('/{{\s*infobox_(\w+)\s*(.*?)\s*}}/s', $content, $matches);

        // Estrai i template
        foreach ($matches[1] as $index => $type) {
            $data = trim($matches[2][$index]);

            // Trasforma i dati in un array associativo
            $templateData = [];
            try {
                $templateData = json_decode($data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Errore di parsing JSON: ' . json_last_error_msg());
                }
            } catch (\Exception $e) {
                dd('Errore durante il parsing dei dati del template: ' . $e->getMessage());
            }

            $templates[] = [
                'type' => $type,
                'content' => $templateData,
                'slug' => Str::slug($templateData['title'] ?? 'slug-not-available')
            ];
        }

        return $templates;
    }

    /**
     * Parsifica i dati dell'infobox e li restituisce come array.
     *
     * @param string $infoboxString Stringa dell'infobox
     * @return array Array associativo con i dati dell'infobox
     */
    private static function parseInfoboxData($infoboxString)
    {
        $lines = explode('|', $infoboxString);
        $infoboxData = ['attributes' => []];

        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                if (trim($key) === 'title') {
                    $infoboxData['title'] = trim($value);
                } else {
                    $infoboxData['attributes'][trim($key)] = trim($value);
                }
            }
        }

        return $infoboxData;
    }

    /**
     * Renderizza l'infobox sostituendo i placeholder con i dati effettivi.
     *
     * @param string $type Tipo di infobox
     * @param array $data Dati dell'infobox
     * @return string HTML renderizzato per l'infobox
     */
    public static function renderInfobox($type, $data)
    {
        try {
            $template = WikiInfoboxTemplate::getByType($type);

            if (!$template) {
                return '<div class="alert alert-warning">Infobox template not found for type: ' . htmlspecialchars($type) . '</div>';
            }

            $templateContent = $template->content;

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            if (!is_array($data)) {
                return '<div class="alert alert-danger">Invalid infobox data</div>';
            }

            // Campi opzionali
            $optionalFields = $template->optional_fields;

            // Elabora il template, sostituendo solo i campi presenti
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $templateContent = str_replace('{{ ' . $key . ' }}', htmlspecialchars($value), $templateContent);
                } else {
                    if (in_array($key, $optionalFields)) {
                        $templateContent = str_replace('{{ ' . $key . ' }}', '', $templateContent); // Rimuovi campi opzionali vuoti
                    } else {
                        $templateContent = str_replace('{{ ' . $key . ' }}', 'N/A', $templateContent); // Mostra N/A per campi non opzionali vuoti
                    }
                }
            }

            // Rimuove i placeholder rimanenti non popolati
            $templateContent = preg_replace('/\{\{\s*[^}]+\s*\}\}/', '', $templateContent);

            return $templateContent;
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">Error rendering infobox</div>';
        }
    }

    /**
     * Recupera l'HTML di un template in base al nome.
     *
     * @param string $templateName Nome del template
     * @return string HTML del template
     */
    private static function getTemplateHTML($templateName)
    {
        $template = WikiTemplate::where('title', $templateName)->first();

        return $template ? $template->html_content : "<div>Template: $templateName not found</div>";
    }
}
