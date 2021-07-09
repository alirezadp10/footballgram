<div class="container custom-container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 order-last col-lg-6 order-lg-2 col-xl-6 middle-content">
            <div class="survey">
                @if($response['survey'])
                    <div class="survey-title">نظرسنجی
                        @if($response['manageSurvey'])
                            <span id="add-survey" data-status="close">+</span>
                        @endif
                    </div>
                    <div class="survey-body" data-survey-id="{{ $response['survey']['id'] }}">
                        <h5 class="question">{{ $response['survey']['question'] }}
                            <div class="question-number" data-to-farsi>{{ $response['survey']['id'] }}</div>
                        </h5>
                        @foreach($response['survey']['options'] as $key => $option)
                            <h6 class="option {{ $response['surveySelectedOption'] == $key + 1 ? 'selected' : '' }}"
                                data-index="{{ $key + 1 }}"
                                data-title="{{ $option['title'] }}"
                                data-count="{{ $option['count'] }}"
                                data-to-farsi>{{ $key + 1 }}- {{ $option['title'] }}</h6>
                        @endforeach
                    </div>
                    <div class="survey-chart">
                        <canvas></canvas>
                    </div>
                    @if($response['manageSurvey'])
                        <div class="add-survey">
                            <form method="post" action="{{ route('surveys.store') }}">
                                @csrf
                                <h5 class="question">
                                    <input type="text"
                                           name="question"
                                           placeholder="سوال">
                                </h5>
                                <h6 class="option">
                                    <input type="text"
                                           name="options[]"
                                           placeholder="گزینه">
                                </h6>
                                <button type="button" class="btn btn-primary add-survey-option">+</button>
                                <button type="submit" class="btn btn-success survey-store-btn">ثبت</button>
                            </form>
                        </div>
                    @endif
                    <div class="survey-footer">
                        <div class="control">
                            <button class="btn btn-info" id="previous-survey">قبلی</button>
                            <button class="btn btn-info" id="next-survey">بعدی</button>
                        </div>
                        <button class="btn btn-warning results">مشاهده ی نتایج</button>
                        <button class="btn btn-warning show-survey">بازگشت</button>
                    </div>
                @endif
            </div>
            <div class="newspapers">
                <div class="newspapers-title">گیشه</div>
                <div class="newspapers-body">
                    <div id="newspaper" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#newspaper" data-slide-to="0" class="active"></li>
                            <li data-target="#newspaper" data-slide-to="1"></li>
                            <li data-target="#newspaper" data-slide-to="2"></li>
                            <li data-target="#newspaper" data-slide-to="3"></li>
                            <li data-target="#newspaper" data-slide-to="4"></li>
                            <li data-target="#newspaper" data-slide-to="5"></li>
                            <li data-target="#newspaper" data-slide-to="6"></li>
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img data-src="/images/np1.jpg"
                                     onclick="window.open(this.src);"
                                     alt="First slide"
                                     class="lazyload">
                            </div>
                            <div class="carousel-item">
                                <img data-src="/images/np2.jpg"
                                     onclick="window.open(this.src);"
                                     alt="Second slide"
                                     class="lazyload">
                            </div>
                            <div class="carousel-item">
                                <img data-src="/images/np3.jpg"
                                     onclick="window.open(this.src);"
                                     alt="Third slide"
                                     class="lazyload">
                            </div>
                            <div class="carousel-item">
                                <img data-src="/images/np4.jpg"
                                     onclick="window.open(this.src);"
                                     alt="Third slide"
                                     class="lazyload">
                            </div>
                            <div class="carousel-item">
                                <img data-src="/images/np5.jpg"
                                     onclick="window.open(this.src);"
                                     alt="Third slide"
                                     class="lazyload">
                            </div>
                            <div class="carousel-item">
                                <img data-src="/images/np6.jpg"
                                     onclick="window.open(this.src);"
                                     alt="Third slide"
                                     class="lazyload">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#newspaper" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">{{ __('previous') }}</span>
                        </a>
                        <a class="carousel-control-next" href="#newspaper" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">{{ __('next') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 order-first col-lg-6 order-lg-1 col-xl-3">
            <div class="row">
                <div class="col-12 col-lg-12 video">
                    <div class="video-title">
                        سرویس ویدیو
                    </div>
                    <div class="col-12 bg-light w-100 mb-3" style="height: 300px;"></div>
                    <div class="col-12 bg-light w-100 mb-3" style="height: 300px;"></div>
                    <div class="col-12 bg-light w-100 mb-3" style="height: 300px;"></div>
                    <div class="col-12 bg-light w-100 mb-3" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-6 order-lg-3 col-xl-3">
            <div class="row">
                <div class="col-12 col-lg-12 broadcast-schedule">
                    <div class="broadcast-schedule-title">
                        جدول پخش زنده
                        @if($response['manageBroadcastSchedule'])
                            <span id="broadcast-schedule-add" data-status="show">+</span>
                        @endif
                    </div>
                    <div class="broadcast-schedule-body">
                        @foreach($response['broadcastSchedule'] as $item)
                            <div class="container broadcast-schedule-item"
                                 @if($response['manageBroadcastSchedule'])
                                 data-id="{{ $item['id'] }}"
                                 data-datetime="{{ $item['datetime'] }}"
                                 data-host="{{ $item['host'] }}"
                                 data-guest="{{ $item['guest'] }}"
                                 data-channel-broadcast="{{ $item['alt'] }}"
                                    @endif
                            >
                                @if($response['manageBroadcastSchedule'])
                                    <span class="broadcast-schedule-delete text-danger"
                                          data-id="{{ $item['id'] }}"
                                          id="broadcast-schedule-delete"
                                          title="حذف">&times;
                                    </span>
                                    <form method="post"
                                          action="{{ route('broadcastSchedule.destroy',$item['id']) }}"
                                          class="broadcast-schedule-delete-form"
                                          data-id="{{ $item['id'] }}"
                                          hidden>
                                        @csrf
                                        @method('delete')
                                    </form>
                                    <span class="broadcast-schedule-edit text-success"
                                          data-id="{{ $item['id'] }}"
                                          id="broadcast-schedule-edit"
                                          title="ویرایش"><i class="fa fa-pencil-alt"></i>
                                    </span>
                                @endif
                                <div class="row">
                                    <div class="col-3 col-sm-3 p-0">
                                        <div class="broadcast-schedule-item-image">
                                            <img src="{{ $item['image'] }}"
                                                 class="img-fluid"
                                                 alt="{{ $item['alt'] }}"
                                                 title="{{ $item['alt'] }}">
                                        </div>
                                    </div>
                                    <div class="col-9 col-sm-9 pr-0 pl-1">
                                        <div class="match-title">{{ $item['host'] }} - {{ $item['guest'] }}</div>
                                        <div class="match-time" data-to-farsi>{{ $item['time'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
