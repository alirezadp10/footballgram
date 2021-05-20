window._ = require("lodash");

$(document).ready(function () {
    $(".follow-btn").click(function () {
        var $this = $(this);
        $.ajax({
            url       : "/users/follow",
            type      : "POST",
            data      : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).attr("data-user-id"),
            },
            beforeSend: function () {
                $("html").prepend(`
                    <div class="before-send">
                        <img src="/images/loader.gif">
                    </div>
                `);
            },
            success   : function (data) {
                if (data["followingStatus"] === "followed") {
                    $this.removeClass("btn-primary");
                    $this.addClass("btn-danger");
                    $this.html("توقف دنبال کردن");
                }
                if (data["followingStatus"] === "unFollowed") {
                    $this.removeClass("btn-danger");
                    $this.addClass("btn-primary");
                    $this.html("دنبال کردن");
                }
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
            complete  : function () {
                _.delay(function () {
                    $(".before-send").remove();
                }, 1000);
            },
        });
    });
});
