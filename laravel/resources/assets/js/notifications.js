window._ = require("lodash");

let offset = 0;

$(document).ready(function () {
    $.ajax({
        url    : "/users/get-notifications",
        type   : "GET",
        data   : {
            "offset": offset,
            "take"  : 10,
        },
        success: function (data) {
            if (data.length) {
                data.forEach(function (item) {
                    $(".wrapper").append(`
                        <div class="card mb-2">
                            <div class="card-body text-right">
                                <a href="${item["url"]}"
                                   class="card-link">
                                    <div class="avatar-border">
                                        <img src="${item["avatar"]}"
                                             class="img-fluid">
                                    </div>
                                    <span class="context">
                                        <small class="text-muted">${item["title"]}</small>
                                        <h6>${item["context"]}&nbsp;</h6>
                                    </span>
                                </a>
                            </div>
                        </div>
                    `);
                });
                $(".wrapper").append(`
                    <div class="see-more">
                        <button class="btn btn-secondary btn-block">مشاهده ی بیشتر</button>
                    </div>
                `);
            }
            else {
                $(".notifications-container").append(`
                        <div class="no-notification">هیچ اعلانی وجود ندارد</div>
                    `);
            }

            offset += data.length;
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

    $(document).on("click", ".see-more", function () {
        $.ajax({
            url       : "/users/get-notifications",
            type      : "GET",
            data      : {
                "offset": offset,
                "take"  : 10,
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data.length) {
                    data.forEach(function (item) {
                        $(".see-more").before(`
                            <div class="card mb-2">
                                <div class="card-body text-right">
                                    <a href="${item["url"]}"
                                       class="card-link">
                                        <div class="avatar-border">
                                            <img src="${item["avatar"]}"
                                                 class="img-fluid">
                                        </div>
                                        <span class="context">
                                            <small class="text-muted">${item["title"]}</small>
                                            <h6>${item["context"]}&nbsp;</h6>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        `);
                    });
                }
                else {
                    $(".see-more").remove();
                }
                offset += data.length;
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
                    );
                }
            },
        });
    });
});
