<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>فانتزی</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style>

        body{
            background-image: url("images/main-paralex.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : 900;
            src         : url('/fonts/iransans/eot/IRANSansWeb_Black.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb_Black.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb_Black.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb_Black.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb_Black.ttf') format('truetype');
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : bold;
            src         : url('/fonts/iransans/eot/IRANSansWeb_Bold.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb_Bold.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb_Bold.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb_Bold.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb_Bold.ttf') format('truetype');
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : 500;
            src         : url('/fonts/iransans/eot/IRANSansWeb_Medium.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb_Medium.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb_Medium.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb_Medium.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb_Medium.ttf') format('truetype');
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : 300;
            src         : url('/fonts/iransans/eot/IRANSansWeb_Light.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb_Light.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb_Light.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb_Light.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb_Light.ttf') format('truetype');
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : 200;
            src         : url('/fonts/iransans/eot/IRANSansWeb_UltraLight.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb_UltraLight.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb_UltraLight.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb_UltraLight.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb_UltraLight.ttf') format('truetype');
        }

        @font-face {
            font-family : IRANSANS;
            font-style  : normal;
            font-weight : normal;
            src         : url('/fonts/iransans/eot/IRANSansWeb.eot');
            src         : url('/fonts/iransans/eot/IRANSansWeb.eot?#iefix') format('embedded-opentype'), /* IE6-8 */
            url('/fonts/iransans/woff2/IRANSansWeb.woff2') format('woff2'), /* FF39+,Chrome36+, Opera24+*/
            url('/fonts/iransans/woff/IRANSansWeb.woff') format('woff'), /* FF3.6+, IE9, Chrome6+, Saf5.1+*/
            url('/fonts/iransans/ttf/IRANSansWeb.ttf') format('truetype');
        }

        * {
            text-align : center !important;
            direction  : rtl;
            font-family: IRANSANS ,sans-serif;
        }

        body {
            padding-top : 5rem;
        }

    </style>
</head>
<body>
<div class="container" style="background: white; padding: 15px">
    <table id="example" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>نام</th>
            <th>باشگاه</th>
            <th>پست</th>
            <th>امتیاز</th>
            <th>قیمت</th>
            <th>درصد انتخاب</th>
            <th>امتیاز هفته قبل</th>
        </tr>
        </thead>
        <tbody>
        @foreach($response['data'] as $item)
            <tr>
                <td data-to-farsi>{{ $loop->index }}</td>
                <td>{{ $item['fullname'] }}</td>
                <td>{{ $item['team']['name'] }}</td>
                <td>{{ $item['postName'] }}</td>
                <td>{{ $item['totalPoints'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td data-order="{{ $item['selectedBy'] }}">{{ $item['selectedBy'] }}٪</td>
                <td>{{ $item['lastWeekPoints'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
        integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $("#example").DataTable({
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
    });
</script>
</body>
</html>