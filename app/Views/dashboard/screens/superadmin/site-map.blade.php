@include('dashboard.components.header')
<?php
use Illuminate\Routing\UrlGenerator;
?>
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            <div class="card-box">
                <div class="header-area d-flex align-items-center">
                    <h4 class="header-title mb-0 d-block">{{__('Sitemap Settings')}}</h4>
                </div>
                <p class="mt-2 text-muted font-italic">{{__('Set up the sitemap settings')}}</p>
                <div class="mt-2 text-muted font-italic"><span>{{__('View your sitemap here: ')}}</span><a
                        href="{{url('/sitemap_index.xml')}}">{{__('sitemap_index.xml ')}}</a></div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <form action="{{dashboard_url('site-map-save')}}" class="form form-action relative mt-2"
                              data-validation-id="form-sitemap">
                            @include('common.loading')
                            <div class="form-group">
                                <?php
                                $sitemap_per_page = get_opt('sitemap_per_page', 100);
                                ?>
                                <label for="sitemap-per-page">{{__('Sitemap Per Page:')}}</label>
                                <input id="sitemap-per-page" type="text" class="form-control"
                                       name="sitemap_per_page" value="{{ $sitemap_per_page}}">
                            </div>
                            <?php
                            $list_services = get_posttypes(true);
                            foreach($list_services as $key => $service){
                            $service_meta_key = 'sitemap_' . $key . '_enable';
                            $sitemap_service_enable = get_opt($service_meta_key, 'on');
                            ?>
                            <div class="form-group">
                                <label for="sitemap-{{$key}}-enable">
                                    {{__('Enable ')}} {{$service}}
                                </label><br/>
                                <input type="checkbox" id="sitemap-{{$key}}-enable" name="sitemap_{{$key}}_enable"
                                       data-plugin="switchery" data-color="#1abc9c" value="on"
                                       @if($sitemap_service_enable == 'on') checked @endif/>
                            </div>
                            <?php } ?>
                            <div id="setting-services-enable" class="form-group">
                                <?php  $sitemap_post_enable = get_opt('sitemap_post_enable', 'on'); ?>
                                <label for="sitemap-post-enable">
                                    {{__('Enable Post')}}
                                </label><br/>
                                <input type="checkbox" id="sitemap-post-enable" name="sitemap_post_enable"
                                       data-plugin="switchery" data-color="#1abc9c" value="on"
                                       @if($sitemap_post_enable == 'on') checked @endif/>
                            </div>
                            <div id="setting-services-enable" class="form-group">
                                <?php  $sitemap_page_enable = get_opt('sitemap_page_enable', 'on'); ?>
                                <label for="sitemap-page-enable">
                                    {{__('Enable Page')}}
                                </label><br/>
                                <input type="checkbox" id="sitemap-page-enable" name="sitemap_page_enable"
                                       data-plugin="switchery" data-color="#1abc9c" value="on"
                                       @if($sitemap_page_enable == 'on') checked @endif/>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{__('Save Change')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')
