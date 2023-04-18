    <style>
        .modal-content{
            overflow: scroll;
            height: 90vh;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">

    <style>
        .preview-container {
            /* display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px; */
            display: grid;
            grid-template-columns: repeat(auto-fill, 170px);
        }

        .preview {
            position: relative;
            width: 150px;
            height: 150px;
            padding: 4px;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            margin: 30px 0px;
            border: 1px solid #ddd;
        }

        .preview img {
            width: 100%;
            height: auto;
            box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            object-fit: cover;

        }

        .delete-btn {
            position: absolute;
            top: 156px;
            right: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: transparent;
            color: rgba(235, 32, 38, 0.97);
        }

        .crop-btn {
            position: absolute;
            top: 156px;
            left: 0px;
            /*border: 2px solid #ddd;*/
            border: none;
            cursor: pointer;
            background: transparent;
            color: #007bff;
        }
    </style>

    <style>
        .variants {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .variants>div {
            margin-right: 5px;
        }

        .variants>div:last-of-type {
            margin-right: 0;
        }

        .file {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>input[type='file'] {
            display: none
        }

        .file>label {
            font-size: 1rem;
            font-weight: 300;
            cursor: pointer;
            outline: 0;
            user-select: none;
            border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
            border-style: solid;
            border-radius: 4px;
            border-width: 1px;
            background-color: hsl(0, 0%, 100%);
            color: hsl(0, 0%, 29%);
            padding-left: 16px;
            padding-right: 16px;
            padding-top: 16px;
            padding-bottom: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .file>label:hover {
            border-color: hsl(0, 0%, 21%);
        }

        .file>label:active {
            background-color: hsl(0, 0%, 96%);
        }

        .file>label>i {
            padding-right: 5px;
        }

        .file--upload>label {
            color: hsl(204, 86%, 53%);
            border-color: hsl(204, 86%, 53%);
        }

        .file--upload>label:hover {
            border-color: hsl(204, 86%, 53%);
            background-color: hsl(204, 86%, 96%);
        }

        .file--upload>label:active {
            background-color: hsl(204, 86%, 91%);
        }

        .file--uploading>label {
            color: hsl(48, 100%, 67%);
            border-color: hsl(48, 100%, 67%);
        }

        .file--uploading>label>i {
            animation: pulse 5s infinite;
        }

        .file--uploading>label:hover {
            border-color: hsl(48, 100%, 67%);
            background-color: hsl(48, 100%, 96%);
        }

        .file--uploading>label:active {
            background-color: hsl(48, 100%, 91%);
        }

        .file--success>label {
            color: hsl(141, 71%, 48%);
            border-color: hsl(141, 71%, 48%);
        }

        .file--success>label:hover {
            border-color: hsl(141, 71%, 48%);
            background-color: hsl(141, 71%, 96%);
        }

        .file--success>label:active {
            background-color: hsl(141, 71%, 91%);
        }

        .file--danger>label {
            color: hsl(348, 100%, 61%);
            border-color: hsl(348, 100%, 61%);
        }

        .file--danger>label:hover {
            border-color: hsl(348, 100%, 61%);
            background-color: hsl(348, 100%, 96%);
        }

        .file--danger>label:active {
            background-color: hsl(348, 100%, 91%);
        }

        .file--disabled {
            cursor: not-allowed;
        }

        .file--disabled>label {
            border-color: #e6e7ef;
            color: #e6e7ef;
            pointer-events: none;
        }

        @keyframes pulse {
            0% {
                color: hsl(48, 100%, 67%);
            }

            50% {
                color: hsl(48, 100%, 38%);
            }

            100% {
                color: hsl(48, 100%, 67%);
            }
        }
    </style>

<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Admin\ProductClassController@store'), 'method' => 'post', 'id' => $quick_add ? 'quick_add_product_class_form' : 'product_class_add_form', 'files' => true]) !!}

        <div class="modal-header">

            <h4 class="modal-title">@lang( 'lang.add_category' )</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <x-adminlte-input name="name" label="{{ __('lang.name') }}" placeholder="{{ __('lang.name') }}"
                    enable-old-support>
                    <x-slot name="appendSlot">
                        <div class="input-group-text text-primary translation_btn"  data-type="product_class">
                            <i class="fas fa-globe"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            @include('admin.partial.translation_inputs', [
                'attribute' => 'name',
                'translations' => [],
                'type' => 'product_class',
            ])
            <input type="hidden" name="quick_add" value="{{ $quick_add }}">
            <div class="form-group">
                {!! Form::label('description', __('lang.description') . ':') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('lang.description'), 'rows' => 3]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('sort', __('lang.sort') . ':*') !!}
                {!! Form::number('sort', 1, ['class' => 'form-control', 'placeholder' => __('lang.sort'), 'required']) !!}
            </div>


            
            <div class="container mt-3">
                  {!! Form::label('image', __('lang.image'), []) !!}
                <div class="row mx-0" style="border: 1px solid #ddd;padding: 30px 0px;">
                    <div class="col-12">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <div class="variants">
                                        <div class='file file--upload w-100'>
                                            <label for='file-input' class="w-100">
                                                <i class="fas fa-cloud-upload-alt"></i>Upload
                                            </label>
                                            <!-- <input  id="file-input" multiple type='file' /> -->
                                            <input type="file" id="file-input" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-10 offset-1">
                        <div class="preview-container"></div>
                    </div>
                </div>
            </div>
        
            <div id="cropped_images"></div>
      
            <div class="form-group mt-3">
                {!! Form::label('status', __('lang.status'), []) !!}
                {!! Form::checkbox('status', 1, true, ['class']) !!}
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'lang.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'lang.close' )</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="croppie-modal" style="display:none">
                                <div id="croppie-container"></div>
                                <button data-dismiss="modal" id="croppie-cancel-btn" type="button" class="btn btn-secondary"><i
                                        class="fas fa-times"></i></button>
                                <button id="croppie-submit-btn" type="button" class="btn btn-primary"><i
                                        class="fas fa-crop"></i></button>
                            </div>
                        </div>
        
                    </div>
                </div>
            </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>


<script>
  const fileInput = document.querySelector('#file-input');
    const previewContainer = document.querySelector('.preview-container');
    const croppieModal = document.querySelector('#croppie-modal');
    const croppieContainer = document.querySelector('#croppie-container');
    const croppieCancelBtn = document.querySelector('#croppie-cancel-btn');
    const croppieSubmitBtn = document.querySelector('#croppie-submit-btn');


    fileInput.addEventListener('change', () => {
        previewContainer.innerHTML = '';
        let files = Array.from(fileInput.files)

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.match('image.*')) {
                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    const preview = document.createElement('div');
                    preview.classList.add('preview');
                    const img = document.createElement('img');
                    const actions = document.createElement('div');
                    actions.classList.add('action_div');
                    img.src = reader.result;
                    preview.appendChild(img);
                    preview.appendChild(actions);

                    const container = document.createElement('div');
                    const deleteBtn = document.createElement('span');
                    deleteBtn.classList.add('delete-btn');
                    deleteBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-trash"></i>';
                    deleteBtn.addEventListener('click', () => {
                    
                        if (window.confirm('Are you sure you want to delete this image?')) {
                            files.splice(file, 1)
                            preview.remove();
                            getImages()
                        }
                    });

                    preview.appendChild(deleteBtn);
                    const cropBtn = document.createElement('span');
                    cropBtn.setAttribute("data-toggle", "modal")
                    cropBtn.setAttribute("data-target", "#exampleModal")
                    cropBtn.classList.add('crop-btn');
                    cropBtn.innerHTML = '<i style="font-size: 20px;" class="fas fa-crop"></i>';
                    cropBtn.addEventListener('click', () => {
                      
                        setTimeout(() => {
                            launchCropTool(img);
                        }, 500);
                    });
                    preview.appendChild(cropBtn);
                    previewContainer.appendChild(preview);
                });
                reader.readAsDataURL(file);
            }
        }

        getImages()
    });
    function launchCropTool(img) {
        // Set up Croppie options
        const croppieOptions = {
            viewport: {
                width: 200,
                height: 200,
                type: 'square' // or 'square'
            },
            boundary: {
                width: 300,
                height: 300,
            },
            enableOrientation: true
        };

        // Create a new Croppie instance with the selected image and options
        const croppie = new Croppie(croppieContainer, croppieOptions);
        croppie.bind({
            url: img.src,
            orientation: 1,
        });

        // Show the Croppie modal
        croppieModal.style.display = 'block';

        // When the user clicks the "Cancel" button, hide the modal
        croppieCancelBtn.addEventListener('click', () => {
    
            croppieModal.style.display = 'none';
            $('#exampleModal').modal('hide');
            croppie.destroy();
        });

        // When the user clicks the "Crop" button, get the cropped image and replace the original image in the preview
        croppieSubmitBtn.addEventListener('click', () => {
        
            croppie.result('base64').then((croppedImg) => {
                img.src = croppedImg;
                croppieModal.style.display = 'none';
                $('#exampleModal').modal('hide');
                croppie.destroy();
                getImages()
            });
        });
    }
    function getImages() {
        setTimeout(() => {
            const container = document.querySelectorAll('.preview-container');
            let images = [];
            $("#cropped_images").empty();
            for (let i = 0; i < container[0].children.length; i++) {
                images.push(container[0].children[i].children[0].src)
                var newInput = $("<input>").attr("type", "hidden").attr("name", "image").val(container[0].children[i].children[0].src);
                $("#cropped_images").append(newInput);
            }
            return images
        }, 500);
    }

</script>

    <script>
        $("[name='status']").bootstrapSwitch();
    </script>
