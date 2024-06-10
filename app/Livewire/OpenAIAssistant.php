<?php

namespace App\Livewire;

use OpenAI;
//use OpenAI\Client as OpenAIClient;
use Livewire\Component;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

class OpenAIAssistant extends Component
{
    public $query;
    public $response;
    public $responseType = 'text';
    public $conversation = [];

    protected $rules = [
        'query' => 'required|string|min:1',
    ];

    public function submit()
    {
        $this->validate();

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        if ($this->responseType === 'text') {
            $result = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => array_merge(
                    [['role' => 'system', 'content' => 'You are a helpful assistant.']],
                    $this->conversation,
                    [['role' => 'user', 'content' => $this->query]]
                ),
            ]);

            $this->response = $result['choices'][0]['message']['content'];
            $this->conversation[] = ['role' => 'user', 'content' => $this->query];
            $this->conversation[] = ['role' => 'assistant', 'content' => $this->convertMarkdownToHtml($this->response)];
        } elseif ($this->responseType === 'image') {
            $result = $client->images()->create([
                'prompt' => $this->query,
                'n' => 1,
                "model" => "dall-e-3",
                'size' => '1024x1024',
            ]);

            $imageUrl = $result['data'][0]['url'];
            $this->conversation[] = ['role' => 'user', 'content' => $this->query];
            $this->conversation[] = ['role' => 'assistant', 'content' => "<img src=\"$imageUrl\" alt=\"Generated Image\">"];
        }

        // Clear the query input
        $this->query = '';
    }

    protected function convertMarkdownToHtml($markdown)
    {
        // Create a new Environment with necessary extensions
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());

        // Instantiate the MarkdownConverter with the environment
        $converter = new MarkdownConverter($environment);

        // Convert the Markdown to HTML and return the content
        return $converter->convert($markdown)->getContent();
    }

    public function render()
    {
        //dd('loaded');
        return view('livewire.open-ai-assistant');
    }
}
