<?php

namespace App\Http\Controllers\Anthaleja\CLAIR;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\CLAIR\Source;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Anthaleja\CLAIR\Interaction;
use App\Models\Anthaleja\CLAIR\Conversation;
use App\Models\Anthaleja\CLAIR\InteractionAnalytics;

class ChatController extends Controller
{
    public function index()
    {
        $interactions = Interaction::latest()->take(20)->get();
        return view('anthaleja.clair.chat.index', compact('interactions'));
    }

    public function send(Request $request)
    {
        $startTime = microtime(true);
        $request->validate([
            'message' => 'required|string|max:1000',
            'tone' => 'required|string',
        ]);
        $language = $this->detectLanguage($request->message);
        sleep(2);
        $response = $this->generateResponseFromSources($request->message);
        $translatedResponse = $this->translateResponse($response, $language);
        $interaction = Interaction::create([
            'message' => $request->message,
            'response' => $translatedResponse,
        ]);
        $response = $this->applyTone($response, $request->tone);
        $responseTime = microtime(true) - $startTime;
        InteractionAnalytics::create([
            'interaction_id' => $interaction->id,
            'response_time' => round($responseTime * 1000), // In millisecondi
            'source_usage_count' => 1, // Esempio base
        ]);

        $interaction->update(['response' => $response]);

        return response()->json([
            'message' => $interaction->message,
            'response' => $interaction->response,
            'created_at' => $interaction->created_at->format('H:i')
        ]);
    }

    private function detectLanguage($message)
    {
        $italianWords = ['ciao', 'grazie', 'per favore', 'buongiorno', 'come', 'stai'];
        $englishWords = ['hello', 'thank you', 'please', 'good morning', 'how', 'are'];

        $italianCount = 0;
        $englishCount = 0;

        $words = explode(' ', strtolower($message));
        foreach ($words as $word) {
            if (in_array($word, $italianWords)) {
                $italianCount++;
            }
            if (in_array($word, $englishWords)) {
                $englishCount++;
            }
        }

        if ($italianCount > $englishCount) {
            return 'it';
        }

        return 'en';
    }

    private function translateResponse($response, $language)
    {
        // If the detected language is Italian, modify the response accordingly
        if ($language == 'it') {
            return "Risposta tradotta in italiano: " . $response;
        }

        return $response;
    }

    private function applyTone($response, $tone)
    {
        switch ($tone) {
            case 'informale':
                return "Ehi! " . $response;
            case 'amichevole':
                return "Ciao! " . $response;
            case 'professionale':
                return "In base alle informazioni raccolte, posso dirti che: " . $response;
            case 'formale':
            default:
                return "Secondo quanto trovato: " . $response;
        }
    }
    public function history()
    {
        $conversations = Conversation::with('interactions')->latest()->get();
        return view('anthaleja.clair.chat.history', compact('conversations'));
    }

    private function generateResponseFromSources($message)
    {
        $keywords = $this->extractKeywords($message);

        $sources = Source::where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('author', 'LIKE', "%{$keyword}%")
                    ->orWhere('type', 'LIKE', "%{$keyword}%");
            }
        })->get();

        $bestSource = $sources->first();
        if ($bestSource) {
            $description = $bestSource->description;
            if (strlen($description) > 200) {
                $description = $this->summarize($description);
            }

            return "Dalla fonte '{$bestSource->title}' di {$bestSource->author}: {$description}";
        }

        return "Non ho trovato informazioni specifiche nelle fonti disponibili.";
    }

    private function summarize($text)
    {
        $sentences = explode('.', $text);
        $summary = implode('. ', array_slice($sentences, 0, 2)); // Prendi le prime due frasi
        return $summary . '...';
    }
    public function showConversation(Conversation $conversation)
    {
        $interactions = $conversation->interactions;
        return view('anthaleja.clair.chat.show', compact('interactions'));
    }

    public function resetConversation()
    {
        session()->forget('conversation_history');
        return redirect()->route('chat.index')->with('success', 'Memoria della conversazione resettata.');
    }


    private function extractKeywords($message)
    {
        $words = explode(' ', $message);
        $commonWords = ['il', 'la', 'e', 'di', 'che', 'un', 'una', 'con', 'su', 'per', 'a'];
        return array_filter($words, function ($word) use ($commonWords) {
            return !in_array(strtolower($word), $commonWords);
        });
    }

    private function fetchUrlContent($url, $keywords)
    {
        $client = new Client();
        try {
            $response = $client->get($url);
            $html = (string) $response->getBody();

            $crawler = new Crawler($html);
            $paragraphs = $crawler->filter('p')->each(function (Crawler $node) {
                return $node->text();
            });

            foreach ($paragraphs as $paragraph) {
                foreach ($keywords as $keyword) {
                    if (stripos($paragraph, $keyword) !== false) {
                        return $paragraph;
                    }
                }
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
