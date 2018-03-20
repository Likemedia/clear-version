@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createSetting/createitem'),
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
        ]
    ])
@endif

@if(!empty($settings_list))
    <table class="el-table" id="tablelistsorter">
        <thead>
        <tr>
            <th>ID</th>
            <th>{{trans('variables.title_table')}}</th>
            <th>Short Cut</th>
            <th>{{trans('variables.edit_table')}}</th>
            @if($groupSubRelations->del_to_rec == 1)
                <th>{{trans('variables.delete_table')}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($settings_list as $key => $setting)
            <tr>
                <td>#{{ $key+1 }}</td>
                <td>{{ !empty(IfHasName($setting->settings_id, $lang_id, 'settings')) ? IfHasName($setting->settings_id, $lang_id, 'settings') : trans('variables.another_name')}}</td>
                <td>[{{$setting->settingsId->alias}}]</td>
                <td>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, str_slug($setting->name).'/edititem/'.$setting->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($setting->settings_id, $one_lang->id, 'settings')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                    @endforeach
                </td>
                @if($groupSubRelations->del_to_rec == 1)
                    <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($setting->name).'/destroysetting/'.$setting->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan=5>{!! $settings_list_id->links() !!}</td>
        </tr>
        </tfoot>
    </table>
@else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop

