<meta name="title" property="og:title" content="{{ $title }}">
<meta name="description" property="og:description" content="{{ $description }}">
<meta name="image" property="og:image" content="{{ env('IMAGE_API') }}?url={{ urlencode($image) }}?v={{ env('IMAGE_API_VERSION' }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@kalenjordan">
<meta name="twitter:creator" content="@kalenjordan">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:image" content="{{ env('IMAGE_API') }}?url={{ urlencode($image) }}?v={{ env('IMAGE_API_VERSION' }}" />
<meta name="twitter:description" content="{{ $description }}" />

<meta name="og:title" content="{{ $title }}">
<meta name="og:description" content="{{ $description }}">
<meta name="og:image" content="{{ env('IMAGE_API') }}?url={{ urlencode($image) }}?v={{ env('IMAGE_API_VERSION' }}">