@include('dashboard.components.header')
<?php
enqueue_style('confirm-css');
enqueue_script('confirm-js');

enqueue_script('nice-select-js');
enqueue_style('nice-select-css');

$lang = request()->get('lang', 'none');
$site_language = get_option('site_language', 'none');
if ($site_language != 'none' && $lang == 'none') {
    $lang = $site_language;
}
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            <div class="card-box page-translation">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0">{{ __('Text Translation') }}</h4>
                    <a class="btn btn-primary ml-3 hh-link-action hh-link-scan-translation  btn-xs btn-success"
                       data-page-loading="true"
                       data-action="{{ dashboard_url('scan-translation') }}"
                       data-parent="tr"
                       data-is-delete="false"
                       data-params="{{ base64_encode(json_encode(['scan' => true, 'lang' => $lang])) }}"
                       href="javascript: void(0)">
                        {{ __('Scan Text') }}
                    </a>
                </div>

                <form action="{{ dashboard_url('update-translate') }}" class="form form-action" method="post"
                      data-validation-id="form-update-translate"
                      data-encode="true">
                    @include('common.loading')
                    <div class="d-flex mb-3 justify-content-between">
                        <div class="form-inline">
                            <label for="hh-choose-langs" class="mr-2">{{ __('Languages') }}</label>
                            <select id="hh-choose-langs" data-plugin="customselect"
                                    class="form-control form-control-sm min-w-200" name="lang"
                                    data-url="{{ dashboard_url('translation') }}">
                                <option value="none">{{ __('Select language') }}</option>
                                @if(!empty($langs))
                                    @foreach($langs as $k => $v)
                                        <option {{ ($k == $lang) ? 'selected' : '' }} value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success right btn-scan-translation">
                            <span class="btn-label"><i class="mdi mdi-check-all"></i></span>
                            {{__('Save Translation')}}
                        </button>
                    </div>
                    @if($lang == 'none' || !isset($langs[$lang]))
                        <div class="alert alert-warning">{{ __('Please select a language before translating') }}</div>
                    @endif

                    <div class="translation-search">
                        <input id="input-search-translation" class="form-control" type="text"
                               placeholder="{{__('Enter search text...')}}"/>
                        <button type="button" class="btn btn-success"><i class="ti-search mr-1"></i> {{__('Search')}}
                        </button>
                    </div>

                    <div class="table-responsive table-translations">
                        @if(!empty($strings))
                            <table class="table mb-0 h-100">
                                <colgroup width="35%"></colgroup>
                                <colgroup></colgroup>
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Origin Text') }}
                                        ({{ __(':number items', ['number' => count($strings)]) }})
                                    </th>
                                    <th>{{ __('Translation Text') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($strings as $k => $v)
                                    <tr>
                                        <th scope="row" class="align-middle">{{ $v }}</th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   name="{{ base64_encode($v) . '_' . time() }}"
                                                   value="{{ isset($translation[$v]) ? $translation[$v] : '' }}"/>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-danger">{{ __('No Text to translate') }}</div>
                        @endif
                    </div>
                    <hr/>
                    <button type="submit" class="mt-2 btn btn-success">
                        <span class="btn-label"><i class="mdi mdi-check-all"></i></span>
                        {{ __('Save Translation') }}
                    </button>
                </form>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')
