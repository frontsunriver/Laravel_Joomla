@include('dashboard.components.header')
<div id="wrapper">
    @include('dashboard.components.top-bar')
    @include('dashboard.components.nav')
    <div class="content-page">
        <div class="content mt-2">
            {{--Start Content--}}
            @include('dashboard.components.breadcrumb', ['heading' => __('All Add-ons')])
            <div id="extensions-management">
                <?php
                $extensions_path = public_path('addons');
                if (!is_dir($extensions_path)) {
                ?>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="alert alert-warning mb-5">
                            <?php echo sprintf(__('Can not create shortcut for Add-ons folder.<br/> Please read this document to resolve it: <a href="%s" target="_blank">How to create shortcut for Add-ons folder</a>'), 'https://docs.awebooking.org/faqs/how-to-create-shortcut-for-add-ons-folder') ?>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <?php
                global $hh_extensions;
                ?>
                @if(!empty($addons) || is_object($addons))
                    <div class="row">
                        <?php
                        foreach($addons as $key => $extension){
                        $class = '';
                        $version = '';
                        $slug = $extension->name;
                        if ($hh_extensions) {
                            foreach ($hh_extensions as $_slug => $_extension) {
                                if ($slug === $_slug) {
                                    $class = 'installed';
                                    $version = $_extension['Version'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="col-md-6 col-xl-3">
                            <form action="{{ dashboard_url('action-extension') }}" class="form-action relative"
                                  data-validation-id="form-extensions">
                                <input type="hidden" name="extension_url" value="{{$extension->download_link}}">
                                @include('common.loading')
                                <div class="card has-matchHeight card-addon card-addon-{{$extension->post_status}}">
                                    @if($extension->post_status == 'draft')
                                        <span
                                            class="card-status {{$extension->post_status}}">{{$extension->post_status}}</span>
                                    @endif
                                    <span class="card-version">{{$extension->version}}</span>
                                    <img class="img-fluid" src="{{ $extension->thumbnail }}"
                                         alt="{{ $extension->post_title }}">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $extension->post_title }}</h4>
                                        <p class="card-text font-italic text-muted">{{ $extension->description }}</p>
                                        <input type="hidden" name="extension" value="{{$slug}}">
                                        <div class="d-flex justify-content-between">
                                            <div class="left">
                                                @if($class == 'installed' && version_compare($version, $extension->version) == -1)
                                                    <input type="hidden" name="update" value="1">
                                                    <button class="btn btn-info btn-xs"
                                                            type="submit">{{__('Update')}}</button>
                                                @endif
                                            </div>
                                            <div class="right">
                                                @if($class == 'installed')
                                                    <input type="hidden" name="action" value="delete">
                                                    <button class="btn btn-danger btn-xs"
                                                            type="submit">{{__('Uninstall')}}</button>
                                                @elseif(!in_array($extension->post_status, ['draft', 'trash']))
                                                    <input type="hidden" name="action" value="install">
                                                    <button class="btn btn-success btn-xs"
                                                            type="submit">{{__('Install')}}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- end col-->
                        <?php } ?>
                    </div>
                @else
                    <h4>{{__('Not Extensions yet')}}</h4>
                @endif
            </div>
            {{--End content--}}
            @include('dashboard.components.footer-content')
        </div>
    </div>
</div>
@include('dashboard.components.footer')
