<a class="btn btn-success waves-effect waves-light" href="javascript:void(0)" data-toggle="modal"
   data-target="#hh-add-new-term-modal">
    <i class="ti-plus mr-1"></i>
    {{__('Create New')}}
</a>
<div id="hh-add-new-term-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="form form-action form-add-term-modal form-translation"
                  data-validation-id="form-add-term"
                  action="{{ dashboard_url('add-new-term') }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Create New')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body relative">
                    @include('common.loading')
                    <?php
                    show_lang_section('mb-2');
                    $langs = get_languages_field();
                    ?>
                    <div class="form-group">
                        <label for="term_name">{{__('Name')}}</label>
                        @foreach($langs as $key => $item)
                            <input type="text" class="form-control has-validation {{get_lang_class($key, $item)}}"
                                   data-validation="required" id="term_name{{get_lang_suffix($item)}}"
                                   name="term_name{{get_lang_suffix($item)}}" @if(!empty($item)) data-lang="{{$item}}"
                                   @endif
                                   placeholder="{{__('Name')}}">
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="term_icon">
                            {{__('Home Facilities')}}
                        </label>
                        <?php
                        $facilities_list = get_terms('home-facilities'); ?>
                        <div class="item-filter-wrapper" id="home-facilities">
                            <?php foreach ($facilities_list as $key => $value) { ?>
                                    <div class="label">{{ $value['title'] }}</div>
                                    <?php $idName = str_replace(' ', '-', str_replace(['[', ']'], '_', $value['title']));?>
                                    <div class="content">
                                        <div class="row">
                                            <?php $sub_val = json_decode($value['selection_val']);
                                                if(!empty($sub_val)){
                                                    foreach ($sub_val as $item) { ?>
                                                        <div class="col-lg-4 mb-1">
                                                            <div class="item checkbox  checkbox-success ">
                                                                <input type="checkbox" value="{{$item}}" onchange="checkFacility('add')"
                                                                    id="{{$idName}}_{{$item}}"/>
                                                                <label
                                                                    for="{{$idName}}_{{$item}}">{{ $item }}</label>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }
                                            ?>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <input type="hidden" name="taxonomy_name"
                           value="home-advanced">
                    <input type="hidden" name="term_select" id="facility_val"
                            >
                    <button type="submit"
                            class="btn btn-info waves-effect waves-light">{{__('Create New')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
