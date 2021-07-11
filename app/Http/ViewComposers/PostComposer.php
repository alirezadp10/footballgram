<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class PostComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view['last_news'] = [];

        $view['hot_news'] = [];
    }

    private function lastNews()
    {
//        $lastNews = News::lastNews();
//
//        $response['lastNews'] = [];
//
//        foreach ($lastNews as $news) {
//            $separator = $news->secondaryTitle ? '؛' : '';
//            $response['lastNews'][] = [
//                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
//                'time'      => Carbon::parse($news->created_at)
//                                     ->format('H:i'),
//                'url'       => route('news.show', [$news->slug]),
//            ];
//        }
//
//        return $response;
    }

    private function hotNews($response)
    {
//        $hotNews = News::hotNewsWithImage();
//
//        $response['hotNews'] = [];
//
//        foreach ($hotNews as $news) {
//            $separator = $news->secondaryTitle ? '؛' : '';
//            $response['hotNews'][] = [
//                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
//                'mainPhoto' => Storage::url($news->sm),
//                'time'      => Carbon::parse($news->created_at)
//                                     ->format('H:i'),
//                'url'       => route('news.show', [$news->slug]),
//            ];
//        }
//
//        return $response;
    }
}
