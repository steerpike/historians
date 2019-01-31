<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;


class RedditThreadController extends Controller
{
    public function index(Question $question) 
    {
        $parent_id = $question->id;
        $url = explode('/', $question->url);
        array_pop($url);
        $url = implode('/', $url); 
        $api = $url.".json";

        $client = new \GuzzleHttp\Client([
            'headers' => ['User-Agent' => 'AskHistoriansConsumerBot/0.0 (by /u/steerpike404)'],
            'verify' => false]);
        $response = $client->request("GET", 
        $api);
        $contents = json_decode($response->getBody());
        $collection = collect($contents[1]->data->children);
        $data = $collection->mapWithKeys(function($item) {
            return [$item->data->id => [
                'reddit_id' => $item->data->id ?? null,
                'body'=>$item->data->body ?? null,
                'replies'=>$item->data->replies ?? null,
                'body_html'=>$item->data->body_html?? null,
                'permalink'=>$item->data->permalink ?? null,
                'author'=>$item->data->author ?? null,
                'author_flair_text'=> $item->data->author_flair_text ?? null,
                'distinguished'=> $item->data->distinguished ?? null,
                'created'=>$item->data->created ?? null]
            ];
        });
        foreach($data as $answer)
        {
            if(strpos($answer['body'], "AskHistorians/wiki/rules") !== false ||
                strpos(strtolower($answer['body']), "hello everyone") !== false ||
                strlen($answer['body']) <= 10)
            {
                //$answer['display'] = false;
                unset($data[$answer['reddit_id']]);
            }
        }
        foreach($data as $item)
        {
            $answer = Answer::updateOrCreate(['reddit_id'=>$item['reddit_id']],
            [
                'question_id'=>$parent_id,
                'body'=>$item['body'],
                'replies'=>json_encode($item['replies']),
                'body_html'=>$item['body_html'],
                'permalink'=>$item['permalink'],
                'author'=>$item['author'],
                'author_flair_text'=>$item['author_flair_text'],
                'distinguished'=>$item['distinguished'],
                'created'=>$item['created']
            ]);
        }
        return response()->json($data);
    }
    public function threads()
    {
        $questions = Question::where('id','>', 84)->get();
        foreach($questions as $question)
        {
            if(strpos($question['url'], "https://www.reddit.com/r/AskHistorians/comments") === true)
            {
                $parent_id = $question->id;
                $url = explode('/', $question->url);
                array_pop($url);
                $url = implode('/', $url); 
                $api = $url.".json";

                $client = new \GuzzleHttp\Client([
                    'headers' => ['User-Agent' => 'AskHistoriansConsumerBot/0.0 (by /u/steerpike404)'],
                    'verify' => false]);
                $response = $client->request("GET", 
                $api);
                $contents = json_decode($response->getBody());
                $collection = collect($contents[1]->data->children);
                $data = $collection->mapWithKeys(function($item) {
                    return [$item->data->id => [
                        'reddit_id' => $item->data->id ?? null,
                        'body'=>$item->data->body ?? null,
                        'replies'=>$item->data->replies ?? null,
                        'body_html'=>$item->data->body_html?? null,
                        'permalink'=>$item->data->permalink ?? null,
                        'author'=>$item->data->author ?? null,
                        'author_flair_text'=> $item->data->author_flair_text ?? null,
                        'distinguished'=> $item->data->distinguished ?? null,
                        'created'=>$item->data->created ?? null]
                    ];
                });
                foreach($data as $answer)
                {
                    if(strpos($answer['body'], "AskHistorians/wiki/rules") !== false ||
                        strpos(strtolower($answer['body']), "hello everyone") !== false ||
                        strlen($answer['body']) <= 10)
                    {
                        //$answer['display'] = false;
                        unset($data[$answer['reddit_id']]);
                    }
                }
                foreach($data as $item)
                {
                    $answer = Answer::updateOrCreate(['reddit_id'=>$item['reddit_id']],
                    [
                        'question_id'=>$parent_id,
                        'body'=>$item['body'],
                        'replies'=>json_encode($item['replies']),
                        'body_html'=>$item['body_html'],
                        'permalink'=>$item['permalink'],
                        'author'=>$item['author'],
                        'author_flair_text'=>$item['author_flair_text'],
                        'distinguished'=>$item['distinguished'],
                        'created'=>$item['created']
                    ]);
                }
            }
        }
    }
}
