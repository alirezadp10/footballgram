<?php


namespace App\Helpers;

use App\User;

class PostHelper extends MainHelper {

    static $comments = [];

    static $text = '';

    static $tags = [];

    public function stairsComments($comments)
    {
        usort($comments, function($a, $b) {
            return $b['id'] - $a['id'];
        });
        $sortedComments = [];

        foreach ($comments as $comment) {
            if ($comment['parent']) {
                if (array_key_exists($comment['id'], $sortedComments)){
                    $temp = $sortedComments[$comment['id']];
                    $temp['details'] = $comment;
                    $sortedComments[$comment['parent']]['child'][$comment['id']] = $temp;
                    unset($sortedComments[$comment['id']]);
                }
                else{
                    $sortedComments[$comment['parent']]['child'][$comment['id']]['details'] = $comment;
                }
            } else {
                $sortedComments[$comment['id']]['details'] = $comment;
            }
        }

        return $sortedComments;
    }

    public function sequentialComments($comments)
    {
        self::$comments = $comments;
        return $this->sortArrayForSequentialComments();
    }

    private function sortArrayForSequentialComments($parent_id = NULL, $sortedComments = [])
    {
        $replies = [];

        foreach (self::$comments as $key => $value) {
            if ($value['parent'] == $parent_id) {
                array_unshift($replies,$value);
                unset(self::$comments[$key]);
            }
        }

        foreach ($replies as $comment) {
            $sortedComments = array_merge(
                array($comment),
                $this->sortArrayForSequentialComments($comment['id'], $sortedComments)
            );
        }

        return $sortedComments;
    }

    public function findSharpInText($text)
    {
        self::$text = $text . ' ';

        self::$text = substr_replace(
            self::$text,
            ' ',
            strpos(self::$text, "\r\n"),
            0
        );

        self::$text = str_replace('&nbsp;', ' &nbsp;', self::$text);

        self::$text = preg_replace('/<a(.*?)class="hashtag">/i', "", self::$text);

        self::$text = preg_replace('/<a class="hashtag"(.*?)>/i', "", self::$text);

        return $this->findSharp(0);
    }

    private function findSharp($offset)
    {
        $hashtagPosition = FALSE;

        if (strlen(self::$text) > $offset){
            $hashtagPosition = strpos(self::$text, '#',$offset);
        }

        if (is_int($hashtagPosition)){

            $spacePositionAfterWord = strpos ( self::$text , ' ' , $hashtagPosition );

            $lessThanPositionAfterWord = strpos ( self::$text , '<' , $hashtagPosition );

            $hashtagPositionAfterWord = strpos ( self::$text , '#' , $hashtagPosition + 1);

            if (is_bool($hashtagPositionAfterWord)){

                if (is_bool($lessThanPositionAfterWord)){
                    $lengthWord = $spacePositionAfterWord - $hashtagPosition;
                }
                else if ($spacePositionAfterWord < $lessThanPositionAfterWord){
                    $lengthWord = $spacePositionAfterWord - $hashtagPosition;
                }
                else if ($lessThanPositionAfterWord < $spacePositionAfterWord){
                    $lengthWord = $lessThanPositionAfterWord - $hashtagPosition;
                }

            }
            elseif ($spacePositionAfterWord < $hashtagPositionAfterWord){

                if (is_bool($lessThanPositionAfterWord)){
                    $lengthWord = $spacePositionAfterWord - $hashtagPosition;
                }
                else if ($spacePositionAfterWord < $lessThanPositionAfterWord){
                    $lengthWord = $spacePositionAfterWord - $hashtagPosition;
                }
                else if($lessThanPositionAfterWord < $spacePositionAfterWord){
                    $lengthWord = $lessThanPositionAfterWord - $hashtagPosition;
                }

            }
            elseif($hashtagPositionAfterWord < $spacePositionAfterWord) {

                if (is_bool($lessThanPositionAfterWord)){
                    $lengthWord = $hashtagPositionAfterWord - $hashtagPosition;
                }
                else if ($hashtagPositionAfterWord < $lessThanPositionAfterWord){
                    $lengthWord = $hashtagPositionAfterWord - $hashtagPosition;
                }
                else if($lessThanPositionAfterWord < $hashtagPositionAfterWord){
                    $lengthWord = $lessThanPositionAfterWord - $hashtagPosition;
                }

            }

            $tag = substr(self::$text, $hashtagPosition, $lengthWord);

            if (substr(self::$text, $hashtagPosition + 7 , 1) !== ';'){
                if (substr(self::$text, $hashtagPosition + 8 , 1) !== '!'){

                    self::$text = substr_replace(
                        self::$text,
                        '<a href="/posts/tags/' . substr($tag,1) . '"  class="hashtag">' ,
                        $hashtagPosition,
                        0
                    );

                    self::$text = substr_replace(
                        self::$text,
                        ' </a>',
                        strpos(self::$text, '#',$offset) + $lengthWord,
                        0
                    );

                    array_push(self::$tags, substr($tag,1));
                }
            }

            $this->findSharp(strpos(self::$text, '#',$offset) + $lengthWord + 5);

        }

        return self::$text;
    }

    public function findMentionInText($text)
    {
        self::$text = $text . ' ';

        self::$text = substr_replace(
            self::$text,
            ' ',
            strpos(self::$text, "\r\n"),
            0
        );

        return $this->findAtSign(0);
    }

    private function findAtSign($offset)
    {
        $atSignPosition = FALSE;

        if (strlen(self::$text) > $offset){
            $atSignPosition = strpos(self::$text, '@',$offset);
        }

        if (is_int($atSignPosition)){

            $spacePositionAfterWord = strpos ( self::$text , ' ' , $atSignPosition );

            $atSignPositionAfterWord = strpos ( self::$text , '@' , $atSignPosition + 1);

            if ($spacePositionAfterWord < $atSignPositionAfterWord || is_bool($atSignPositionAfterWord)) {
                $lengthWord = $spacePositionAfterWord - $atSignPosition;
            }
            else{
                $lengthWord = $atSignPositionAfterWord - $atSignPosition;
            }

            $mention = substr(self::$text, $atSignPosition, $lengthWord);


            $user = User::where('username',substr($mention, 1))->first();
            if(!is_null($user)){

                self::$text = substr_replace(
                    self::$text,
                    '<a href="' . route('users.show',[ 'id' => $user->id, 'name' => $user->name ]) . '" class="mention">' ,
                    $atSignPosition,
                    0
                );

                self::$text = substr_replace(
                    self::$text,
                    ' </a>',
                    strpos(self::$text, '@',$offset) + $lengthWord,
                    0
                );

            }

            $this->findAtSign(strpos(self::$text, '@',$offset) + $lengthWord + 5);

        }

        return self::$text;
    }
}