<div class="league premier_league">
    <div class="league-nav">
        <div class="league-nav-item-wrapper">
            <a href="#">
                <button class="btn btn-light league-nav-item active">لیگ خلیج فارس</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">سری آ</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">بوندس لیگا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لالیگا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لوشامپیونه</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">ارودیوژه</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ آزادگان</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ جزیره</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ قهرمانان اروپا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ اروپا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ قهرمانان آسیا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ ملت های اروپا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">جام ملت های اروپا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">جام جهانی</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">جام ملت های آسیا</button>
            </a>
            <a href="#">
                <button class="btn btn-light league-nav-item">لیگ ستارگان قطر</button>
            </a>
        </div>
    </div>
    <div class="league-header premier_league-header"> لیگ جزیره (لیگ انگلیس)<img
                style="width: 85px;position: absolute;margin-top: -20px;left: 10px;"
                data-src="images/premier_league.png" class="img-fluid lazyload"></div>
    <div class="league-header-scope nav nav-tabs">
        <div class="league-header-news active"
             data-toggle="tab"
             href="#nav-premier_league-news"
             data-league="premier_league"
             role="tab"
             aria-controls="#nav-premier_league-news"
             aria-selected="true">آخرین اخبار
        </div>
        <div class="league-header-table"
             data-toggle="tab"
             href="#nav-premier_league-table"
             data-league="premier_league"
             role="tab"
             aria-controls="#nav-premier_league-table"
             aria-selected="false">جدول رده بندی
        </div>
        <div class="league-header-scores"
             data-toggle="tab"
             href="#nav-premier_league-scores"
             data-league="premier_league"
             role="tab"
             aria-controls="#nav-premier_league-scores"
             aria-selected="false">جدول گلزنان
        </div>
        <div class="league-header-plan"
             data-toggle="tab"
             href="#nav-premier_league-plan"
             data-league="premier_league"
             role="tab"
             aria-controls="#nav-premier_league-plan"
             aria-selected="false">برنامه بازی ها
        </div>
    </div>
    <div class="league-body tab-content" id="nav-tab-content-premier_league">
        <div id="nav-premier_league-news"
             class="tab-pane fade show active"
             role="tabpanel"
             aria-labelledby="nav-premier_league-news-tab">
            <div class="list-group-item league-body-clubs">
                <div class="league-club1 custom-active">همه</div>
                <div class="league-club2">منچستر یونایتد</div>
                <div class="league-club3">منچستر سیتی</div>
                <div class="league-club4">چلسی</div>
                <div class="league-club5">لیورپول</div>
                <div class="league-club6">آرسنال</div>
                <div class="league-club7">تاتنهام</div>
                <div class="league-club8"></div>
            </div>
            <ul class="list-group">
                @foreach($response['premierleagueNews'] as $news)
                    <a href="{{ $news['url'] }}">
                        <li class="list-group-item league-body-item">
                            {{ $news['title'] }}
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
        <ul class="list-group tab-pane fade" id="nav-premier_league-table" role="tabpanel"
            aria-labelledby="nav-premier_league-table-tab">
            <table class="table table-hover table-bordered" style="font-size: 12px; text-align: center">
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
        </ul>
        <ul class="list-group tab-pane fade" id="nav-premier_league-scores" role="tabpanel"
            aria-labelledby="nav-premier_league-scores-tab">
            <table class="table table-hover table-bordered" style="font-size: 14px; text-align: center">
                <thead class="thead-default">
                <tr>
                <tr>
                    <th class="league-scores-title-position">
                        <div>#</div>
                    </th>
                    <th class="league-scores-title-name">
                        <div>نام بازیکن</div>
                    </th>
                    <th class="league-scores-title-played">
                        <div>تعداد بازی</div>
                    </th>
                    <th class="league-scores-title-goals">
                        <div>تعداد گل</div>
                    </th>
                    <th class="league-scores-title-assists">
                        <div>تعداد پاس گل</div>
                    </th>
                </tr>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                <tr>
                    <td class="league-scores-body-position">1</td>
                    <td class="league-scores-body-name">کریستیانو رونالدو</td>
                    <td class="league-scores-body-palyed">10</td>
                    <td class="league-scores-body-goals">16</td>
                    <td class="league-scores-body-assists">5</td>
                </tr>
                </tbody>
            </table>
        </ul>
        <div class="tab-pane fade"
             id="nav-premier_league-plan"
             role="tabpanel"
             aria-labelledby="nav-premier_league-plan-tab">
            <div class="list-group-item">
                <div class="league-plan-select-parent">
                    <div class="league-plan-select" data-league="premier_league">
                        <select id="premier_league-plan-select">
                            <option value="week" selected>برنامه هفتگی</option>
                            <option value="team">تیم ها</option>
                        </select>
                    </div>
                    <div class="league-plan-select-result" data-league="premier_league">
                        <select id="premier_league-plan-select-week-result" data-type="period"></select>
                        <select id="premier_league-plan-select-team-result" data-type="club"
                                style="display:none;"></select>
                    </div>
                </div>
            </div>
            <ul class="list-group"></ul>
        </div>
    </div>
</div>