<div class="league calcio">
    <div class="league-header calcio-header"> سری آ (لیگ ایتالیا)<img
                style="width: 33px;position: absolute;margin-top: 3px;left: 20px;" data-src="images/calcio.png"
                class="img-fluid lazyload"></div>
    <div class="league-header-scope nav nav-tabs">
        <div class="league-header-news active"
             data-toggle="tab"
             href="#nav-calcio-news"
             role="tab"
             data-league="calcio"
             aria-controls="#nav-calcio-news"
             aria-selected="true">آخرین اخبار
        </div>
        <div class="league-header-table"
             data-toggle="tab"
             href="#nav-calcio-table"
             role="tab"
             data-league="calcio"
             aria-controls="#nav-calcio-table"
             aria-selected="false">جدول رده بندی
        </div>
        <div class="league-header-scores"
             data-toggle="tab"
             href="#nav-calcio-scores"
             role="tab"
             data-league="calcio"
             aria-controls="#nav-calcio-scores"
             aria-selected="false">جدول گلزنان
        </div>
        <div class="league-header-plan"
             data-toggle="tab"
             href="#nav-calcio-plan"
             role="tab"
             data-league="calcio"
             aria-controls="#nav-calcio-plan"
             aria-selected="false">برنامه بازی ها
        </div>
    </div>
    <div class="league-body tab-content" id="nav-tab-content-calcio">
        <div class="tab-pane fade show active"
             id="nav-calcio-news"
             role="tabpanel"
             aria-labelledby="nav-hot-news-external-tab">
            <div class="list-group-item league-body-clubs">
                <div class="league-club1 custom-active">همه</div>
                <div class="league-club2">یوونتوس</div>
                <div class="league-club3">میلان</div>
                <div class="league-club4">اینتر</div>
                <div class="league-club5">رم</div>
                <div class="league-club6">ناپولی</div>
                <div class="league-club7">فیورنتینا</div>
                <div class="league-club8"></div>
            </div>
            <ul class="list-group">
                @foreach($response['calcioNews'] as $news)
                    <a href="{{ $news['url'] }}">
                        <li class="list-group-item league-body-item">
                            {{ $news['title'] }}
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
        <div class="table-responsive tab-pane fade" id="nav-calcio-table" role="tabpanel"
             aria-labelledby="nav-hot-news-external-tab">
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
        </div>
        <div class="table-responsive tab-pane fade" id="nav-calcio-scores" role="tabpanel"
             aria-labelledby="nav-hot-news-external-tab" style="width: 100%; height: 100%">
            <table class="table table-hover table-bordered" style="font-size: 14px; text-align: center">
                <thead class="thead-default">
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
        </div>
        <div class="tab-pane fade"
             id="nav-calcio-plan"
             role="tabpanel"
             aria-labelledby="nav-calcio-plan-tab">
            <div class="list-group-item">
                <div class="league-plan-select-parent">
                    <div class="league-plan-select" data-league="calcio">
                        <select id="calcio-plan-select">
                            <option value="week" selected>برنامه هفتگی</option>
                            <option value="team">تیم ها</option>
                        </select>
                    </div>
                    <div class="league-plan-select-result" data-league="calcio">
                        <select id="calcio-plan-select-week-result" data-type="period"></select>
                        <select id="calcio-plan-select-team-result" data-type="club" style="display:none;"></select>
                    </div>
                </div>
            </div>
            <ul class="list-group"></ul>
        </div>
    </div>
</div>
