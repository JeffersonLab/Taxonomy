<!-- {{-- Generic Bootstrap Modal Blade Template --}} -->
<div class="modal fade" id="{{$modal_id}}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">{{$modal_title}}</h4>
            </div>
            <div class="modal-notice"></div>

            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>