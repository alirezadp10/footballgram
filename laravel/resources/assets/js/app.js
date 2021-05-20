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
    window.alertify.set("notifier", "position", "bottom-left");

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

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo";

// window.Pusher = require("pusher-js");

// window.Echo = new Echo({
//     broadcaster: "pusher",
//     key        : process.env.MIX_PUSHER_APP_KEY,
//     cluster    : process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted  : true,
// });

// window.Pusher.logToConsole = true;

// window.Echo.private(`App.User.${AuthID}`).notification((data) => {
//
//     $(".notifications-badge-counter").css({
//         "background": "red",
//     });
//
//     $(".notifications-badge-counter").html(function () {
//         return (isNaN(parseInt($(this).text().FarsiToDigits())) ? 1 : parseInt($(this).text().FarsiToDigits()) + 1).toString().DigitsToFarsi();
//     });
//
//     if (data.notification_type === "follow" || data.notification_type === "unfollow") {
//         window.alertify.notify(`
//             ${data.context}
//         `, "warning", 20);
//     }
//     else {
//         window.alertify.notify(`
//             <span style="font-size: 17px;line-height: 50px;">
//                 ${data.title}
//             </span>
//             <br>
//             ${data.context}
//         `, "warning", 20);
//     }
//
//     var notificatioinItem = `
//         <a href="${data.url}">
//             <div class="notification-item">
//                 <img src="${data.avatar}"
//                      class="img-fluid notification-item-avatar">
//                 <small class="notification-item-title">
//                     ${data.title}
//                 </small>
//                 <div class="notification-item-context">
//                     ${data.context}
//                 </div>
//             </div>
//         </a>
//     `;
//
//     $(".notifications-container").prepend(notificatioinItem);
//
// });

$(document).ready(function () {

    $("*[data-to-farsi]").html(function () {
        return $(this).html().DigitsToFarsi();
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
    });

    $(".lazyload").Lazy({
        placeholder: "/images/preloader.gif",
    });

    $("*[data-toggle=\"tooltip\"]").tooltip({
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
                    );
                }
            },
        });
    });
});