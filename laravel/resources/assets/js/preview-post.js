$(document).ready(function () {
    $(".release-link").click(function () {
        $("html").prepend(`
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);
    });
    $(".draft-link").click(function () {
        $("html").prepend(`
                        <div class="before-send">
                            <img src="/images/loader.gif">
                        </div>
                    `);
    });
    $(".post-main-body").find('img').each(function () {
        $(this).addClass('img-fluid');
        $(this).addClass('img-thumbnail');
    })
});