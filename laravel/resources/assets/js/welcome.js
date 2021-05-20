window._ = require("lodash");

String.prototype.DigitsToFarsi = function () {
    var id = [
        "۰",
        "۱",
        "۲",
        "۳",
        "۴",
        "۵",
        "۶",
        "۷",
        "۸",
        "۹",
    ];
    return this.replace(/[0-9]/g, function (w) {
        return id[+w];
    });
};

String.prototype.FarsiToDigits = function () {
    var id = {"۰": "0", "۱": "1", "۲": "2", "۳": "3", "۴": "4", "۵": "5", "۶": "6", "۷": "7", "۸": "8", "۹": "9"};
    return this.replace(/[^0-9.]/g, function (w) {
        return id[w] || w;
    });
};

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require("jquery");
    require("bootstrap");
    require("jquery-lazy");
    window.alertify = require("alertifyjs");
    require("datatables.net")(window, $);
} catch (e) {
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 * window.axios = require('axios');
 * window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
 */

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.

 * let token = document.head.querySelector('meta[name="csrf-token"]');
 * if (token) {
 *     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
 * } else {
 *     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
 * }
 */

require("slider-pro");
require("slick-carousel");
require("persian-datepicker/dist/js/persian-datepicker.min.js");
$(document).ready(function () {

    $(window).on("load", function () { // makes sure the whole site is loaded
        $("#status").fadeOut(); // will first fade out the loading animation
        $("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
        $("body").delay(350).css({"overflow": "visible"});
    });

    $("*[data-to-farsi]").html(function () {
        return $(this).html().DigitsToFarsi();
    });

    $(".lazyload").Lazy();

    $(".parallax-lazyload").Lazy();

    if (window.innerWidth < 768) {
        $("p[data-position='bottomRight']").attr("data-horizontal", "10");
        $("p[data-position='bottomRight']").attr("data-vertical", function () {
            if ($(this).attr("data-vertical") == 120) {
                return 75;
            }
            if ($(this).attr("data-vertical") == 50) {
                return 10;
            }
        });
    }

    $(".main-slider").fadeIn();
    $(".main-slider-preloader").fadeOut();
    $(".main-slider").sliderPro({
        width             : "100%",
        height            : 600,
        orientation       : "horizontal",
        loop              : true,
        arrows            : true,
        buttons           : false,
        thumbnailPointer  : true,
        waitForLayers     : true,
        thumbnailsPosition: "bottom",
        autoScaleLayers   : true,
        thumbnailWidth    : "183px",
        thumbnailHeight   : "150px",
        breakpoints       : {
            768: {
                width             : "100%",
                height            : 400,
                thumbnailsPosition: "bottom",
                thumbnailWidth    : 0,
                thumbnailHeight   : 0,
            },
            576: {
                width             : "100%",
                height            : 400,
                thumbnailsPosition: "bottom",
                thumbnailWidth    : 0,
                thumbnailHeight   : 0,
            },
        },
    });

    $("*[data-toggle='tooltip']").tooltip({
        container: "body",
    });

    $(".button-new-post").click(function () {
        if (window.innerWidth < 768) {
            if (!$(this).attr("href")) {
                $(this).tooltip("hide");
                if ($(this).attr("data-status") === "collapse") {
                    $(".button-new-tweet").animate({
                        "bottom": 189,
                    }, 500);
                    $(".button-new-news").animate({
                        "bottom": 133,
                    }, 500);
                    $(".button-new-user-content").animate({
                        "bottom": 77,
                    }, 500);
                    $(this).attr("data-status", "expand");
                }
                else {
                    $(".button-new-tweet").animate({
                        "bottom": 20,
                    }, 500);
                    $(".button-new-news").animate({
                        "bottom": 20,
                    }, 500);
                    $(".button-new-user-content").animate({
                        "bottom": 20,
                    }, 500);
                    $(this).attr("data-status", "collapse");
                }
            }
        }
        else {
            if (!$(this).attr("href")) {
                if ($(this).attr("data-status") === "collapse") {
                    $(".button-new-tweet").animate({
                        "bottom": 260,
                    }, 500);
                    $(".button-new-news").animate({
                        "bottom": 180,
                    }, 500);
                    $(".button-new-user-content").animate({
                        "bottom": 100,
                    }, 500);
                    $(this).attr("data-status", "expand");
                }
                else {
                    $(".button-new-tweet").animate({
                        "bottom": 20,
                    }, 500);
                    $(".button-new-news").animate({
                        "bottom": 20,
                    }, 500);
                    $(".button-new-user-content").animate({
                        "bottom": 20,
                    }, 500);
                    $(this).attr("data-status", "collapse");
                }
            }
        }
    });

    $("#navbar-btn-search").click(function () {
        if (window.innerWidth > 768) {
            $(".main-nav-form-inline").toggle();
            $(".main-navbar>.navbar-brand").hide();
            $("#main-nav-items-wrapper").attr("style", "display:none !important");
        }
    });

    $("#navbar-close-form").click(function () {
        if (window.innerWidth > 768) {
            $(".main-nav-form-inline").toggle();
            $(".main-navbar>.navbar-brand").show();
            $("#main-nav-items-wrapper").attr("style", "display:flex !important");
        }
    });

    $("#navbar-user-avatar-sm").click(function () {
        if (window.innerWidth < 768) {
            $("#main-nav-items-wrapper").hide();
            $("#navbar-user-dropdown-sm").toggle();
        }
    });

    $("#navbar-brand").click(function (e) {
        if (window.innerWidth < 768) {
            e.preventDefault();
            $("#navbar-user-dropdown-sm").hide();
            $("#main-nav-items-wrapper").toggle();
        }
    });

    $("#notifications-button-sm").click(function (e) {
        e.stopPropagation();
        $("#notifications-container-sm").toggle();
    });

    $("#notifications-button-lg").click(function (e) {
        e.stopPropagation();
        $("#notifications-container-lg").toggle();
    });

    $("#navbar-user-avatar-sm,#navbar-user-avatar-lg").click(function () {
        $.ajax({
            url    : "/users/get-notifications",
            type   : "GET",
            data   : {
                "offset": 0,
                "take"  : 3,
            },
            success: function (data) {
                $(".notifications-container").html("");
                if (data.length) {
                    data.forEach(function (item) {
                        $(".notifications-container").append(`
                            <a href="${item["url"]}">
                                <div class="notification-item">
                                    <img src="${item["avatar"]}"
                                         class="img-fluid notification-item-avatar">
                                    <small class="notification-item-title">
                                        ${item["title"]}
                                    </small>
                                    <div class="notification-item-context">
                                        ${item["context"]}
                                    </div>
                                </div>
                            </a>    
                        `);
                    });
                    $(".notifications-container").append(`
                        <a href="/users/all-notifications">
                            <div class="all-notifications">مشاهده ی همه</div>
                        </a>
                    `);
                }
                else {
                    $(".notifications-container").append(`
                        <div class="no-notification">هیچ اعلانی وجود ندارد</div>
                    `);
                }
            },
            error  : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    function pad2(number) {
        return (number < 10 ? "0" : "") + number;
    }

    $(".league-header-table").click(function () {
        let league = $(".league").attr("data-league");
        $(`#league-table-year`).empty();
        if (league === "world_cup") {
            for (let i = 2018; i >= 1990; i -= 4) {
                let selected = "";
                if (i === 2018) {
                    selected = "selected";
                }
                $("#league-table-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "uefa_euro") {
            for (let i = 2016; i >= 1996; i -= 4) {
                let selected = "";
                if (i === 2016) {
                    selected = "selected";
                }
                $("#league-table-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "afc_asian_cup") {
            for (let i = 2019; i >= 1987; i -= 4) {
                let selected = "";
                if (i === 2019) {
                    selected = "selected";
                }
                $("#league-table-year").append(`
                    <option ${selected} value="${i > 2004 ? i : i + 1}">${String(i > 2004 ? i : i + 1).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "afc_champions_league") {
            for (let i = 2018; i >= 2010; i--) {
                let selected = "";
                if (i === 2018) {
                    selected = "selected";
                }
                $("#league-table-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else {
            for (let i = 18; i > 0; i--) {
                if (league === "khaligefars" && i === 0) {
                    break;
                }
                if (league === "premier_league" && i === 8) {
                    break;
                }
                if (league === "bundesliga" && i === 7) {
                    break;
                }
                if (league === "azadegan" && i === 17) {
                    break;
                }
                if (league === "eredivisie" && i === 17) {
                    break;
                }
                if (league === "loshampione" && i === 17) {
                    break;
                }
                if (league === "calcio" && i === 6) {
                    break;
                }
                if (league === "laliga" && i === 6) {
                    break;
                }
                if (league === "stars_league" && i === 17) {
                    break;
                }
                if (league === "uefa_champions_league" && i === 2) {
                    break;
                }
                if (league === "europe_league" && i === 7) {
                    break;
                }
                if (league === "europe_nations_league" && i === 17) {
                    break;
                }
                let selected = "";
                if (i === 18) {
                    selected = "selected";
                }
                $("#league-table-year").append(`
                    <option ${selected} value="20${pad2(i)}-20${pad2(i + 1)}">۲۰${pad2(i).DigitsToFarsi()} - ۲۰${pad2(i + 1).DigitsToFarsi()}</option>
                `);
            }
        }
        updateLeagueTable();
    });

    $("#league-table-year").change(function () {
        updateLeagueTable();
    });

    function updateLeagueTable() {
        let league = $(".league").attr("data-league");
        let season = $("#league-table-year").val();
        $.ajax({
            url       : "league/table",
            data      : {
                "league": league,
                "season": season,
            },
            type      : "GET",
            beforeSend: function () {
                $("body").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                $(`#nav-league-table table`).remove();
                $(`#nav-league-table .group-title`).remove();
                $(`#nav-league-table`).append(`
                    <table class="standing-data-table table table-bordered table-striped">
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
                `);

                if (league === "uefa_champions_league" || league === "world_cup" || league === "europe_league"
                    || league === "afc_champions_league" || league === "europe_nations_league" || league === "uefa_euro"
                    || league === "afc_asian_cup"
                ) {
                    let temp_group = (league === "europe_nations_league") ? "1" : "A";
                    $(`#nav-league-table table`).attr("data-group", temp_group);
                    $(`#nav-league-table table`).before(`<div class="group-title">GROUP ${temp_group}</div>`);
                    data.forEach(function (item) {
                        if (item["group"] !== temp_group) {
                            $(`#nav-league-table table[data-group = ${temp_group}]`).after(`
                                <table class="table table-bordered table-striped" 
                                       data-group="${item["group"]}">
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
                            `);
                            $(`#nav-league-table table[data-group = ${item["group"]}]`).before(`<div class="group-title">GROUP ${item["group"]}</div>`);
                        }
                        temp_group = item["group"];
                        $(`#nav-league-table table[data-group = ${temp_group}] tbody`).append(`
                            <tr>
                                <td class="league-table-body-position" data-order="${item["position"]}">${String(item["position"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-team">${String(item["name"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-played" data-order="${item["played"]}">${String(item["played"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-won" data-order="${item["won"]}">${String(item["won"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-drawn" data-order="${item["drawn"]}">${String(item["drawn"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-lost" data-order="${item["lost"]}">${String(item["lost"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-for" data-order="${item["goals_for"]}">${String(item["goals_for"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-against" data-order="${item["goals_against"]}">${String(item["goals_against"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-difference" data-order="${item["goals_difference"]}">${String(item["goals_difference"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-points" data-order="${item["points"]}">${String(item["points"]).DigitsToFarsi()}</td>
                            </tr>
                        `);
                    });
                }
                else {
                    data.forEach(function (item) {
                        $(`#nav-league-table table tbody`).append(`
                            <tr>                            
                                <td class="league-table-body-position" data-order="${item["position"]}">${String(item["position"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-team">${String(item["name"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-played" data-order="${item["played"]}">${String(item["played"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-won" data-order="${item["won"]}">${String(item["won"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-drawn" data-order="${item["drawn"]}">${String(item["drawn"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-lost" data-order="${item["lost"]}">${String(item["lost"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-for" data-order="${item["goals_for"]}">${String(item["goals_for"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-against" data-order="${item["goals_against"]}">${String(item["goals_against"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-goals-difference" data-order="${item["goals_difference"]}">${String(item["goals_difference"]).DigitsToFarsi()}</td>
                                <td class="league-table-body-points" data-order="${item["points"]}">${String(item["points"]).DigitsToFarsi()}</td>
                            </tr>
                        `);
                    });
                    $("input[aria-controls^='DataTables_Table']").remove();
                    $(".standing-data-table").DataTable({
                        paging   : false,
                        info     : false,
                        language : {
                            "sEmptyTable"    : "هیچ داده ای در جدول وجود ندارد",
                            "sInfo"          : "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                            "sInfoEmpty"     : "نمایش 0 تا 0 از 0 رکورد",
                            "sInfoFiltered"  : "(فیلتر شده از _MAX_ رکورد)",
                            "sInfoPostFix"   : "",
                            "sInfoThousands" : ",",
                            "sLengthMenu"    : "نمایش _MENU_ رکورد",
                            "sLoadingRecords": "در حال بارگزاری...",
                            "sProcessing"    : "در حال پردازش...",
                            "sSearch"        : "جستجو:",
                            "sZeroRecords"   : "رکوردی با این مشخصات پیدا نشد",
                            "oPaginate"      : {
                                "sFirst"   : "ابتدا",
                                "sLast"    : "انتها",
                                "sNext"    : "بعدی",
                                "sPrevious": "قبلی",
                            },
                            "oAria"          : {
                                "sSortAscending" : ": فعال سازی نمایش به صورت صعودی",
                                "sSortDescending": ": فعال سازی نمایش به صورت نزولی",
                            },
                        },
                    });
                    let search_element = $("input[aria-controls^='DataTables_Table']")
                        .addClass('form-control')
                        .css({
                            width: '90%',
                            margin: '15px auto'
                        })
                        .attr('placeholder','جست و جو')
                        .detach();
                    search_element.insertBefore(".standing-data-table");
                    $(".dataTables_filter").remove();

                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    }

    $(".league-header-scorers").click(function () {
        let league = $(".league").attr("data-league");
        $(`#league-scorers-year`).empty();
        if (league === "world_cup") {
            for (let i = 2018; i >= 2014; i -= 4) {
                let selected = "";
                if (i === 2018) {
                    selected = "selected";
                }
                $("#league-scorers-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "uefa_euro") {
            for (let i = 2016; i >= 2012; i -= 4) {
                let selected = "";
                if (i === 2016) {
                    selected = "selected";
                }
                $("#league-scorers-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "afc_asian_cup") {
            for (let i = 2019; i >= 2015; i -= 4) {
                let selected = "";
                if (i === 2019) {
                    selected = "selected";
                }
                $("#league-scorers-year").append(`
                    <option ${selected} value="${i > 2004 ? i : i + 1}">${String(i > 2004 ? i : i + 1).DigitsToFarsi()}</option>
                `);
            }
        }
        else if (league === "afc_champions_league") {
            for (let i = 2018; i >= 2014; i--) {
                let selected = "";
                if (i === 2018) {
                    selected = "selected";
                }
                $("#league-scorers-year").append(`
                    <option ${selected} value="${i}">${String(i).DigitsToFarsi()}</option>
                `);
            }
        }
        else {
            for (let i = 18; i > 0; i--) {
                if (league === "khaligefars" && i === 0) {
                    break;
                }
                if (league === "premier_league" && i === 11) {
                    break;
                }
                if (league === "bundesliga" && i === 11) {
                    break;
                }
                if (league === "azadegan" && i === 17) {
                    break;
                }
                if (league === "eredivisie" && i === 11) {
                    break;
                }
                if (league === "loshampione" && i === 11) {
                    break;
                }
                if (league === "calcio" && i === 11) {
                    break;
                }
                if (league === "laliga" && i === 11) {
                    break;
                }
                if (league === "stars_league" && i === 17) {
                    break;
                }
                if (league === "uefa_champions_league" && i === 11) {
                    break;
                }
                if (league === "europe_league" && i === 11) {
                    break;
                }
                if (league === "europe_nations_league" && i === 17) {
                    break;
                }
                let selected = "";
                if (i === 18) {
                    selected = "selected";
                }
                $("#league-scorers-year").append(`
                    <option ${selected} value="20${pad2(i)}-20${pad2(i + 1)}">۲۰${pad2(i).DigitsToFarsi()} - ۲۰${pad2(i + 1).DigitsToFarsi()}</option>
                `);
            }
        }
        updateLeagueScorers();
    });

    $("#league-scorers-year").change(function () {
        updateLeagueScorers();
    });

    function updateLeagueScorers() {
        let league = $(".league").attr("data-league");
        let season = $("#league-scorers-year").val();
        $.ajax({
            url       : "league/scorers",
            data      : {
                "league": league,
                "season": season,
            },
            type      : "GET",
            beforeSend: function () {
                $("body").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                $(`#nav-league-scorers table`).remove();
                let rank = 0;
                $(`#nav-league-scorers`).append(`
                    <table class="scorers-data-table table table-bordered table-striped">
                        <thead class="thead-default">
                        <tr>
                            <th class="league-scorers-title-position">
                                #
                            </th>
                            <th class="league-scorers-title-name">
                                نام بازیکن
                            </th>
                            <th class="league-scorers-title-club">
                                باشگاه
                            </th>
                            <th class="league-scorers-title-goals">
                                تعداد گل
                            </th>
                            <th class="league-scorers-title-goals">
                                تعداد پاس گل
                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                `);
                data.forEach(function (item) {
                    rank++;
                    $(`#nav-league-scorers table tbody`).append(`
                        <tr>
                            <td class="league-scores-body-position" data-order="${rank}">${String(rank).DigitsToFarsi()}</td>
                            <td class="league-scores-body-name"
                                data-toggle="tooltip"
                                data-placement="top" 
                                title="${item["en_name"]}">
                                ${String(item["fa_name"]).DigitsToFarsi()}
                            </td>
                            <td class="league-scores-body-club"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="${item["en_club"]}">
                                ${String(item["fa_club"]).DigitsToFarsi()}
                            </td>
                            <td class="league-scores-body-goals" data-order="${item["count_scores"]}">${String(item["count_scores"]).DigitsToFarsi()}</td>
                            <td class="league-scores-body-assists" data-order="${item["count_assists"] ? item["count_assists"] : 0}">${String(item["count_assists"] ? item["count_assists"] : "-").DigitsToFarsi()}</td>
                        </tr>
                    `);
                });
                $("input[aria-controls^='DataTables_Table']").remove();
                $(".scorers-data-table").DataTable({
                    paging   : false,
                    info     : false,
                    language : {
                        "sEmptyTable"    : "هیچ داده ای در جدول وجود ندارد",
                        "sInfo"          : "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                        "sInfoEmpty"     : "نمایش 0 تا 0 از 0 رکورد",
                        "sInfoFiltered"  : "(فیلتر شده از _MAX_ رکورد)",
                        "sInfoPostFix"   : "",
                        "sInfoThousands" : ",",
                        "sLengthMenu"    : "نمایش _MENU_ رکورد",
                        "sLoadingRecords": "در حال بارگزاری...",
                        "sProcessing"    : "در حال پردازش...",
                        "sSearch"        : "جستجو:",
                        "sZeroRecords"   : "رکوردی با این مشخصات پیدا نشد",
                        "oPaginate"      : {
                            "sFirst"   : "ابتدا",
                            "sLast"    : "انتها",
                            "sNext"    : "بعدی",
                            "sPrevious": "قبلی",
                        },
                        "oAria"          : {
                            "sSortAscending" : ": فعال سازی نمایش به صورت صعودی",
                            "sSortDescending": ": فعال سازی نمایش به صورت نزولی",
                        },
                    },
                });
                let search_element = $("input[aria-controls^='DataTables_Table']")
                    .css({
                        width: '90%',
                        margin: '15px auto'
                    })
                    .addClass('form-control')
                    .attr('placeholder','جست و جو')
                    .detach();
                search_element.insertBefore(".scorers-data-table");
                $(".dataTables_filter").remove();
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    }

    $(".league-header-plan").click(function () {
        let league = $(".league").attr("data-league");
        $.ajax({
            url       : "league/fixtures",
            type      : "GET",
            data      : {
                "league": league,
            },
            beforeSend: function () {
                $("body").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                $(`#nav-league-plan ul`).empty();
                data["fixtures"].forEach(function (item) {
                    $(`#nav-league-plan ul`).append(`
                        <li class="list-group-item league-body-plan-item">
                            <div class="league-body-plan-item-match">
                                <div class="league-body-plan-item-match-host">
                                    <div class="league-body-plan-item-match-host-count-goals">${item["hostPoint"]}</div>
                                    ${item["host"]}
                                </div>
                                <div class="league-body-plan-item-match-guest">
                                    <div class="league-body-plan-item-match-guest-count-goals">${item["guestPoint"]}</div>
                                    ${item["guest"]}
                                </div>
                            </div>
                            <div class="league-body-plan-item-dates">${item["datetime"]}</div>
                        </li>
                    `);
                });
                $(`#league-plan-select-week-result`).empty();
                for (let i = 1; i <= data['countWeeks']; i++) {
                    let selected = "";
                    if (i === data["currentWeek"]) {
                        selected = "selected";
                    }
                    $(`#league-plan-select-week-result`).append(`
                        <option value="${i}" ${selected}>${i}</option>
                    `);
                }
                $(`#league-plan-select-team-result`).empty();
                $(`#league-plan-select-team-result`).append(`
                    <option value="please-select">تیم را انتخاب کنید</option>
                `);
                data["clubs"].forEach(function (club) {
                    $(`#league-plan-select-team-result`).append(`
                        <option value="${club}">${club}</option>
                    `);
                });
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $(".league-plan-select-result select").click(function () {
        let league     = $(".league").attr("data-league");
        let filterType = $(this).attr("data-type");
        $.ajax({
            url       : "league/fixtures",
            type      : "GET",
            data      : {
                "league": league,
                "filter": {
                    type : filterType,
                    value: $(this).val(),
                },
            },
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                $(`#nav-league-plan ul`).empty();
                data["fixtures"].forEach(function (item) {
                    $(`#nav-league-plan ul`).append(`
                        <li class="list-group-item league-body-plan-item">
                            <div class="league-body-plan-item-match">
                                <div class="league-body-plan-item-match-host">
                                    <div class="league-body-plan-item-match-host-count-goals">${item["hostPoint"]}</div>
                                    ${item["host"]}
                                </div>
                                <div class="league-body-plan-item-match-guest">
                                    <div class="league-body-plan-item-match-guest-count-goals">${item["guestPoint"]}</div>
                                    ${item["guest"]}
                                </div>
                            </div>
                            <div class="league-body-plan-item-dates">${item["datetime"]}</div>
                        </li>
                    `);
                });
                $(`#league-plan-select-week-result`).empty();
                for (let i = 1; i <= data['countWeeks']; i++) {
                    let selected = "";
                    if (i == data["selectedWeek"]) {
                        selected = "selected";
                    }
                    $(`#league-plan-select-week-result`).append(`
                        <option value="${i}" ${selected}>${i}</option>
                    `);
                }
                $(`#league-plan-select-team-result`).empty();
                $(`#league-plan-select-team-result`).append(`
                    <option value="please-select">تیم را انتخاب کنید</option>
                `);
                data["clubs"].forEach(function (club) {
                    let selected = "";
                    if (club === data["selectedClub"]) {
                        selected = "selected";
                    }
                    $(`#league-plan-select-team-result`).append(`
                        <option value="${club}" ${selected}>${club}</option>
                    `);
                });
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $(".league-plan-select select").click(function () {
        let league = $(".league").attr("data-league");
        if ($(this).val() === "team") {
            $(`#league-plan-select-team-result`).show();
            $(`#league-plan-select-week-result`).hide();
        }
        if ($(this).val() === "week") {
            $(`#league-plan-select-week-result`).show();
            $(`#league-plan-select-team-result`).hide();
        }
    });

    $(".league-nav-item-wrapper button").click(function () {
        $(this).parent().find(".active").removeClass("active");
        $(this).addClass("active");
        let league = $(this).attr("data-league");
        let tag    = $(this).attr("data-tag");
        $(".league").attr("data-league", league);
        $.ajax({
            url       : "league/news",
            type      : "GET",
            data      : {
                "tag"   : tag,
                "league": league,
            },
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                $(".league").attr("data-offset", 15);
                $(".league").attr("data-tag", "all");
                document.getElementsByClassName("league")[0].scrollTop = 0;
                $(".league").attr("data-status", "INCOMPLETE");

                $("#nav-news ul").empty();
                data["news"].forEach(function (item) {
                    $(`#nav-news ul`).append(`
                        <a href="${item["url"]}">
                            <li class="list-group-item league-body-item">
                                <span class="time" data-to-farsi>
                                    ${String(item["time"]).DigitsToFarsi()}
                                </span>
                                ${item["title"]}
                            </li>
                        </a>
                    `);
                });

                $(".league-body-clubs").empty();
                if (data["categories"].length) {
                    $(`.league-body-clubs`).append(`
                        <div class="custom-active" data-tag="all">همه</div>
                    `);
                }
                data["categories"].forEach(function (item) {
                    $(`.league-body-clubs`).append(`
                        <div data-tag="${item["tag"]}">${item["name"]}</div>
                    `);
                });

                $("#nav-tab-content").find(".active").removeClass("active");
                $("#nav-tab-content").find(".show").removeClass("show");
                $("#nav-news").addClass("active");
                $("#nav-news").addClass("show");

                $(".league-header-scope").find(".active").removeClass("active");
                $(".league-header-news").addClass("active");

            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $(document).on("click", ".league-body-clubs div", function () {
        $(this).parent().find("div.custom-active").removeClass("custom-active");
        $(this).addClass("custom-active");
        let league = $(".league").attr("data-league");
        let tag    = $(this).attr("data-tag");
        $.ajax({
            url       : "league/news",
            type      : "GET",
            data      : {
                "tag"   : tag,
                "league": league,
            },
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                document.getElementsByClassName("league")[0].scrollTop = 0;
                $(".league").attr("data-status", "INCOMPLETE");
                $(".league").attr("data-offset", 15);
                $(".league").attr("data-tag", tag);

                $("#nav-news ul").empty();
                data["news"].forEach(function (item) {
                    $(`#nav-news ul`).append(`
                        <a href="${item["url"]}">
                            <li class="list-group-item league-body-item">
                                <span class="time" data-to-farsi>
                                    ${item["time"]}
                                </span>
                                ${item["title"]}
                            </li>
                        </a>
                    `);
                });
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $("#previous-survey").click(function () {
        let survey_id = parseInt($(".survey-body").attr("data-survey-id")) - 1;
        showSurvey(survey_id);
    });

    $("#next-survey").click(function () {
        let survey_id = parseInt($(".survey-body").attr("data-survey-id")) + 1;
        showSurvey(survey_id);
    });

    function showSurvey(survey_id) {
        $(".survey-chart").hide();
        $(".survey-body").show();
        $(".results").show();
        $(".show-survey").hide();
        $.ajax({
            url       : `surveys/show/${survey_id}`,
            type      : "GET",
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                $(".survey-body").attr("data-survey-id", survey_id);
                $(".survey-body").empty();
                $(`.survey-body`).append(`
                    <h5 class="question">${data["question"]}
                        <div class="question-number">${String(data["id"]).DigitsToFarsi()}</div>
                    </h5>
                `);
                data["options"].forEach(function (item, index) {
                    $(`.survey-body`).append(`
                        <h6 class="option ${item["selected"] ? "selected" : ""}"
                            data-index="${index + 1}"
                            data-title="${item["title"]}"
                            data-count="${item["count"]}"
                            data-to-farsi>${String(index + 1).DigitsToFarsi()}- ${item["title"]}</h6>
                    `);
                });
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    }

    $(document).on("click", ".survey-body .option", function () {
        $this = $(this);
        $.ajax({
            url       : `surveys/vote`,
            type      : "POST",
            data      : {
                _token         : $("meta[name='csrf-token']").attr("content"),
                option_selected: $(this).attr("data-index"),
                survey_id      : parseInt($(".survey-body").attr("data-survey-id")),
            },
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                $this.parent().find(".selected").removeClass("selected");
                $this.addClass("selected");
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    });

    $(document).on("click", ".survey-footer .results", function () {
        $(".survey-chart").show();
        $(".survey-body").hide();
        $(".results").hide();
        $(".show-survey").show();
        $(".add-survey").hide();
        $("#add-survey").html("+");
        $("#add-survey").attr("data-status", "close");
        let labels     = [];
        let data       = [];
        let background = [];
        $(".survey-body .option").each(function () {
            labels.push($(this).attr("data-title"));
            data.push($(this).attr("data-count"));
            background.push(randColor());
        });
        console.log(labels);
        console.log(data);
        const chart = require("chart.js");
        new chart(document.getElementsByTagName("canvas")[0].getContext("2d"), {
            type   : "bar",
            data   : {
                labels  : labels,
                datasets: [
                    {
                        data           : data,
                        backgroundColor: background,
                        borderWidth    : 1,
                    },
                ],
            },
            options: {
                tooltips: {
                    mode: "x",
                },
                scales  : {
                    yAxes: [
                        {
                            scaleLabel: {
                                display    : true,
                                labelString: "تعداد رای",
                                fontSize   : 14,
                            },
                            ticks     : {
                                beginAtZero: true,
                                callback   : function (value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                },
                            },
                        },
                    ],
                    xAxes: [
                        {
                            scaleLabel: {
                                display    : false,
                                labelString: "گزینه",
                                fontSize   : 14,
                            },
                        },
                    ],
                },
                legend  : {
                    labels: [],
                },
            },
        });
        chart.defaults.global.defaultFontFamily = "IRANSANS";
    });

    $(document).on("click", ".survey-footer .show-survey", function () {
        $(".survey-chart").hide();
        $(".survey-body").show();
        $(".results").show();
        $(".show-survey").hide();
        $(".add-survey").hide();
        $("#add-survey").html("+");
        $("#add-survey").attr("data-status", "close");
    });

    function randColor() {
        return "rgba(" + (Math.floor(Math.random() * 255) + 1) + ", " + (Math.floor(Math.random() * 255) + 1) + ", " + (Math.floor(Math.random() * 255) + 1) + ", 0.9)";
    }

    $("#add-survey").click(function () {
        if ($(this).attr("data-status") === "close") {
            $(this).html("&times;");
            $(this).attr("data-status", "open");
            $(".add-survey").show();
            $(".survey-body").hide();
            $(".survey-chart").hide();
        }
        else if ($(this).attr("data-status") === "open") {
            $(this).html("+");
            $(this).attr("data-status", "close");
            $(".add-survey").hide();
            $(".survey-body").show();
            $(".survey-chart").hide();
        }
    });

    $(".add-survey-option").click(function () {
        $(this).before(`
            <h6 class="option">
                <input type="text"
                       style="width: 100%;text-indent: 10px;"
                       name="options[]"
                       placeholder="گزینه">
            </h6>
        `);
    });

    $(".post-instagram-footer").click(function () {
        $this      = $(this);
        let offset = $this.find("div").attr("data-offset");
        $.ajax({
            url       : `/posts/user-contents/instagram`,
            data      : {
                "offset": offset,
            },
            type      : "GET",
            beforeSend: function () {
                $("body").prepend(`
                <div class="before-send">
                    <img src="/images/loader.gif">
                </div>
            `);
            },
            success   : function (data) {
                $this.find("div").attr("data-offset", parseInt(offset) + data.length);
                let loop = 0;
                data.forEach(function (item) {
                    let first_post_padding = "pr-sm-2";
                    if (!loop % 5) {
                        first_post_padding = "pr-sm-0";
                    }
                    loop++;
                    let like = item.isLiked ? 'post-liked' : '';
                    let dislike = item.isDisliked ? 'post-disliked' : '';
                    $(`.post-instagram-body-ajax`).find(".row").append(`
                        <div class="col-12 col-sm-6 col-md-6 col-lg-2 pt-2 pb-2 pl-0 pl-sm-3 pr-0 ${first_post_padding}">
                            <div class="post-instagram-wrapper mt-1"
                                 data-post-id="${item.id}">
                                <a href="${item.url}">
                                    <span class="post-layout"></span>
                                </a>
                                <img class="post-instagram-img lazyload"
                                     src="${item.mainPhoto}" />
                                <a href="${item.authorUrl}" class="post-instagram-author">
                                    <i class="fas fa-pencil-alt"></i>
                                    ${item.authorName}
                                </a>
                                <div class="post-instagram-like-wrapper">
                                    <div class="post-instagram-like ${like}">
                                        <span class="count-like">${item.countLike}</span>
                                        <i class="far fa-thumbs-up"></i>
                                    </div>
                                    <div class="post-instagram-dislike ${dislike}">
                                        <span class="count-dislike">${item.countDislike}</span>
                                        <i class="far fa-thumbs-down"></i>
                                    </div>
                                </div>
                                <a href="${item.url}">
                                    <div class="post-instagram-caption">
                                        ${item.title}
                                    </div>
                                </a>
                            </div>
                        </div>
                    `);
                });
                if (!data.length) {
                    $this.remove();
                }
            },
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 500);
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    });

    infiniteScroll("#nav-tab-content-last-news", "lastNewsInfiniteScroll");

    infiniteScroll(".league", "leagueNewsInfiniteScroll");

    let Handler = {};

    Handler.lastNewsInfiniteScroll = function ($this, offset) {
        $.ajax({
            url       : `/posts/news/last`,
            data      : {
                "offset": offset,
            },
            type      : "GET",
            beforeSend: function () {
                $this.append("<p class='infinite-loading'><img src='/images/loading_dots.gif'></p>");
            },
            success   : function (data) {
                $this.attr("data-offset", parseInt(offset) + data.length);
                data.forEach(function (item) {
                    $this.append(`
                        <a href="${item["url"]}">
                            <li class="list-group-item news-body-item">
                                <span class="time">
                                    ${String(item["time"]).DigitsToFarsi()}
                                </span>
                                ${item["title"]}
                            </li>
                        </a>
                    `);
                });
                if (!data.length) {
                    $this.attr("data-status", "DONE");
                }
            },
            complete  : function () {
                $(".infinite-loading").remove();
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    };

    Handler.leagueNewsInfiniteScroll = function ($this, offset) {
        let league = $this.attr("data-league");
        let tag    = $this.attr("data-tag");
        $.ajax({
            url       : `/league/news`,
            data      : {
                "offset": offset,
                "tag"   : tag,
                "league": league,
            },
            type      : "GET",
            beforeSend: function () {
                $this.append("<p class='infinite-loading'><img src='/images/loading_dots.gif'></p>");
            },
            success   : function (data) {
                $this.attr("data-offset", parseInt(offset) + data["news"].length);
                data["news"].forEach(function (item) {
                    $("#nav-news ul").append(`
                        <a href="${item["url"]}">
                            <li class="list-group-item league-body-item">
                                <span class="time" data-to-farsi>
                                    ${String(item["time"]).DigitsToFarsi()}
                                </span>
                                ${item["title"]}
                            </li>
                        </a>
                    `);
                });
                if (!data["news"].length) {
                    $this.attr("data-status", "DONE");
                }
            },
            complete  : function () {
                $(".infinite-loading").remove();
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    };

    function infiniteScroll(element, callback) {
        $(`${element}`).scroll(function () {

            if (element === ".league") {
                if ($(".league .nav-tabs .active").attr("class") !== "league-header-news active show") {
                    if ($(".league .nav-tabs .active").attr("class") !== "league-header-news show active") {
                        return;
                    }
                }
            }

            let $this           = $(this);
            let offset          = $this.attr("data-offset");
            let height          = this.scrollHeight - $this.height(); // Get the height of the div
            let scroll          = $this.scrollTop(); // Get the vertical scroll position
            let isScrolledToEnd = (scroll >= height);
            if (isScrolledToEnd) {

                if ($(`${element}`).attr("data-status") !== "DONE") {
                    Handler[callback]($this, offset);
                }
            }
        });
    }

    $("#news-footer").click(function () {
        let $this = $("#nav-tab-content-last-news");
        let offset = $this.attr("data-offset");
        $.ajax({
            url       : `/posts/news/last`,
            data      : {
                "offset": offset,
            },
            type      : "GET",
            beforeSend: function () {
                $this.append("<p class='infinite-loading'><img src='/images/loading_dots.gif'></p>");
            },
            success   : function (data) {
                $this.attr("data-offset", parseInt(offset) + data.length);
                data.forEach(function (item) {
                    $this.append(`
                        <a href="${item["url"]}">
                            <li class="list-group-item news-body-item">
                                <span class="time">
                                    ${String(item["time"]).DigitsToFarsi()}
                                </span>
                                ${item["title"]}
                            </li>
                        </a>
                    `);
                });
                if (!data.length) {
                    $("#news-footer").remove();
                }
            },
            complete  : function () {
                $(".infinite-loading").remove();
            },
            error     : function (data) {
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
                else {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.responseJSON.message,
                        "error",
                        10,
                    ).dismissOthers();
                }
            },
        });
    });

    $("#broadcast-schedule-add").click(function () {
        if ($(this).attr('data-status') === 'add'){
            $(this).attr('data-status','show');
            $(".broadcast-schedule-body").children().first().remove();
        }
        else {
            $(this).attr('data-status','add');
            $(".broadcast-schedule-body").prepend(
                `<form method="post" 
                       class="broadcast-schedule-form"
                       action="/broadcast-schedule/store">
                    <div class="container broadcast-schedule-item">
                        <div class="row">
                            <div class="col-3 col-sm-3 p-0">
                                <div class="broadcast-schedule-item-image broadcast-schedule-item-image-select"
                                     data-status="choice"
                                     style="cursor: pointer">
                                    <img src="/images/camera.png"
                                         class="img-fluid"
                                         title="شبکه را انتخاب کنید">
                                    <input hidden
                                           id="broadcast-schedule-image-input"
                                           name="broadcast_channel">
                                </div>
                            </div>
                            <div class="col-9 col-sm-9 pr-0 pl-1">
                                <div class="match-title">
                                    <input class="broadcast-schedule-host" name="host" placeholder="میزبان">
                                    :
                                    <input class="broadcast-schedule-guest" name="guest" placeholder="میهمان"></div>
                                <div class="match-time">
                                    <div class="input-group">
                                        <div class="input-group-prepend broadcast-schedule-calender">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <input class="form-control broadcast-schedule-time-input"
                                               placeholder="تاریخ">
                                        <input class="form-control broadcast-schedule-time-input-alt" hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <button type="submit"
                                            class="btn btn-sm btn-block text-white mt-1 mb-1 broadcast-schedule-submit">ثبت
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>`
            );
            $(".broadcast-schedule-calender").click(function () {
                $(".broadcast-schedule-time-input").trigger('click');
            });
            $(".broadcast-schedule-time-input").persianDatepicker({
                altField  : ".broadcast-schedule-time-input-alt",
                format : "dddd ، D MMMM - ساعت: HH:mm",
                timePicker: {
                    enabled: true,
                    second : {
                        enabled: false,
                    },
                },
            });
            $(".broadcast-schedule-item-image-select").click(function () {
                if ($(this).attr('data-status') === 'choice' || $(this).attr('data-status') === 'lenz'){
                    $(this).attr('data-status','tv3');
                    $(this).find('img').attr('src','/images/tv3.png');
                    $("#broadcast-schedule-image-input").val('tv3');
                }
                else if ($(this).attr('data-status') === 'tv3'){
                    $(this).attr('data-status','varzesh');
                    $(this).find('img').attr('src','/images/varzesh.png');
                    $("#broadcast-schedule-image-input").val('varzesh');
                }
                else if ($(this).attr('data-status') === 'varzesh'){
                    $(this).attr('data-status','anten');
                    $(this).find('img').attr('src','/images/anten.png');
                    $("#broadcast-schedule-image-input").val('anten');
                }
                else if ($(this).attr('data-status') === 'anten'){
                    $(this).attr('data-status','lenz');
                    $(this).find('img').attr('src','/images/lenz.png');
                    $("#broadcast-schedule-image-input").val('lenz');
                }
            });
            $(".broadcast-schedule-form").submit(function (e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    type   : "POST",
                    url    : form.attr("action"),
                    data   : $.param({
                        _token  : $("meta[name='csrf-token']").attr("content"),
                        datetime: $(".broadcast-schedule-time-input-alt").val(),
                    }) + "&" + form.serialize(),
                    success: function (data) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            data.message,
                            "success",
                            10,
                        ).dismissOthers();
                        $("#broadcast-schedule-add").attr('data-status','show');
                        $(".broadcast-schedule-body").children().first().remove();
                        $(".broadcast-schedule-body").append(`
                            <div class="container broadcast-schedule-item"
                                 data-id="${data.id}"
                                 data-datetime="${data.datetime}"
                                 data-host="${data.host}"
                                 data-guest="${data.guest}"
                                 data-channel-broadcast="${data.alt}"
                            >
                                <span class="broadcast-schedule-delete text-danger"
                                      data-id="${data.id}"
                                      id="broadcast-schedule-delete"
                                      title="حذف">&times;
                                </span>
                                <form method="post"
                                      action="/broadcast-schedule/destroy/${data.id}"
                                      class="broadcast-schedule-delete-form"
                                      data-id="${data.id}"
                                      hidden>
                                      <input hidden name="_token" value="${document.head.querySelector("meta[name='csrf-token']").content}">
                                      <input hidden name="_method" value="delete">
                                </form>
                                <span class="broadcast-schedule-edit text-success"
                                      data-id="${data.id}"
                                      id="broadcast-schedule-edit"
                                      title="ویرایش"><i class="fa fa-pencil-alt"></i>
                                </span>
                                <div class="row">
                                    <div class="col-3 col-sm-3 p-0">
                                        <div class="broadcast-schedule-item-image">
                                            <img src="${data.image}"
                                                 class="img-fluid"
                                                 alt="${data.alt}"
                                                 title="${data.alt}">
                                        </div>
                                    </div>
                                    <div class="col-9 col-sm-9 pr-0 pl-1">
                                        <div class="match-title">${data.title}</div>
                                        <div class="match-time" data-to-farsi>${data.time}</div>
                                    </div>
                                </div>
                            </div>
                        `);
                    },
                    error  : function (data) {
                        if (data.status === 422) {
                            window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                            Object.values(data.responseJSON.errors).forEach(function (item) {
                                window.alertify.set("notifier", "position", "bottom-left");
                                window.alertify.notify(
                                    item[0],
                                    "error",
                                    10,
                                );
                            });
                        }
                        if (data.status === 403) {
                            window.alertify.set("notifier", "position", "bottom-left");
                            window.alertify.notify(
                                'شما دسترسی لازم برای انجام این عملیات را ندارید.',
                                "error",
                                10,
                            ).dismissOthers();
                        }
                    },
                });
            });
        }
    });

    $(document).on('click','.broadcast-schedule-delete',function () {
        let $this = $(this);
        window.alertify.alert().setting("closable");
        window.alertify.confirm("آیا از حذف این مورد مطمئن هستید؟")
            .set("defaultFocus", "لغو")
            .setHeader("هشدار !")
            .set("onok", function () {
                $("html").prepend(
                    `
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);
                let form = $(`.broadcast-schedule-delete-form[data-id="${$this.attr('data-id')}"]`);
                $.ajax({
                    type    : "POST",
                    url     : form.attr("action"),
                    data    : form.serialize(),
                    success : function (data) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            data.message,
                            "success",
                            10,
                        ).dismissOthers();
                        $this.parent().remove();
                    },
                    error   : function (data) {
                        if (data.status === 422) {
                            window.alertify.notify("", "error", 0.00000000001).dismissOthers();
                            Object.values(data.responseJSON.errors).forEach(function (item) {
                                window.alertify.set("notifier", "position", "bottom-left");
                                window.alertify.notify(
                                    item[0],
                                    "error",
                                    10,
                                );
                            });
                        }
                        if (data.status === 403) {
                            window.alertify.set("notifier", "position", "bottom-left");
                            window.alertify.notify(
                                "شما دسترسی لازم برای انجام این عملیات را ندارید.",
                                "error",
                                10,
                            ).dismissOthers();
                        }
                    },
                    complete: function () {
                        _.delay(function () {
                            $(".before-send").remove();
                        }, 500);
                    },
                });
            });
        $(".ajs-ok").text("تایید");
        $(".ajs-ok").addClass("btn btn-default");
        $(".ajs-cancel").text("لغو");
        $(".ajs-cancel").addClass("btn btn-default");
    })

    window.match_title = [];
    window.match_time = [];
    $(document).on('click','.broadcast-schedule-edit',function () {
        let item = $(`.broadcast-schedule-item[data-id="${$(this).attr('data-id')}"]`);
        item.find('.row').wrap(`
            <form method="post"
                  class="broadcast-schedule-form-update"
                  action="/broadcast-schedule/update/${$(this).attr('data-id')}">
            </form>
        `);
        $(item).find(".broadcast-schedule-edit").hide();
        $(item).find(".broadcast-schedule-delete").hide();
        $(item).find(".broadcast-schedule-item-image").after(`
            <input hidden
                   class="broadcast-schedule-image-input-edit"
                   value="${$(item).attr("data-channel-broadcast")}"
                   name="broadcast_channel">
        `);
        $(item).find(".broadcast-schedule-item-image").click(function () {
            if ($(this).find('img').attr('alt') === 'choice' || $(this).find('img').attr('alt') === 'lenz'){
                $(this).find('img').attr('alt','tv3');
                $(this).find('img').attr('src','/images/tv3.png');
                $(item).find(".broadcast-schedule-image-input-edit").val('tv3');
            }
            else if ($(this).find('img').attr('alt') === 'tv3'){
                $(this).find('img').attr('alt','varzesh');
                $(this).find('img').attr('src','/images/varzesh.png');
                $(item).find(".broadcast-schedule-image-input-edit").val('varzesh');
            }
            else if ($(this).find('img').attr('alt') === 'varzesh'){
                $(this).find('img').attr('alt','anten');
                $(this).find('img').attr('src','/images/anten.png');
                $(item).find(".broadcast-schedule-image-input-edit").val('anten');
            }
            else if ($(this).find('img').attr('alt') === 'anten'){
                $(this).find('img').attr('alt','lenz');
                $(this).find('img').attr('src','/images/lenz.png');
                $(item).find(".broadcast-schedule-image-input-edit").val('lenz');
            }
        });
        $(item).find('.row').append(`
            <div class="col-6">
                <div class="input-group">
                    <button type="submit"
                            class="btn btn-sm btn-block text-white mt-1 mb-1 broadcast-schedule-edit-submit">ویرایش
                    </button>
                </div>
            </div>
            <div class="col-6">
                <div class="input-group">
                    <button type="button"
                            class="btn btn-sm btn-block text-white mt-1 mb-1 btn-warning broadcast-schedule-edit-cancel">بازگشت
                    </button>
                </div>
            </div>
        `);
        window.match_title[$(item).attr('data-id')] = $(item).find(".match-title").clone();
        $(item).find(".match-title").empty();
        $(item).find(".match-title").html(`
            <input class="broadcast-schedule-host" name="host" placeholder="میزبان" value="${$(item).attr('data-host')}">
            :
            <input class="broadcast-schedule-guest" name="guest" placeholder="میهمان" value="${$(item).attr('data-guest')}"></div>
        `);
        window.match_time[$(item).attr('data-id')] = $(item).find(".match-time").clone();
        $(item).find(".match-time").empty();
        $(item).find(".match-time").html(`
            <div class="input-group">
                <div class="input-group-prepend broadcast-schedule-calender">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <input class="form-control broadcast-schedule-time-input-edit"
                       value="${$(item).attr('data-datetime')}"
                       placeholder="تاریخ">
                <input class="form-control broadcast-schedule-time-input-edit-alt" hidden>
            </div>
        `);
        $(".broadcast-schedule-calender").click(function () {
            $(".broadcast-schedule-time-input").trigger('click');
        });
        $(".broadcast-schedule-time-input-edit").persianDatepicker({
            altField  : ".broadcast-schedule-time-input-edit-alt",
            format : "dddd ، D MMMM - ساعت: HH:mm",
            timePicker: {
                enabled: true,
                second : {
                    enabled: false,
                },
            },
        });
        $(item).find(".broadcast-schedule-form-update").submit(function (e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                type   : "POST",
                url    : form.attr("action"),
                data   : $.param({
                    _token  : $("meta[name='csrf-token']").attr("content"),
                    _method  : 'put',
                    datetime: $(".broadcast-schedule-time-input-edit-alt").val(),
                }) + "&" + form.serialize(),
                success: function (data) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        data.message,
                        "success",
                        10,
                    ).dismissOthers();
                    $(item).find(".broadcast-schedule-item-image").unbind( "click" );
                    $(item).find(".broadcast-schedule-edit").show();
                    $(item).find(".broadcast-schedule-delete").show();
                    $(item).find(".match-title").after(window.match_title[$(item).attr('data-id')]);
                    $(item).find(".match-title").first().remove();
                    $(item).find(".match-time").after(window.match_time[$(item).attr('data-id')]);
                    $(item).find(".match-time").first().remove();
                    window.match_time[$(item).attr('data-id')] = null;
                    window.match_title[$(item).attr('data-id')] = null;
                    $(item).find(".broadcast-schedule-edit-submit").parent().parent().remove();
                    $(item).find(".broadcast-schedule-edit-cancel").parent().parent().remove();
                    item.find('.row').unwrap();

                    $(item).find(".match-title").text(data.title);
                    $(item).find(".match-time").text(data.time);
                    $(item).attr("data-datetime",data.datetime);
                    $(item).attr("data-host",data.host);
                    $(item).attr("data-guest",data.guest);
                    $(item).attr("data-channel-broadcast",data.alt);
                },
                error  : function (data) {
                    if (data.status === 422) {
                        window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                        Object.values(data.responseJSON.errors).forEach(function (item) {
                            window.alertify.set("notifier", "position", "bottom-left");
                            window.alertify.notify(
                                item[0],
                                "error",
                                10,
                            );
                        });
                    }
                    if (data.status === 403) {
                        window.alertify.set("notifier", "position", "bottom-left");
                        window.alertify.notify(
                            'شما دسترسی لازم برای انجام این عملیات را ندارید.',
                            "error",
                            10,
                        ).dismissOthers();
                    }
                },
            });
        });

        $(item).find(".broadcast-schedule-edit-cancel").click(function () {
            $(item).find(".broadcast-schedule-item-image").unbind( "click" );
            $(item).find(".broadcast-schedule-edit").show();
            $(item).find(".broadcast-schedule-delete").show();
            $(item).find(".match-title").after(window.match_title[$(item).attr('data-id')]);
            $(item).find(".match-title").first().remove();
            $(item).find(".match-time").after(window.match_time[$(item).attr('data-id')]);
            $(item).find(".match-time").first().remove();
            window.match_time[$(item).attr('data-id')] = null;
            window.match_title[$(item).attr('data-id')] = null;
            $(item).find(".broadcast-schedule-edit-submit").parent().parent().remove();
            $(item).find(".broadcast-schedule-edit-cancel").parent().parent().remove();
            item.find('.row').unwrap();
        });
    })

    $(document).on("click", ".post-instagram-like", function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/user-contents/like",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $this.parent().parent().attr("data-post-id"),
                type  : 'user-contents',
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.addClass("post-liked");
                    $this.find(".count-like").text(data["like"]);
                    $this.parent().find(".post-instagram-dislike").removeClass("post-disliked");
                    $this.parent().find(".count-dislike").text(data["dislike"]);
                }
            },
            error  : function (data) {
                if (data.status === 401) {
                    window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "برای انجام این کار ابتدا ثبت نام کنید",
                        "error",
                        10,
                    );
                }
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });

    $(document).on("click", ".post-instagram-dislike", function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/user-contents/dislike",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $this.parent().parent().attr("data-post-id"),
                type  : 'user-contents',
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.addClass("post-disliked");
                    $this.find(".count-dislike").text(data["dislike"]);
                    $this.parent().find(".post-instagram-like").removeClass("post-liked");
                    $this.parent().find(".count-like").text(data["like"]);
                }
            },
            error  : function (data) {
                if (data.status === 401) {
                    window.alertify.notify('', "error", 0.00000000001).dismissOthers();
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "برای انجام این کار ابتدا ثبت نام کنید",
                        "error",
                        10,
                    );
                }
                if (data.status === 429) {
                    window.alertify.set("notifier", "position", "bottom-left");
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        "error",
                        5,
                    ).dismissOthers();
                }
            },
        });
    });


});