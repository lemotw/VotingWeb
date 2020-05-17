@extends('candidate.layout')

@section('include_asset')

<!-- bootstrap 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
<!-- link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
<!-- the font awesome icon library if using with `fas` theme (or Bootstrap 4.x). Note that default icons used in the plugin are glyphicons that are bundled only with Bootstrap 3.x. -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/piexif.min.js" type="text/javascript"></script>
<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. 
    This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/sortable.min.js" type="text/javascript"></script>
<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for 
    HTML files. This must be loaded before fileinput.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/plugins/purify.min.js" type="text/javascript"></script>
<!-- popper.min.js below is needed if you use bootstrap 4.x (for popover and tooltips). You can also use the bootstrap js 
   3.3.x versions without popper.min.js. -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
    dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<!-- the main fileinput plugin file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/js/fileinput.min.js"></script>
<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.1/themes/fas/theme.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.8/js/locales/zh-TW.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" rel="stylesheet">
@stop

<style>
    #form_main{
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .row{
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items:center;
        margin: 10px;
    }
    
    .row > input{
        width:200px;
    }
    .row > label{
        margin:10px;
        font-size: 1.3em;
    }

</style>

@section('title', $title)
@section('content')
<div class="back-inner">
    <h4 style="margin:0;">{{ $subtitle }}</h4>
        <form action="{{ isset($postURL)?$postURL:NULL }}" method="POST" enctype="multipart/form-data" id='form_main'>
            @csrf
            <input type="hidden" name="Candidate" value="{{ isset($candidate)?$candidate->Candidate:NULL }}">

            <div class="row">
                <label>名稱:</label> 
                <input style="width:200px;" type="text" name="Name"
                value="{{ isset($candidate)?$candidate->Name:NULL }}" autocomplete="off">
            </div>

            <div class="row">
                <label>頭貼圖片:</label> 
                <input type="file" name="file" id="profile_image">
            </div>

            <div class="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>

<script>

$("#profile_image").fileinput({
    language: 'zh-TW',
    uploadUrl: "",
    allowedFileExtensions: ["jpg", "png", "gif"],
    minImageWidth: 50,
    minImageHeight: 50,
    maxFileCount: 1,
    initialPreview: [
    @if($candidate->image)
    "<img src='{{ $candidate->image_path() }}' style='width:100%'/>",
    @endif
    ],
    initialPreviewConfig: [
        {
            url: "",
            key: 100,
        },
    ]

});

</script>

@endsection 