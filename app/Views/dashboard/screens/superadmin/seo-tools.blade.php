@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            @include('dashboard.components.breadcrumb', ['heading' => __('Change SEO of Pages')])
            {{--Start Content--}}
            <div class="card-box page-seo-tools">
                <h5>{{__('Change SEO of Pages')}}</h5>
                <div class="row pt-3 pb-3">
                    <div class="col-12 col-lg-8">
                        <form class="form form-action hh-options-wrapper form-sm form-seo-tools"
                              action="{{ dashboard_url('seo-page-save') }}"
                              method="post">
                            {!! show_lang_section('mb-2') !!}
                            <?php
                            $pages = config('awebooking.pages_name');
                            $i = 0;
                            foreach ($pages as $name => $page) {
                            if (isset($page['seo_page']) && !$page['seo_page']) {
                                continue;
                            }
                            ?>
                            <div class="accordion" id="accordion-{{$page['screen']}}-{{$i}}">
                                <div class="card">
                                    <div class="card-header p-1" id="heading-{{$page['screen']}}-{{$i}}">
                                        <h2 class="m-0">
                                            <button
                                                class="btn btn-link text-black-50 btn-block text-left d-flex align-items-center justify-content-between"
                                                type="button"
                                                data-toggle="collapse" data-target="#collapse-{{$page['screen']}}-{{$i}}"
                                                aria-expanded="true" aria-controls="collapse-{{$page['screen']}}-{{$i}}">
                                                {{$page['seo_name']}}
                                                <i class="mdi mdi-chevron-down accordion-arrow right"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapse-{{$page['screen']}}-{{$i}}" class="collapse @if($i == 0) show @endif"
                                         aria-labelledby="heading-{{$page['screen']}}-{{$i}}"
                                         data-parent="#accordion-{{$page['screen']}}-{{$i}}">
                                        <div class="card-body">
                                            <div class="row">
                                                <?php
                                                $fields = [
                                                    [
                                                        'id' => 'seo_title__seo_page_' . $page['screen'],
                                                        'label' => __('SEO Title'),
                                                        'type' => 'text',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'seo_description__seo_page_' . $page['screen'],
                                                        'label' => __('SEO Description'),
                                                        'type' => 'textarea',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'facebook_title__seo_page_' . $page['screen'],
                                                        'label' => __('Facebook Title'),
                                                        'type' => 'text',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'facebook_description__seo_page_' . $page['screen'],
                                                        'label' => __('Facebook Description'),
                                                        'type' => 'textarea',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'facebook_image__seo_page_' . $page['screen'],
                                                        'label' => __('Facebook Image'),
                                                        'type' => 'upload',
                                                        'translation' => false,
                                                    ],
                                                    [
                                                        'id' => 'twitter_title__seo_page_' . $page['screen'],
                                                        'label' => __('Twitter Title'),
                                                        'type' => 'text',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'twitter_description__seo_page_' . $page['screen'],
                                                        'label' => __('Twitter Description'),
                                                        'type' => 'textarea',
                                                        'translation' => true,
                                                        'std' => $page['label']
                                                    ],
                                                    [
                                                        'id' => 'twitter_image__seo_page_' . $page['screen'],
                                                        'label' => __('Twitter Image'),
                                                        'type' => 'upload',
                                                        'translation' => false,
                                                    ],
                                                ];
                                                $seo_value = get_seo_item_by_post_id($page['screen'], 'seo_page');
                                                foreach ($fields as $field) {
                                                    $key = explode('__', $field['id']);
                                                    $key = $key[0];
                                                    $field['value'] = (!empty($seo_value->$key)) ? $seo_value->$key : '';
                                                    $field = \ThemeOptions::mergeField($field);
                                                    echo \ThemeOptions::loadField($field);
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                            }
                            ?>
                            <div class="clearfix">
                                <button class="btn btn-success btn-has-spinner width-xl waves-effect waves-light"
                                        type="submit"><?php echo __('Save Options'); ?></button>
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
