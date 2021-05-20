window._ = require("lodash");

$(document).ready(function () {

    $(document).on("click", ".post-comment-reply", function () {

        $(".reply-show").slideUp(1000).delay(1000)
            .queue(function () {
                $(this).remove();
            });

        if ($(this).hasClass("clicked-to-reply")) {
            $(".clicked-to-reply").removeClass("clicked-to-reply");
            return;
        }

        $(".clicked-to-reply").removeClass("clicked-to-reply");

        $(this).addClass("clicked-to-reply");

        if ($("meta[name='comment']").attr("content")) {

            var commentLevel = parseInt($(this).parent().parent().attr("data-level")) + 1;
            var parentID     = parseInt($(this).parent().parent().attr("data-id"));
            var postID       = $("#post-details").attr("data-post-id");
            var postType     = $("#post-details").attr("data-post-type");
            var csrf         = $("meta[name='csrf-token']").attr("content");

            $(this).parent().parent().after(`
                <div class="post-compose-comment reply-show post-comment-${commentLevel}" style="display: none">
                    <div class="post-compose-comment-container">
                        <form method="post" action="/posts/comments?post_id=${postID}&post_type=${postType}&comment_level=${commentLevel}&parent_id=${parentID}">
                            <input type="hidden" name="_token" value="${csrf}">
                            <textarea name="context" placeholder="پاسخ خود را بنویسید ..."></textarea>
                            <button type="submit" class="btn btn-info">ارسال</button>
                        </form>
                    </div>
                </div>
            `);
        }
        else {
            $(this).parent().parent().after(`
                <div class="reply-show" style="display: none">
                    <div class="alert alert-danger" role="alert"
                     style="margin-top: 15px; text-align: right; font-family: IRANSANS">
                        <strong>.شما امکان ارسال نظر ندارید</strong>
                    </div>
                </div>
            `);
        }

        $(".reply-show").slideDown(1000);
    });

    $(".post-comment-toggle").click(function () {
        let dataLevel   = parseInt($(this).parent().parent().attr("data-level"));
        let dataStatus  = $(this).parent().parent().attr("data-status");
        let nextComment = $(this).parent().parent().next();

        $(this).children().toggleClass("fa-angle-up");
        $(this).children().toggleClass("fa-angle-down");
        if ($(this).children().hasClass("fas fa-angle-up")) {
            $(this).tooltip("hide")
                .attr("data-original-title", "باز کردن")
                .tooltip("show");
            $(this).css({
                "background": "#7D7D7D",
                "color"     : "#F7F7F7",
            });
        }
        if ($(this).children().hasClass("fas fa-angle-down")) {
            $(this).tooltip("hide")
                .attr("data-original-title", "بستن")
                .tooltip("show");
            $(this).css({
                "background": "#F7F7F7",
                "color"     : "#7D7D7D",
            });
        }

        if (dataStatus === 'expand'){
            $(this).parent().parent().attr("data-status","collapse")
        }
        if (dataStatus === 'collapse'){
            $(this).parent().parent().attr("data-status","expand")
        }

        while (true) {
            if (parseInt(nextComment.attr("data-level")) > dataLevel) {
                if (dataStatus === 'expand'){
                    nextComment.fadeOut();
                }
                if (dataStatus === 'collapse'){
                    nextComment.fadeIn();
                    $(this).parent().parent().attr("data-status","expand");

                    nextComment.find('.post-comment-toggle').children().addClass("fa-angle-down");
                    nextComment.find('.post-comment-toggle').children().removeClass("fa-angle-up");
                    nextComment.find('.post-comment-toggle').attr("data-original-title", "بستن");
                    nextComment.find('.post-comment-toggle').css({
                        "background": "#F7F7F7",
                        "color"     : "#7D7D7D",
                    });
                }
            } else {
                break;
            }
            nextComment = nextComment.next();
        }

    });

    $(".post-like-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url    : $("#post-details").attr("data-post-type") == "news" ? "/posts/news/like" : "/posts/user-contents/like",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $("#post-details").attr("data-post-id"),
                type  : $("#post-details").attr("data-post-type"),
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-like-count").addClass("post-liked");
                    $this.parent().parent().find(".post-like-button").addClass("post-liked");
                    $(".post-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-dislike-count").removeClass("post-disliked");
                    $this.parent().parent().find(".post-dislike-button").removeClass("post-disliked");
                    $(".post-dislike-count").html(data["dislike"]);
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

    $(".post-dislike-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url    : $("#post-details").attr("data-post-type") == "news" ? "/posts/news/dislike" : "/posts/user-contents/dislike",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $("#post-details").attr("data-post-id"),
                type  : $("#post-details").attr("data-post-type"),
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-like-count").removeClass("post-liked");
                    $this.parent().parent().find(".post-like-button").removeClass("post-liked");
                    $(".post-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-dislike-count").addClass("post-disliked");
                    $this.parent().parent().find(".post-dislike-button").addClass("post-disliked");
                    $(".post-dislike-count").html(data["dislike"]);
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

    $(".post-comment-like-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/comments/like",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : "comment",
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-comment-like-count").addClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-button").addClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-comment-dislike-count").removeClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-button").removeClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-count").html(data["dislike"]);
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

    $(".post-comment-dislike-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url    : "/posts/comments/dislike",
            type   : "POST",
            data   : {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : "comment",
            },
            success: function (data) {
                if (data["status"] === "Done") {
                    $this.parent().parent().find(".post-comment-like-count").removeClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-button").removeClass("post-comment-liked");
                    $this.parent().parent().find(".post-comment-like-count").html(data["like"]);

                    $this.parent().parent().find(".post-comment-dislike-count").addClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-button").addClass("post-comment-disliked");
                    $this.parent().parent().find(".post-comment-dislike-count").html(data["dislike"]);
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

    var offset = $(":target").offset();
    if (offset) {
        var scrollto = offset.top - 80;
        $("html, body").animate({scrollTop: scrollto}, 0);
    }

    $("#delete-post-btn").click(function () {
        window.alertify.alert().setting("closable");
        window.alertify.confirm("آیا از حذف این پست مطمئن هستید؟")
            .set("defaultFocus", "لغو")
            .setHeader("هشدار !")
            .set("onok", function () {
                $("html").prepend(
                    `
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);

                $("#delete-post").submit();
            });
        $(".ajs-ok").text("تایید");
        $(".ajs-ok").addClass("btn btn-default");
        $(".ajs-cancel").text("لغو");
        $(".ajs-cancel").addClass("btn btn-default");
    });

    $("#slide-post").click(function () {
        if ($("meta[name='slide-post']").attr("content")) {
            $.ajax({
                url    : "/posts/news/slider/show",
                type   : "GET",
                data: {
                    id  : document.head.querySelector("meta[name='id']").content,
                    slug: document.head.querySelector("meta[name='slug']").content,
                },
                success: function (data) {
                    $(".slide-post-prompt").empty();
                    $(".modal-backdrop").remove();
                    $(".slide-post-prompt").append(`
                        <div class="modal fade bd-example-modal-lg" id="slidePostModal" tabindex="-1" role="dialog"
                             aria-labelledby="slidePostModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="max-width: 920px">
                                    <div class="modal-content" style="padding: 15px;">
                                        <div style="width: 100%; height: 30px">
                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal"
                                                    style="float: left;"
                                                    aria-label="Close">
                                                <span aria-hidden="true" style="color: red">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-10">
                                                        <div class="card">
                                                            <div class="card-header" style="line-height: 40px;">اسلایدر
                                                                <form method="POST"
                                                                      style="float: left" 
                                                                      action="/posts/news/slider/destroy">

                                                                    <input type="hidden" name="_token" value="${document.head.querySelector("meta[name='csrf-token']").content}">

                                                                    <input type="hidden" name="_method" value="delete">
                                                                    
                                                                    <input type="hidden" name="id" value="${document.head.querySelector("meta[name='id']").content}">
        
                                                                    <input type="hidden" name="slug" value="${document.head.querySelector("meta[name='slug']").content}">

                                                                    <button type="submit" 
                                                                            class="btn btn-danger">حذف از اسلایدر</button>

                                                                </form>
                                                            </div>
                                                            <div class="card-body">
                                                                <form method="POST"
                                                                      action="/posts/news/slider/store">
        
                                                                    <input hidden name="_token" value="${document.head.querySelector("meta[name='csrf-token']").content}">
        
                                                                    <input hidden name="id" value="${document.head.querySelector("meta[name='id']").content}">
        
                                                                    <input hidden name="slug" value="${document.head.querySelector("meta[name='slug']").content}">
        
                                                                    <div class="form-group row">
                                                                        <label for="first-tag"
                                                                               class="col-md-4 col-form-label text-md-left text-right">تگ اول</label>
                                                                        <div class="col-md-6">
                                                                            <input id="first-tag"
                                                                                   type="text"
                                                                                   value="${data.firstTag}"
                                                                                   class="form-control"
                                                                                   name="first_tag"
                                                                                   autofocus>
                                                                        </div>
                                                                    </div>
                                        
                                                                    <div class="form-group row">
                                                                        <label for="second-tag"
                                                                               class="col-md-4 col-form-label text-md-left text-right">تگ دوم</label>
                                                                        <div class="col-md-6">
                                                                            <input id="second-tag"
                                                                                   value="${data.secondTag}"
                                                                                   type="text"
                                                                                   class="form-control"
                                                                                   name="second_tag">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="form-group row">
                                                                        <label for="third-tag"
                                                                               class="col-md-4 col-form-label text-md-left text-right">تگ سوم</label>
                                                                        <div class="col-md-6">
                                                                            <input id="third-tag"
                                                                                   type="text"
                                                                                   value="${data.thirdTag}"
                                                                                   class="form-control"
                                                                                   name="third_tag">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="form-group row">
                                                                        <label for="forth-tag"
                                                                               class="col-md-4 col-form-label text-md-left text-right">تگ چهارم</label>
                                                                        <div class="col-md-6">
                                                                            <input id="forth-tag"
                                                                                   value="${data.forthTag}"
                                                                                   type="text"
                                                                                   class="form-control"
                                                                                   name="forth_tag">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="form-group row">
                                                                        <label for="order"
                                                                               class="col-md-4 col-form-label text-md-left text-right">شماره نوبت</label>
                                                                        <div class="col-md-6">
                                                                            <input id="order"
                                                                                   type="number"
                                                                                   value="${(data.order) ? data.order : 1}"
                                                                                   min="1"
                                                                                   class="form-control"
                                                                                   name="order">
                                                                        </div>
                                                                    </div>                                        

                                                                    <div class="form-group row mb-0 justify-content-around">
                                                                        <div class="col-md-4 offset-sm-3 offset-lg-5 text-right text-md-left">
                                                                            <button type="submit"
                                                                                    class="btn btn-primary">
                                                                                ذخیره
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `);
                    $("#slidePostModal").modal();
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
        }
    });

    $("#chief-choice").click(function () {
        if ($("meta[name='chief-choice']").attr("content")) {
            $.ajax({
                url    : "/chief-choice",
                type   : "GET",
                data   : {
                    id  : document.head.querySelector("meta[name='id']").content,
                    slug: document.head.querySelector("meta[name='slug']").content,
                },
                success: function (data) {
                    if (!data.length) {
                        $.ajax({
                            url    : "/chief-choice/store",
                            type   : "POST",
                            data   : {
                                _token: $("meta[name='csrf-token']").attr("content"),
                                id    : document.head.querySelector("meta[name='id']").content,
                                slug  : document.head.querySelector("meta[name='slug']").content,
                            },
                            success: function () {
                                window.alertify.set("notifier", "position", "bottom-left");
                                window.alertify.notify(
                                    "عملیات با موفقیت انجام شد",
                                    "success",
                                    5,
                                );
                            },
                        });
                        return;
                    }
                    $(".chief-choice-prompt").empty();
                    $(".modal-backdrop").remove();
                    let chief_choices = "";
                    data.forEach(function (item) {
                        chief_choices += `
                            <div class="chief-choice-item card mr-1 ml-1">
                                 <label>
                                     <input type="radio" name="delete_item" value="${item.slug}">
                                     <img class="card-img-top chief-choice-card-image"
                                         src="${item.image}"
                                         alt="Card image cap"/>
                                      <div class="card-body chief-choice-card-body">
                                          ${item.title}
                                      </div>
                                 </label>
                            </div>
                        `;
                    });
                    $(".chief-choice-prompt").append(`
                        <div class="modal fade bd-example-modal-lg"
                             id="chief-choice-modal"
                             tabindex="-1"
                             role="dialog"
                             aria-labelledby="chief-choice-modal-label"
                             aria-hidden="true">
                                <div class="modal-dialog" 
                                     style="max-width: 920px">
                                    <div class="modal-content" 
                                         style="padding: 15px;">
                                        <div style="width: 100%; height: 30px">
                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal"
                                                    style="float: left;"
                                                    aria-label="Close">
                                                <span aria-hidden="true"
                                                      style="color: #F05">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container">
                                                <div class="row justify-content-center">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-header" 
                                                                 style="line-height: 40px;">
                                                            کدام مطلب از ستون پیشنهاد سردبیر برداشته شود؟
                                                            </div>
                                                            <div class="card-body">
                                                                <form method="POST"
                                                                      style="text-align: center;"
                                                                      action="/chief-choice/store">
                                                                    <input type="hidden" 
                                                                           name="_token" 
                                                                           value="${document.head.querySelector("meta[name='csrf-token']").content}">
                                                                    <input type="hidden" 
                                                                           name="id" 
                                                                           value="${document.head.querySelector("meta[name='id']").content}">
                                                                    <input type="hidden" 
                                                                           name="slug" 
                                                                           value="${document.head.querySelector("meta[name='slug']").content}">
                                                                    ${chief_choices}
                                                                    <div class="form-group"
                                                                         style="margin-top: 15px;text-align: right;margin-right: 50px;">
                                                                        <button type="submit"
                                                                                class="btn btn-primary">
                                                                            تایید
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `);
                    $("#chief-choice-modal").modal();
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
        }
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
                        <a href="${item['url']}">
                            <li class="list-group-item news-body-item">
                                <span class="time">
                                    ${String(item['time']).DigitsToFarsi()}
                                </span>
                                ${item['title']}
                            </li>
                        </a>
                    `);
                });
                if (!data.length){
                    $this.attr("data-status","DONE");
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
            let $this           = $(this);
            let offset          = $this.attr("data-offset");
            let height          = this.scrollHeight - $this.height(); // Get the height of the div
            let scroll          = $this.scrollTop(); // Get the vertical scroll position
            let isScrolledToEnd = (scroll >= height);
            if (isScrolledToEnd) {
                if ($(`${element}`).attr('data-status') !== 'DONE'){
                    Handler[callback]($this, offset);
                }
            }
        });
    }

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
