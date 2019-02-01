<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Question;
use textrazor\textrazorPhp\TextRazor;
//require_once('../vendor/textrazor/textrazor-php/TextRazor.php');

class RedditQuestionController extends Controller
{
    //
    public function index(Request $request) 
    {
        $client = new \GuzzleHttp\Client([
            'headers' => ['User-Agent' => 'AskHistoriansConsumerBot/0.0 (by /u/steerpike404)'],
            'verify' => false]);
        $args = array();
        $args['limit'] = 100;
        $args['t'] = 'year';
        $created = 0;
        $updated = 0;
        if($request->input('after')) {
            $args['after'] = $request->input('after');
        }
        $response = $client->request("GET", 
        'https://www.reddit.com/r/AskHistorians/top.json',
        ['query'=>$args]);
        //['query'=>['t'=>'all', 'after'=>'t3_6ijmg2']]);
        $contents = json_decode($response->getBody());
        $after = $contents->data->after;
        $collection = collect($contents->data->children);
        $data = $collection->mapWithKeys(function($item) {
            return [$item->data->id => [
                'reddit_id'=>$item->data->id,
                'url'=>$item->data->url,
                'title'=>$item->data->title,
                'text'=>$item->data->selftext,
                'html'=>$item->data->selftext_html,
                'permalink'=>$item->data->permalink,
                'author'=>$item->data->author,
                'created_utc'=>$item->data->created_utc]
            ];
        });
        foreach($data as $thread) {
            $question = Question::updateOrCreate(['reddit_id'=>$thread['reddit_id']],
                ['url'=>$thread['url'],
                'title'=>$thread['title'],
                'text'=>$thread['text'],
                'html'=>$thread['html'],
                'permalink'=>$thread['permalink'],
                'author'=>$thread['author'],
                'created_utc'=>$thread['created_utc']]
            );
            if($question->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }
        $result = "Created: ".$created." Updated: ".$updated." Next batch found at: ".$after."<br />";
        return $result;
        //return response()->json($data);
    }
    public function show(Question $question)
    {
        $text = $question->getRawContent();
        $textrazor = new \TextRazor('f0329804115be08a996355eebf848027c3c75f30e5c8ded0ab002fb7');
        $textrazor->addExtractor('topics');
        $textrazor->setClassifiers([ 
            "textrazor_newscodes"
            ]);
        $response = $textrazor->analyze($text);
        $categorise = $question->processCategorisationResponse($response);
        return $text;
    }
    public function categorise()
    {
        //$questions = Question::all();
        $questions = Question::whereDoesntHave('ontologies')
                    ->where('categorised','=', false)
                    ->take(247)
                    ->get();
        //dd($questions);
        foreach($questions as $question)
        {
            $text = $question->getRawContent();
            $textrazor = new \TextRazor('f0329804115be08a996355eebf848027c3c75f30e5c8ded0ab002fb7');
            $textrazor->addExtractor('topics');
            $textrazor->setClassifiers([ 
                "textrazor_newscodes"
                ]);
            $response = $textrazor->analyze($text);
            $categorise = $question->processCategorisationResponse($response);
        }
        echo count($questions);
    }
    public function latest(Request $request) 
    {
        $client = new \GuzzleHttp\Client([
            'headers' => ['User-Agent' => 'AskHistoriansConsumerBot/0.0 (by /u/steerpike404)'],
            'verify' => false]);
        $args = array();
        $args['limit'] = 100;
        //$args['t'] = 'all';
        $created = 0;
        $updated = 0;
        if($request->input('after')) {
            $args['after'] = $request->input('after');
        }
        $response = $client->request("GET", 
        'https://www.reddit.com/r/AskHistorians/new.json',
        ['query'=>$args]);
        //['query'=>['t'=>'all', 'after'=>'t3_6ijmg2']]);
        $contents = json_decode($response->getBody());
        $after = $contents->data->after;
        $collection = collect($contents->data->children);
        
        $data = $collection->mapWithKeys(function($item) {
            return [$item->data->id => [
                'reddit_id'=>$item->data->id,
                'url'=>$item->data->url,
                'title'=>$item->data->title,
                'text'=>$item->data->selftext,
                'html'=>$item->data->selftext_html,
                'permalink'=>$item->data->permalink,
                'author'=>$item->data->author,
                'created_utc'=>$item->data->created_utc]
            ];
        });
        foreach($data as $thread) {
            $question = Question::updateOrCreate(['reddit_id'=>$thread['reddit_id']],
                ['url'=>$thread['url'],
                'title'=>$thread['title'],
                'text'=>$thread['text'],
                'html'=>$thread['html'],
                'permalink'=>$thread['permalink'],
                'author'=>$thread['author'],
                'created_utc'=>$thread['created_utc']]
            );
            if($question->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }
        $result = "Created: ".$created." Updated: ".$updated." Next batch found at: ".$after."<br />";
        return $result;
        //return response()->json($data);
    }
}
