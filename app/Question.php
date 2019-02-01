<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;

class Question extends Model
{
    protected $guarded = [];
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
    public function ontologies()
    {
        return $this->belongsToMany('App\Tag')
                ->orderBy('label', 'DESC');;
    }
    public function categories()
    {
        return $this->belongsToMany('App\Tag')
            ->where('tags.type', '=', 'category')
            ->orderBy('label', 'DESC');
    }
    public function tags()
    {
        return $this->belongsToMany('App\Tag')
            ->where('tags.type', '=', 'tag')
            ->orderBy('label', 'DESC');;
    }
    public function topics()
    {
        return $this->belongsToMany('App\Tag')
            ->where('tags.type', '=', 'topic')
            ->orderBy('label', 'DESC');;
    }
    public function getRawContent() 
    {
        $text = $this->title." ".strip_tags(html_entity_decode($this->html));
        foreach($this->answers as $answer)
        {
            $text = $text." ".strip_tags(html_entity_decode($answer->body_html));
        }
        return $text;
    }
    public function processCategorisationResponse($response)
    {  
        if (isset($response['response'])) {
            if(isset($response['response']['coarseTopics'])) {
                $coarseTopics = $this->processTopicArray($response['response']['coarseTopics'], 0.4, 'topic');
            }
            if(isset($response['response']['topics'])) {
                $topics = $this->processTopicArray($response['response']['topics'], 0.7, 'tag');
            }
            if(isset($response['response']['categories'])) {
                $topics = $this->processTopicArray($response['response']['categories'], 0.4, 'category');
            }
        }
        $this->categorised = true;
        $this->save();
        return $this;
    }
    public function processTopicArray($topics, $score, $type)
    {
        foreach($topics as $topic) 
        {
            if($topic['score']>=$score)
            {   
                $tag = Tag::updateOrCreate(['label'=>$topic['label']],
                    [
                        'wiki_link'=>$topic['wikiLink'] ?? null,
                        'wikidataId'=>$topic['wikidataId'] ?? null,
                        'categoryId'=>$topic['categoryId'] ?? null,
                        'type'=>$type
                    ]
                );
                $this->ontologies()->syncWithoutDetaching($tag->id);
            }
        }
    }
}
