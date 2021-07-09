<div class="container custom-container">
    <div class="row">
        <div class="col col-12 col-md-12 col-xl-6 order-xl-2 slider-wrapper">
            <div class="slider-pro main-slider" style="display:none;">
                <div class="sp-slides">
                    @foreach($response['slider'] as $news)
                        <div class="sp-slide">
                            <a href="{{ $news['newsURL'] }}">
                                <img class="lazyload" style="width: 100%; height: 100%"
                                     data-src="{{ $news['image'] . '&w=900&h=640' }}"
                                     data-retina="{{ $news['image'] . '&w=900&h=640' }}" />
                            </a>
                            <div class="sp-caption">{{ $news['title'] }}</div>
                            @if($news['firstTag'])
                                <a href="{{ $news['firstTagURL'] }}">
                                    <p class="sp-layer sp-layer-tag sp-black sp-padding hide-small-screen"
                                       data-position="leftTop"
                                       data-horizontal="10"
                                       data-vertical="10"
                                       data-show-transition="down"
                                       data-show-delay="1200"
                                       data-hide-transition="up"
                                       data-hide-delay="250">
                                        {{ $news['firstTag'] }}
                                    </p>
                                </a>
                            @endif
                            @if($news['secondTag'])
                                <a href="{{ $news['secondTagURL'] }}">
                                    <p class="sp-layer sp-layer-tag sp-black sp-padding hide-small-screen"
                                       data-horizontal="10"
                                       data-vertical="60"
                                       data-position="leftTop"
                                       data-show-transition="down"
                                       data-show-delay="1300"
                                       data-hide-transition="up"
                                       data-hide-delay="250">
                                        {{ $news['secondTag'] }}
                                    </p>
                                </a>
                            @endif
                            @if($news['thirdTag'])
                                <a href="{{ $news['thirdTagURL'] }}">
                                    <p class="sp-layer sp-layer-tag sp-black sp-padding hide-small-screen"
                                       data-horizontal="10"
                                       data-vertical="110"
                                       data-position="leftTop"
                                       data-show-transition="down"
                                       data-show-delay="1400"
                                       data-hide-transition="up"
                                       data-hide-delay="250">
                                        {{ $news['thirdTag'] }}
                                    </p>
                                </a>
                            @endif
                            @if($news['forthTag'])
                                <a href="{{ $news['forthTagURL'] }}">
                                    <p class="sp-layer sp-layer-tag sp-black sp-padding hide-small-screen"
                                       data-position="leftTop"
                                       data-horizontal="10"
                                       data-vertical="160"
                                       data-show-transition="down"
                                       data-show-delay="1500"
                                       data-hide-transition="up"
                                       data-hide-delay="250">
                                        {{ $news['forthTag'] }}
                                    </p>
                                </a>
                            @endif
                            @if($news['secondaryTitle'])
                                <a href="{{ $news['newsURL'] }}">
                                    <p class="sp-layer sp-black sp-padding"
                                       style="font-size: 22px; font-weight: bold"
                                       data-horizontal="10"
                                       data-vertical="70"
                                       data-position="bottomRight"
                                       data-show-transition="left"
                                       data-show-delay="500"
                                       data-hide-transition="right"
                                       data-hide-delay="250">
                                        {{ $news['secondaryTitle'] }}
                                    </p>
                                </a>
                            @endif
                            <a href="{{ $news['newsURL'] }}">
                                <p class="sp-layer sp-white sp-padding"
                                   style="font-size: 22px; font-weight: bold"
                                   data-horizontal="10"
                                   data-vertical="10"
                                   data-position="bottomRight"
                                   data-show-transition="left"
                                   data-show-delay="800"
                                   data-hide-transition="right"
                                   data-hide-delay="400">
                                    {{ $news['mainTitle'] }}
                                    <span class="hide-medium-screen"></span>
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="sp-thumbnails">
                    @foreach($response['slider'] as $news)
                        <div class="sp-thumbnail">
                            <div class="sp-thumbnail-image-container">
                                <img class="img-fluid sp-thumbnail-image lazyload"
                                     data-src="{{ $news['image'] . '&w=180&h=128' }}" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="main-slider-preloader">
                <img src="/images/football-loading.gif">
            </div>
        </div>
        <div class="col col-12 col-md-6  col-xl-3 order-xl-1 flex-fill last-news-wrapper">
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
                <div class="news-footer hide-in-xl hide-in-lg" id="news-footer">بارگذاری بیشتر&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-angle-double-down"></i>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6  col-xl-3 order-xl-3 users-most-followed">
            <div class="users-most-followed-title">
                دنبال شونده ترین ها
            </div>
            @foreach($response['mostFollowedUsers'] as $user)
                <div class="users-most-followed-item">
                    <a href="{{ $user['url'] }}">
                        <img src="{{ $user['image'] }}"
                             alt="{{ $user['name'] }}"
                             title="{{ $user['name'] }}">
                        <div class="users-most-followed-item-details">
                            <div>{{ $user['name'] }}</div>
                            <div data-to-farsi>{{ $user['countPosts'] }} پست</div>
                            <div data-to-farsi>{{ $user['countFollowers'] }} دنبال کننده</div>
                            <div data-to-farsi>{{ $user['countFollowings'] }} دنبال شونده</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
