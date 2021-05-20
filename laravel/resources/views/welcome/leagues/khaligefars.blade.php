<div class="league khaligefars">
    <div class="league-header khaligefars-header"> لیگ خلیج فارس (لیگ ایران)<img
                style="width: 54px;position: absolute;margin-top: 5px;left: 14px;" src="images/khaligefars.png"
                class="img-fluid"></div>
    <div class="league-header-scope nav nav-tabs">
        <div class="league-header-news active"
             data-toggle="tab"
             data-league="khaligefars"
             href="#nav-khaligefars-news"
             role="tab"
             aria-controls="#nav-khaligefars-news"
             aria-selected="true">آخرین اخبار
        </div>
        <div class="league-header-table"
             data-toggle="tab"
             data-league="khaligefars"
             href="#nav-khaligefars-table"
             role="tab"
             aria-controls="#nav-khaligefars-table"
             aria-selected="false">جدول رده بندی
        </div>
        <div class="league-header-scores"
             data-toggle="tab"
             href="#nav-khaligefars-scores"
             data-league="khaligefars"
             role="tab"
             aria-controls="#nav-khaligefars-scores" aria-selected="false">جدول گلزنان
        </div>
        <div class="league-header-plan"
             data-toggle="tab"
             href="#nav-khaligefars-plan"
             role="tab"
             data-league="khaligefars"
             aria-controls="#nav-khaligefars-plan"
             aria-selected="false">برنامه بازی ها
        </div>
    </div>
    <div class="league-body tab-content" id="nav-tab-content-khaligefars">
        <div class="tab-pane fade show active"
             id="nav-khaligefars-news"
             role="tabpanel"
             aria-labelledby="nav-khaligefars-news-tab">
            <div class="list-group-item league-body-clubs">
                <div class="league-club1 custom-active">همه</div>
                <div class="league-club2">پرسپولیس</div>
                <div class="league-club3">استقلال</div>
                <div class="league-club4">تراکتور</div>
                <div class="league-club5">سپاهان</div>
                <div class="league-club6">فولاد</div>
                <div class="league-club7">نساجی</div>
                <div class="league-club8"></div>
            </div>
            <ul class="list-group">
                @foreach($response['khaligefarsNews'] as $news)
                    <a href="{{ $news['url'] }}">
                        <li class="list-group-item league-body-item">
                            {{ $news['title'] }}
                        </li>
                    </a>
                @endforeach
            </ul>
        </div>
        <div class="table-responsive tab-pane fade" id="nav-khaligefars-table" role="tabpanel"
             aria-labelledby="nav-khaligefars-table-tab">
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
        <div class="table-responsive tab-pane fade" id="nav-khaligefars-scores" role="tabpanel"
             aria-labelledby="nav-khaligefars-scores-tab" style="width: 100%; height: 100%">
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
        </div>
        <div class="tab-pane fade"
             id="nav-khaligefars-plan"
             role="tabpanel"
             aria-labelledby="nav-khaligefars-plan-tab">
            <div class="list-group-item">
                <div class="league-plan-select-parent">
                    <div class="league-plan-select" data-league="khaligefars">
                        <select id="khaligefars-plan-select">
                            <option value="week" selected>برنامه هفتگی</option>
                            <option value="team">تیم ها</option>
                        </select>
                    </div>
                    <div class="league-plan-select-result" data-league="khaligefars">
                        <select id="khaligefars-plan-select-week-result" data-type="period"></select>
                        <select id="khaligefars-plan-select-team-result" data-type="club"
                                style="display:none;"></select>
                    </div>
                </div>
            </div>
            <ul class="list-group"></ul>
        </div>
    </div>
</div>

