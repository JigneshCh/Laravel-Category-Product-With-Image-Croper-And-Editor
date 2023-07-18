<div class="row ">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
            <label for="first_name" class="">
                <span class="field_compulsory">*</span>@lang('user.label.first_name')
            </label>
            {!! Form::text('first_name', null, ['class' => 'form-control','autocomplete'=>"false"]) !!}
            {!! $errors->first('first_name', '<p class="help-block text-danger">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
            <label for="last_name" class="">
                <span class="field_compulsory">*</span>@lang('user.label.last_name')
            </label>
            {!! Form::text('last_name', null, ['class' => 'form-control','autocomplete'=>"false"]) !!}
            {!! $errors->first('last_name', '<p class="help-block text-danger">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
            <label for="role">
                <span class="field_compulsory">*</span>@lang('user.label.role')
            </label>
            <div>
                {!! Form::select('roles[]',$roles,$selected_role, ['class' => 'full-width selectTag2','autocomplete'=>"false"]) !!}
                {!! $errors->first('roles', '<p class="help-block text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            <label for="email" class="">
                <span class="field_compulsory">*</span>@lang('user.label.email')
            </label>
            @if(isset($user))
            {!! Form::email('email', null, ['class' => 'form-control', 'disabled' => 'disabled','autocomplete'=>"false"]) !!}
            @else
            {!! Form::email('email', null, ['class' => 'form-control','autocomplete'=>"false"]) !!}
            @endif
            {!! $errors->first('email', '<p class="help-block text-danger">:message</p>') !!}
        </div>
        @if(isset($user))
        <div class="form-group  {{ $errors->has('enable_password') ? 'has-error' : ''}}">
            <label for="enable_password" class="">
                @lang('user.label.change_password')
            </label><br />
            <input type="checkbox" value="1" name="enable_password" class="enable_password form-control switchery" />
        </div>
        @endif
        <div class="form-group if_pasenabled {{ $errors->has('password') ? 'has-error' : ''}}">
            <label for="password" class="">
                @if(!isset($user))
                <span class="field_compulsory">*</span>
                @endif
                @lang('common.label.password')
            </label>
            {!! Form::password('password', ['class' => 'form-control','autocomplete'=>"new-password"]) !!}
            {!! $errors->first('password', '<p class="help-block text-danger">:message</p>') !!}
        </div>

        <?php
        $selected_q = [];
        $selected_e = [];
        if (isset($user)) {
            $selected_q = $user->qualificationList();
            $selected_e = $user->experienceList();
        }
        if (count($selected_q) <= 0) {
            $selected_q = [""];
        }
        if (count($selected_e) <= 0) {
            $selected_e = [""];
        }
        ?>
        <div class="multi-group {{ $errors->has('qualification') ? 'has-error' : ''}}">
            @foreach($selected_q as $q)
            <div class="form-group ">
                <label for="password" class="title">
                    @lang('user.label.qualification')
                </label>
                <label class="action_btn pull-right">
                    <a href="#" class="add_row"><i style="padding: 8px;background-color: black;border-radius: 50%;" class='fa fa-plus'></i></a>
                    <a href="#" class="remove_row"><i style="padding: 8px;background-color: black;border-radius: 50%;" class='fa fa-minus'></i></a>
                </label>
                {!! Form::text('qualification[]', $q,['placeholder'=>trans('user.label.qualification'),'class' => 'form-control','autocomplete'=>"false"]) !!}
            </div>
            @endforeach
            {!! $errors->first('qualification', '<p class="help-block text-danger">:message</p>') !!}
        </div>
        <div class="multi-group {{ $errors->has('experience') ? 'has-error' : ''}}">
            @foreach($selected_e as $e)
            <div class="form-group ">
                <label for="password" class="title">
                    @lang('user.label.experience')
                </label>
                <label class="action_btn pull-right">
                    <a href="javascript:void(0)" class="add_row"><i style="padding: 8px;background-color: #7235a2;border-radius: 50%;" class='fa fa-plus'></i></a>
                    <a href="javascript:void(0)" class="remove_row"><i style="padding: 8px;background-color: #7235a2;border-radius: 50%;" class='fa fa-minus'></i></a>
                </label>
                {!! Form::text('experience[]',$e,['placeholder'=>trans('user.label.experience'),'class' => 'form-control','autocomplete'=>"false"]) !!}
            </div>
            @endforeach
            {!! $errors->first('experience', '<p class="help-block text-danger">:message</p>') !!}
        </div>
        <div class="form-group {{ $errors->has('profilephoto') ? 'has-error' : ''}}">
            <label for="profilephoto" class="">
                <span class="field_compulsory">*</span>
                @lang('user.label.signature')
            </label>
            <input type="file" id="inputImage" name="profilephoto" class="form-control" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
            {!! $errors->first('profilephoto', '<p class="help-block text-danger">:message</p>') !!}
            <input type="hidden" id="outputImage" name="signature_base64" class="form-control">
            <img src="" class="signature-preview" height="100" />
            <div class="clearfix cropping_tool cropping_tool-container" style="width:100%;display:none">
                <div class="col-md-12 " style="width:100%">
                    <div class="img-container" style="width:100%">
                        <img id="image" src="" alt="Picture" style="width:100%">
                    </div>
                </div>
                <a href="#" class="btn btn-raised btn-secondary  crop_btn" data-method="getCroppedCanvas" style="width:100%"> @lang('user.label.crop_signature')</a>
            </div>
        </div>
        <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
            @if(isset($user) && $user->signature)
            <div class="row">
                @php( $rf=$user->signature )
                @if($rf->file_url && $rf->file_url != "")
                <div class="relative-container">
                    <a href="{{url('admin/reference-file/'.$rf->id.'/delete')}}" onclick="return confirm('@lang('common.js_msg.confirm_for_delete',['item_name'=>trans('common.label.file')])')" class="close btn btn-danger btn-sm"><i class='fa fa-trash' aria-hidden='true'></i></a>
                    <a class="example-image-link " href="{!! $rf->file_url !!}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
                        <img src="{!! $rf->file_url !!}" class="" height="80" />
                    </a>
                </div>
                @else
                @endif
            </div>
            @else
            @endif
        </div>
        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
            {!! Form::label('status', trans('common.label.status'), ['class' => '']) !!}
            {!! Form::select('status',trans('common.active_status'), null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group image_submit_btn">
            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('common.label.create'), ['class' => ' btn btn-primary']) !!}
            {{ Form::reset(trans('common.label.clear_form'), ['class' => 'btn btn-light']) }}
        </div>
    </div>
</div>
@push('js')

<script src="{!! asset('js/cropper/cropper.js') !!}"></script>
<script src="{!! asset('js/cropper/main.js') !!}"></script>

<script>
    @if(isset($user))
    $(".if_pasenabled").css("display", "none");
    $(".enable_password ").change(function() {
        if (this.checked) {
            $(".if_pasenabled").css("display", "block");
        } else {
            $(".if_pasenabled").css("display", "none");
        }
    });
    @endif
    $('.selectTag2').select2({
        tokenSeparators: [",", " "]
    });

    $(document).on('click', '.add_row', function(e) {
        var rootc = $(this).parents('.multi-group');
        var rowdata = $(this).parents('.form-group');
        $(rootc).append("<div class='form-group'>" + rowdata.html() + "</div>");
        $(rootc).children(".form-group").last().find("input[type=text]").val("");
        return false;
    });

    $(document).on('click', '.remove_row', function(e) {
        var rowdata = $(this).parents('.form-group');
        rowdata.remove();
        return false;
    });
</script>
@endpush