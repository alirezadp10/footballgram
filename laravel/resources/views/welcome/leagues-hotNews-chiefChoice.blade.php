<div class="container custom-container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 order-last col-lg-6 order-lg-2 col-xl-6">
            <div class="league-parent">
                <div class="league-nav">
                    <div class="league-nav-item-wrapper">
                        <button data-tag="خلیج_فارس" data-league="khaligefars"
                                class="btn btn-light league-nav-item active">لیگ خلیج فارس
                        </button>
                        <button data-tag="لیگ_جزیره" data-league="premier_league"
                                class="btn btn-light league-nav-item">لیگ جزیره
                        </button>
                        <button data-tag="سری_آ" data-league="calcio"
                                class="btn btn-light league-nav-item">سری آ
                        </button>
                        <button data-tag="بوندس_لیگا" data-league="bundesliga"
                                class="btn btn-light league-nav-item">بوندس لیگا
                        </button>
                        <button data-tag="لالیگا" data-league="laliga"
                                class="btn btn-light league-nav-item">لالیگا
                        </button>
                        <button data-tag="لیگ_قهرمانان_اروپا" data-league="uefa_champions_league"
                                class="btn btn-light league-nav-item">لیگ قهرمانان اروپا
                        </button>
                        <button data-tag="لیگ_اروپا" data-league="europe_league"
                                class="btn btn-light league-nav-item">لیگ اروپا
                        </button>
                        <button data-tag="لیگ_قهرمانان_آسیا" data-league="afc_champions_league"
                                class="btn btn-light league-nav-item">لیگ قهرمانان آسیا
                        </button>
                        <button data-tag="لیگ_ملت_های_اروپا" data-league="europe_nations_league"
                                class="btn btn-light league-nav-item">لیگ ملت های اروپا
                        </button>
                        <button data-tag="جام_ملت_های_اروپا" data-league="uefa_euro"
                                class="btn btn-light league-nav-item">جام ملت های اروپا
                        </button>
                        <button data-tag="جام_ملت_های_آسیا" data-league="afc_asian_cup"
                                class="btn btn-light league-nav-item">جام ملت های آسیا
                        </button>
                        <button data-tag="لوشامپیونه" data-league="loshampione"
                                class="btn btn-light league-nav-item">لوشامپیونه
                        </button>
                        <button data-tag="ارودیوژه" data-league="eredivisie"
                                class="btn btn-light league-nav-item">ارودیوژه
                        </button>
                        <button data-tag="لیگ_آزادگان" data-league="azadegan"
                                class="btn btn-light league-nav-item">لیگ آزادگان
                        </button>
                        <button data-tag="جام_جهانی" data-league="world_cup"
                                class="btn btn-light league-nav-item">جام جهانی
                        </button>
                        <button data-tag="لیگ_ستارگان_قطر" data-league="stars_league"
                                class="btn btn-light league-nav-item">لیگ ستارگان قطر
                        </button>
                    </div>
                </div>
                <div class="league"
                     data-offset="15"
                     data-tag="all"
                     data-status="INCOMPLETE"
                     data-league="khaligefars">
                    <div class="league-header-scope nav nav-tabs">
                        <div class="league-header-news show active"
                             data-toggle="tab"
                             href="#nav-news"
                             role="tab"
                             aria-controls="#nav-news"
                             aria-selected="true">آخرین اخبار
                        </div>
                        <div class="league-header-table"
                             data-toggle="tab"
                             href="#nav-league-table"
                             role="tab"
                             aria-controls="#nav-league-table"
                             aria-selected="false">جدول رده بندی
                        </div>
                        <div class="league-header-scorers"
                             data-toggle="tab"
                             href="#nav-league-scorers"
                             role="tab"
                             aria-controls="#nav-league-scorers"
                             aria-selected="false">جدول گلزنان
                        </div>
                        <div class="league-header-plan"
                             data-toggle="tab"
                             href="#nav-league-plan"
                             role="tab"
                             aria-controls="#nav-league-plan"
                             aria-selected="false">برنامه بازی ها
                        </div>
                    </div>
                    <div class="league-body tab-content"
                         id="nav-tab-content">
                        <div class="tab-pane fade show active"
                             id="nav-news"
                             role="tabpanel"
                             aria-labelledby="nav-news-tab">
                            <div class="list-group-item league-body-clubs">
                                <div class="custom-active" data-tag="all">همه</div>
                                @foreach($response['leagueNewsClubs'] as $club)
                                    <div data-tag="{{ $club['tag'] }}">{{ $club['name'] }}</div>
                                @endforeach
                            </div>
                            <ul class="list-group">
                                @foreach($response['leagueNews'] as $news)
                                    <a href="{{ $news['url'] }}">
                                        <li class="list-group-item league-body-item">
                                            <span class="time" data-to-farsi>
                                                {{ $news['time'] }}
                                            </span>
                                            {{ $news['title'] }}
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-pane fade"
                             id="nav-league-table"
                             role="tabpanel"
                             aria-labelledby="nav-league-table-tab">
                            <div class="list-group-item">
                                <div class="league-table-select">
                                    <label> سال :</label>
                                    <select id="league-table-year">
                                        <option value="2018-2019" selected>۲۰۱۸ - ۲۰۱۹</option>
                                    </select>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped">
                                <thead class="thead-default">
                                <tr>
                                    <th class="league-table-title-position">
                                        <div>#</div>
                                    </th>
                                    <th class="league-table-title-team">
                                        <div>تیم</div>
                                    </th>
                                    <th class="league-table-title-played">
                                        <div>بازی</div>
                                    </th>
                                    <th class="league-table-title-won">
                                        <div>برد</div>
                                    </th>
                                    <th class="league-table-title-drawn">
                                        <div>تساوی</div>
                                    </th>
                                    <th class="league-table-title-lost">
                                        <div>باخت</div>
                                    </th>
                                    <th class="league-table-title-goals-for">
                                        <div>زده</div>
                                    </th>
                                    <th class="league-table-title-goals-against">
                                        <div>خورده</div>
                                    </th>
                                    <th class="league-table-title-goals-difference">
                                        <div>تفاضل</div>
                                    </th>
                                    <th class="league-table-title-points">
                                        <div>امتیاز</div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade"
                             id="nav-league-scorers"
                             role="tabpanel"
                             aria-labelledby="nav-league-scorers-tab">
                            <div class="list-group-item">
                                <div class="league-scorers-select">
                                    <label> سال :</label>
                                    <select id="league-scorers-year">
                                        <option value="2018-2019" selected>۲۰۱۸ - ۲۰۱۹</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade"
                             id="nav-league-plan"
                             role="tabpanel"
                             aria-labelledby="nav-league-plan-tab">
                            <div class="list-group-item">
                                <div class="league-plan-select-parent">
                                    <div class="league-plan-select">
                                        <select id="league-plan-select">
                                            <option value="week" selected>برنامه هفتگی</option>
                                            <option value="team">تیم ها</option>
                                        </select>
                                    </div>
                                    <div class="league-plan-select-result">
                                        <select id="league-plan-select-week-result" data-type="period"></select>
                                        <select id="league-plan-select-team-result" data-type="club"
                                                style="display:none;"></select>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-group"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 order-first col-lg-6 order-lg-1 col-xl-3">
            <div class="row">
                <div class="col-12 col-lg-12 hot-news">
                    <div class="news">
                        <div class="news-header">
                            داغ ترین اخبار
                        </div>
                        <div class="news-body tab-content" id="nav-tab-content-hot-news">
                            <ul class="list-group tab-pane fade show active" id="nav-hot-news" role="tabpanel"
                                aria-labelledby="nav-hot-news-tab">
                                @foreach($response['hotNews'] as $news)
                                    <a href="{{ $news['url'] }}">
                                        <li class="list-group-item news-body-item">
                                            <span class="time" data-to-farsi>
                                                {{ $news['time'] }}
                                            </span>
                                            {{ $news['title'] }}</li>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-6 order-lg-3 col-xl-3">
            <div class="row">
                <div class="col-12 col-lg-12 chief-choice">
                    <div class="chief-choice-title">
                        پیشنهاد سردبیر
                    </div>
                    @foreach($response['chiefChoices'] as $chiefChoice)
                        <div class="chief-choice-item card">
                            <img class="card-img-top lazyload"
                                 style="text-align: right"
                                 data-src="{{ $chiefChoice['image'] }}"
                                 alt="{{ $chiefChoice['title'] }}" />
                            <div class="card-body"
                                 style="text-align: right">
                                {{ $chiefChoice['title'] }}
                            </div>
                            <div class="card-body">
                                <a href="{{ $chiefChoice['newsURL'] }}"
                                   class="card-link">ادامه ی مطلب</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
