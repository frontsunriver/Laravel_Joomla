<div id="hh-get-qrcode-modal" class="modal fade hh-get-modal-content" tabindex="-1" role="dialog"
     aria-hidden="true"
     data-url="{{ dashboard_url('get-qrcode') }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form class="form form-action form-get-qrcode-modal relative"
                  data-validation-id="form-update-coupon"
                  action="{{ dashboard_url('update-coupon-item') }}">
                @include('common.loading')
                <div class="modal-header">
                    <h4 class="modal-title">{{__('QR Code')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
