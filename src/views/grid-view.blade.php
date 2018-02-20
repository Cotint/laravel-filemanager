@if((sizeof($files) > 0) || (sizeof($directories) > 0))

<div class="row">

  @foreach($items as $item)
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 img-row">
    <?php $item_name = $item->name; ?>
    <?php $thumb_src = $item->thumb; ?>
    <?php $item_path = $item->is_file ? $item->url : $item->path; ?>

    <div class="square clickable {{ $item->is_file ? 'file-item' : 'folder-item' }}" data-id="{{ $item_path }}"
           @if($item->is_file && $thumb_src) onclick="useFile('{{ $item_path }}', '{{ $item->updated }}')"
           @elseif($item->is_file) onclick="download('{{ $item_name }}')" @endif >
      @if($thumb_src)
      <img src="{{ $thumb_src }}">
      @else
      <i class="fa {{ $item->icon }} fa-5x"></i>
      @endif
    </div>

    <div class="caption text-center">
      <div class="btn-group">
        <button type="button" data-id="{{ $item_path }}"
                class="item_name btn btn-default btn-xs {{ $item->is_file ? '' : 'folder-item'}}"
                @if($item->is_file && $thumb_src) onclick="useFile('{{ $item_path }}', '{{ $item->updated }}')"
                @elseif($item->is_file) onclick="download('{{ $item_name }}')" @endif >
          {{ $item_name }}
        </button>
        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="javascript:rename('{{ $item_name }}')"><i class="fa fa-edit fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-rename') }}</a></li>
          <li><a class="seo-link" href="" data-toggle="modal" data-target="#seo"><i class="fa fa-edit fa-fw"></i>سئو</a></li>
          @if($item->is_file)
          <li><a href="javascript:download('{{ $item_name }}')"><i class="fa fa-download fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-download') }}</a></li>
          <li class="divider"></li>
          @if($thumb_src)
          <li><a href="javascript:fileView('{{ $item_path }}', '{{ $item->updated }}')"><i class="fa fa-image fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-view') }}</a></li>
          <li><a href="javascript:resizeImage('{{ $item_name }}')"><i class="fa fa-arrows fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-resize') }}</a></li>
          <li><a href="javascript:cropImage('{{ $item_name }}')"><i class="fa fa-crop fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-crop') }}</a></li>
          <li class="divider"></li>
          @endif
          @endif
          <li><a href="javascript:trash('{{ $item_name }}')"><i class="fa fa-trash fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-delete') }}</a></li>
        </ul>
      </div>
    </div>

  </div>
  @endforeach

</div>

@else
<p>{{ Lang::get('laravel-filemanager::lfm.message-empty') }}</p>
@endif
<!-- Modal -->
<div class="modal fade" id="seo" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Seo</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="usr">Title</label>
          <input type="text" class="form-control" id="title">
        </div>
        <div class="form-group">
          <label for="usr">Alt</label>
          <input type="text" class="form-control" id="alt">
          <input type="hidden" class="form-control" id="filepath">
        </div>
      </div>
      <div class="modal-footer">
        <button data-dismiss="modal" type="button" class="btn btn-default">لغو</button>
        <button id="sub" type="button" class="btn btn-primary">تایید</button>
      </div>
    </div>
  </div>
</div>
<script>

    var getImageUrl = '{{route('panel.files.getImage')}}';
    var storeImageUrl = '{{route('panel.files.storeImage')}}';

    $('.seo-link').on('click', function() {
        var item = $(this).parent().parent().parent().parent().prev().data('id');
        // var url = window.location.protocol + "//" + window.location.host + "/" + getImageUrl;
        $.ajax({
            url: getImageUrl,
            type: 'GET',
            data: {'item': item},
            success: function (data) {
                $('#title').val(data.title);
                $('#alt').val(data.alt);
                $('#filepath').val(data.filepath);
            }
        });
    });

    $('#sub').on('click', function() {
        var title = $('#title').val();
        var alt = $('#alt').val();
        var item = $('#filepath').val();
        // var url = window.location.protocol + "//" + window.location.host + "/" + storeImageUrl;
        $.ajax({
            url: storeImageUrl,
            type: 'POST',
            data: {'title':title, 'alt':alt, 'item': item,'_token':'{{ csrf_token() }}',
            },
            success: function (data) {
            }
        });
        $('#seo').modal('hide');
    })

</script>
