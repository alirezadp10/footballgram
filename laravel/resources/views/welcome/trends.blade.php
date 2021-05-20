<div class="container hashtag-navbar">
    <span class="title">ترند های ۲۴ ساعت گذشته :</span>
    <div class="hashtag-wrapper">
        @foreach($response['trends'] as $trend)
            <a href="{{ $trend['url'] }}">
                <button class="btn btn-light hashtag">{{ $trend['name'] }}</button>
            </a>
        @endforeach
    </div>
</div>