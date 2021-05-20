window._ = require("lodash");

$(document).ready(function () {
    $(document).on('click',".post-comment-reply",function () {

        $(".reply-show").slideUp(1000).delay(1000)
            .queue(function () {
                $(this).remove();
            });

        if ($(this).hasClass('clicked-to-reply')){
            $(".clicked-to-reply").removeClass("clicked-to-reply");
            return;
        }

        $(".clicked-to-reply").removeClass("clicked-to-reply");

        $(this).addClass('clicked-to-reply');

        if ($("meta[name='comment']").attr("content")) {

            var commentLevel = parseInt($(this).parent().parent().attr('data-level')) + 1;
            var parentID = parseInt($(this).parent().parent().attr('data-id'));
            var postID = $('#post-details').attr('data-post-id');
            var postType = $('#post-details').attr('data-post-type');
            var csrf = $('meta[name="csrf-token"]').attr('content');

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
                    nextComment.hide();
                }
                if (dataStatus === 'collapse'){
                    nextComment.show();
                    nextComment.attr("data-status","expand");
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
            url: $("#post-details").attr("data-post-type") == 'news' ? '/posts/news/like' : '/posts/user-contents/like',
            type: 'POST',
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $("#post-details").attr("data-post-id"),
                type  : $("#post-details").attr("data-post-type"),
            },
            success: function (data) {
                if (data['status'] === 'Done' ){
                    $this.parent().parent().find(".post-like-count").addClass('post-liked');
                    $this.parent().parent().find(".post-like-button").addClass('post-liked');
                    $(".post-like-count").html(data['like']);

                    $this.parent().parent().find(".post-dislike-count").removeClass('post-disliked');
                    $this.parent().parent().find(".post-dislike-button").removeClass('post-disliked');
                    $(".post-dislike-count").html(data['dislike']);
                }
            },
            error: function (data) {
                if ( data.statusText === 'Too Many Requests'){
                    window.alertify.set('notifier','position', 'bottom-left');
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        'error',
                        5
                    );
                }
            }
        });
    });

    $(".post-dislike-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url: $("#post-details").attr("data-post-type") == 'news' ? '/posts/news/dislike' : '/posts/user-contents/dislike',
            type: 'POST',
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $("#post-details").attr("data-post-id"),
                type  : $("#post-details").attr("data-post-type"),
            },
            success: function (data) {
                if (data['status'] === 'Done' ){
                    $this.parent().parent().find(".post-like-count").removeClass('post-liked');
                    $this.parent().parent().find(".post-like-button").removeClass('post-liked');
                    $(".post-like-count").html(data['like']);

                    $this.parent().parent().find(".post-dislike-count").addClass('post-disliked');
                    $this.parent().parent().find(".post-dislike-button").addClass('post-disliked');
                    $(".post-dislike-count").html(data['dislike']);
                }
            },
            error: function (data) {
                if ( data.statusText === 'Too Many Requests'){
                    window.alertify.set('notifier','position', 'bottom-left');
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        'error',
                        5
                    );
                }
            }
        });
    });

    $(".post-comment-like-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url: '/posts/comments/like',
            type: 'POST',
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : 'comment',
            },
            success: function (data) {
                if (data['status'] === 'Done' ) {
                    $this.parent().parent().find(".post-comment-like-count").addClass('post-comment-liked');
                    $this.parent().parent().find(".post-comment-like-button").addClass('post-comment-liked');
                    $this.parent().parent().find(".post-comment-like-count").html(data['like']);

                    $this.parent().parent().find(".post-comment-dislike-count").removeClass('post-comment-disliked');
                    $this.parent().parent().find(".post-comment-dislike-button").removeClass('post-comment-disliked');
                    $this.parent().parent().find(".post-comment-dislike-count").html(data['dislike']);
                }
            },
            error: function (data) {
                if ( data.statusText === 'Too Many Requests'){
                    window.alertify.set('notifier','position', 'bottom-left');
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        'error',
                        5
                    );
                }
            }
        });
    });

    $(".post-comment-dislike-wrapper").click(function () {
        var $this = $(this);
        $.ajax({
            url: '/posts/comments/dislike',
            type: 'POST',
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id    : $(this).parent().parent().attr("data-id"),
                type  : 'comment',
            },
            success: function (data) {
                if (data['status'] === 'Done' ) {
                    $this.parent().parent().find(".post-comment-like-count").removeClass('post-comment-liked');
                    $this.parent().parent().find(".post-comment-like-button").removeClass('post-comment-liked');
                    $this.parent().parent().find(".post-comment-like-count").html(data['like']);

                    $this.parent().parent().find(".post-comment-dislike-count").addClass('post-comment-disliked');
                    $this.parent().parent().find(".post-comment-dislike-button").addClass('post-comment-disliked');
                    $this.parent().parent().find(".post-comment-dislike-count").html(data['dislike']);
                }
            },
            error: function (data) {
                if ( data.statusText === 'Too Many Requests'){
                    window.alertify.set('notifier','position', 'bottom-left');
                    window.alertify.notify(
                        "تعداد درخواست های شما از سرور بیش از حد مجاز بوده است. <br> یک دقیقه ی دیگر دوباره تلاش کنید.",
                        'error',
                        5
                    );
                }
            }
        });
    });

    var offset = $(':target').offset();
    if (offset){
        var scrollto = offset.top - 80;
        $('html, body').animate({scrollTop:scrollto}, 0);
    }

    $("#delete-post-btn").click(function () {
        window.alertify.alert().setting('closable');
        window.alertify.confirm('آیا از حذف این پست مطمئن هستید؟')
            .set('defaultFocus', 'لغو')
            .setHeader('هشدار !')
            .set('onok', function(){
                $("html").prepend(
                    `
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);

                $("#delete-post").submit();
            });
        $(".ajs-ok").text('تایید');
        $(".ajs-ok").addClass('btn btn-default');
        $(".ajs-cancel").text('لغو');
        $(".ajs-cancel").addClass('btn btn-default');
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

    $(".post-main-body").find('img').each(function () {
        $(this).addClass('img-fluid');
        $(this).addClass('img-thumbnail');
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
