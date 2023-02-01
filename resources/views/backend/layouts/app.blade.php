<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.1.0/ui/trumbowyg.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css" integrity="sha512-Zi7Hb6P4D2nWzFhzFeyk4hzWxBu/dttyPIw/ZqvtIkxpe/oCAYXs7+tjVhIDASEJiU3lwSkAZ9szA3ss3W0Vug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" />

    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('assets/css/core.css') }}" data-hs-appearance="default" as="style">
    <link rel="stylesheet" href="{{ asset('assets/css/backend/app.css') }}" data-hs-appearance="default" as="style">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    @yield('css_content')
</head>

<body>
    @include('backend.layouts.navbars.navbar')
    <div class="content-wrap">
        <div class="container-fluid">
            <div class="row">
                @include('backend.layouts.navbars.sidebar')
                <div class="col-lg-10 col-sm-12">
                    <!-- Content -->
                    <div class="content py-3">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        @yield('content')
                    </div>
                    @include('backend.layouts.footer.nav')
                </div>
            </div>
        </div>
    </div>
    @stack('js')
    <!-- ========== END SECONDARY CONTENTS ========== -->

    <!-- JS Global Compulsory  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.4.0/jquery-migrate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- JS Global Compulsory
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
    -->

    <!-- JS Front -->
    <!--<script src="{{ asset('assets/js/theme.min.js') }}"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/trumbowyg.min.js" integrity="sha512-ZfWLe+ZoWpbVvORQllwYHfi9jNHUMvXR4QhjL1I6IRPXkab2Rquag6R0Sc1SWUYTj20yPEVqmvCVkxLsDC3CRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        function deletevarient(id) {
            $('#variantproduct-' + id).remove();
        }

        function selectFileFromManager(id, preview) {
            $('#fileManagerPreview').attr('src', preview);
            $('#fileManagerId').val(id);
            $('#CallFilesModal').modal('hide')
            return false;
        }

        function selectFileFromManagerModel(id) {
            $('#fileManagerModelId').val(id);
            $('#CallFilesModal').modal('hide')
        }

        function selectFileFromManagerAsset(id) {
            $('#digital_download_assets').val(id);
            $('#CallFilesModal').modal('hide')
        }

        function uploadAjax(is_model, is_product) {
            var files = $("#prepare_images").get(0).files[0];
            var formData = new FormData()
            formData.append('file', files);
            formData.append("_token", "{{ csrf_token() }}")
            formData.append("is_model", is_model)
            formData.append("is_product", is_product)

            jQuery.ajax({
                type: 'POST',
                url: "{{ route('backend.filemanager.ajaxupload') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $('#modelmanagerAppend').html(data);
                    $('#media-tab').trigger('click')
                }
            })

        }

        function uploadPrepareAjax(is_model, is_product) {
            $("#prepare_images").trigger('click');
        }

        function productImageDiv(id, preview) {
            var div = '<div id="fileappend-' + id + '" class="col-6 col-sm-4 col-md-3 mb-3 mb-lg-5">' +
                '<div class="card card-sm">' +
                '<img class="card-img-top" src="' + preview + '" alt="Image Description">' +

                '<div class="card-body">' +
                '<div class="row col-divider text-center">' +
                '<div class="col">' +
                '<a class="text-body" href="./assets/img/1920x1080/img3.jpg" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-fslightbox="gallery" data-bs-original-title="View">' +
                '<i class="bi-eye"></i>' +
                '</a>' +
                '</div>' +


                '<div class="col">' +
                '<a onclick="removepreviewappended(' + id +
                ')" class="text-danger" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Delete">' +
                '<i class="bi-trash"></i>' +
                '</a>' +
                '</div>' +
                '</div>' +

                '</div>' +
                '</div>' +
                '</div>';
            return div;
        }

        jQuery(document).ready(function() {
            $(document).on("click", ".modal-body li a", function() {
                tab = $(this).attr("href");
                $(".modal-body .tab-content div").each(function() {
                    $(this).removeClass("active");
                });
                $(".modal-body .tab-content " + tab).addClass("active");
            });

            $('#attributes').on('change', function() {
                var attributes = $(this).val()
                $.ajax({
                    type: 'POST',
                    url: "{{ route('backend.products.attributes.ajaxcall') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "attributes": attributes
                    },
                    success: (data) => {
                        $('#product_attribute_values').html(data);
                    }
                })
            })

            var getVariants = function(isDigital, productId) {
                var values_selected = $('#product_attribute_values').val();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('backend.products.attributes.combinations') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "values": values_selected,
                        'isDigital': isDigital,
                        product_id: productId
                    },
                    success: function(result) {
                        $('#variantsbody').html(result)
                    }
                })
            }

            $('#generatevariants').on('click', function() {
                getVariants($('#availabilitySwitch1').prop('checked') * 1, $(this).attr('data-product-id'));
            })

        });
        $('#variant').on('change', function() {

            jQuery.ajax({
                url: "{{ route('backend.products.attributes.getvalues') }}",
                method: 'post',
                data: {
                    'id_attribute': $(this).val(),
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'HTML',
                success: function(result) {
                    $('#variantsbody').html(result)
                }
            });
        })
    </script>

    @yield('js_content')
</body>

</html>
