<div class="post-side-block">
    <div class="news last-news">
        <div class="news-header last-news-header">
            جدیدترین اخبار
        </div>
        <div class="news-body tab-content"
             data-status="INCOMPLETE"
             data-offset="15"
             id="nav-tab-content-last-news">
            <ul class="list-group tab-pane fade show active" id="nav-last-news" role="tabpanel"
                aria-labelledby="nav-last-news-tab">
                @foreach($response['lastNews'] as $news)
                    <a href="{{ $news['url'] }}">
                        <li class="list-group-item news-body-item">
                                    <span class="time" data-to-farsi>
                                        {{ $news['time'] }}
                                    </span>
                            {{ $news['title'] }}
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
        <div class="news-footer hide-in-xl hide-in-lg">بارگذاری بیشتر&nbsp;&nbsp;&nbsp;
            <i class="fas fa-angle-double-down"></i>
        </div>
    </div>
    <div class="text-center hot-news-title">داغ ترین اخبار</div>
    @foreach($response['hotNews'] as $hotNews)
        <div class="card post-news-card">
            <a href="{{ $hotNews['url'] }}">
                <div class="card-body">
                    <span class="hot-news-index" data-to-farsi>{{ $loop->iteration }}</span>
                    <img class="img-fluid lazyload card-img"
                         data-src="{{ $hotNews['mainPhoto'] }}"
                         alt="Card image cap">
                    <p class="card-text">{{ $hotNews['title'] }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>
