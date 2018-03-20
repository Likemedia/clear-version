@section('nav-bar')
<div class="header-block header-block-collapse hidden-lg-up"> 
    <button class="collapse-btn" id="sidebar-collapse-btn"><i class="fa fa-bars"></i></button> 
</div>

<div class="header-block header-block-buttons">

    <a href="" target="_blank" class="btn btn-sm header-btn"> <i class="fa fa-home"> </i> <span>{{trans('variables.go_to_the_site')}}</span> </a>

    <a href="/auth/logout" class="btn btn-sm header-btn"> <i class="fa fa-sign-out"></i> <span>{{trans('variables.log_out')}}</span> </a>

</div>

<div  class="header-block">
    @foreach($langs as $lang)
        <a href="{{ route('set.language', $lang->lang) }}"
        @if ( session('applocale') == $lang->lang ) {{ "class=active-link" }} @endif
        >
            {{ $lang->lang }}
        </a>
    @endforeach

</div>

<div class="header-block header-block-nav">
    <ul class="nav-profile">
        <li class="profile dropdown">
            <a class="nav-link" href="{{ url(1 . '/back/admin_user/administrator/edituser/'.Auth::user()->id) }}"> <span class="name">Hi,
            {{ Auth::user()->name }} </span> </a>
        </li>
    </ul>
</div>
@stop
