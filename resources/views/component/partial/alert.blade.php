@if (Session::get('type'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-{{ Session::get('type') }}  alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                @if (Session::get('type') == 'danger')
                    <i class="fa fa-times-circle fa-fw fa-lg"></i>
                @elseif(Session::get('type') == 'warning')
                    <i class="fa fa-exclamation-triangle fa-fw fa-lg"></i>
                @elseif(Session::get('type') == 'info')
                    <i class="fa fa-info-circle fa-fw fa-lg"></i>
                @elseif(Session::get('type') == 'success')
                    <i class="fa fa-check-circle fa-fw fa-lg"></i>
                @else
                    <i class="fa fa-info-circle fa-fw fa-lg"></i>
                @endif
                {!! Session::get('message') !!}
            </div>
        </div>
    </div>
@endif
