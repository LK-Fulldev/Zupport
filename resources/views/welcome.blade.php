@extends('layouts.app')
@section('title', config('app.name', 'Zupports'))
@section('style')
    <style>
        .pagination {
            float: right;
            margin-top: 10px;
        }

        .wrapper-loader {
            top: 50%;
            left: 50%;
            width: 300px;
            text-align: center;
            transform: translateX(-50%);
        }

        .spanner-loader {
            position: absolute;
            left: 0;
            margin-top: 8%;
            background: #2a2a2a55;
            width: 100%;
            display: block;
            text-align: center;
            height: 425px;
            color: #FFF;
            transform: translateY(-50%);
            z-index: 1000;
            visibility: hidden;
        }

        .overlay-loader {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            visibility: hidden;
        }

        .loader-loader,
        .loader-loader:before,
        .loader-loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation: load7 1.8s infinite ease-in-out;
            animation: load7 1.8s infinite ease-in-out;
        }

        .loader-loader {
            color: #ffffff;
            font-size: 10px;
            margin: 80px auto;
            position: relative;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }

        .loader-loader:before,
        .loader-loader:after {
            content: '';
            position: absolute;
            top: 0;
        }

        .loader-loader:before {
            left: -3.5em;
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }

        .loader-loader:after {
            left: 3.5em;
        }

        @-webkit-keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        @keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        .show {
            visibility: visible;
        }

        .spanner-loader,
        .overlay-loader {
            opacity: 0;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .spanner-loader.show,
        .overlay-loader.show {
            opacity: 1
        }
    </style>
@endsection
@section('content')
    <section>
        <div class="container py-4">
            @include('layouts.result')
        </div>
    </section>

    <div class="spanner-loader">
        <div class="loader-loader"></div>
    </div>

@endsection
@section('script')
    <!-- Main Script Dashboard -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        // Pagination
        $(document).on('click', '.pagination a', function(event) {
            // Reset HTML
            $('#contentLoader').html("");
            $('#pagination').html("");
            // Loader
            $("div.spanner-loader").addClass("show");
            $("div.overlay-loader").addClass("show");
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var wordDefault = $('#keySearchName').val();
            $.ajax({
                url: "{{ url('/pagination') }}",
                method: 'GET',
                data: {
                    _token: "{{ csrf_token() }}",
                    search_word: wordDefault,
                    limit: 10,
                    page: page
                },
                success: function(data) {
                    // Loader
                    $('#contentLoader').append(data.googleData);
                    $(".spanner-loader").hide();
                    $(".overlay-loader").hide();
                }
            });
        });
        // Search Key Word
        $(document).ready(function() {

            $("form").submit(function(e) { // Check required with form search
                $('#confimreHidden').attr('type', 'button');
                $("#confimreSearch").click();
                e.preventDefault();
            });

            $('#confimreSearch').click(function(e) { // Function Confirmed Search
                // Reset HTML
                $('#contentLoader').html("");
                $('#pagination').html("");
                $('#keySearchName').val("");
                // Loader
                $("div.spanner-loader").addClass("show");
                $("div.overlay-loader").addClass("show");
                $.ajax({
                    url: "{{ url('api/googlerestaurants') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        search_word: $("#searchWord").val(),
                        limit: 10,
                        page: 1
                    },
                    success: function(response) {
                        var list = response.data.data;

                        $.each(list, function(i, list) {
                            // Check Types
                            let typesElement = "";
                            var myStringArray = list.types.replaceAll('[', "");
                            var arrayLength = myStringArray.replaceAll(']', "");
                            var resultConvertArray = arrayLength.split(",");

                            for (var i = 0; i < resultConvertArray.length; i++) {
                                var dataResult = resultConvertArray[i].replaceAll('"',
                                    '');
                                typesElement += ' <span>' + dataResult + '</span>' +
                                    '<span class="text-primary"> • </span>';
                            }
                            // Check Star
                            var checkRatingStar = parseInt(list.rating);
                            switch (checkRatingStar) {
                                case 1:
                                    var starElement =
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>';
                                    break;
                                case 2:
                                    var starElement =
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>';
                                    break;
                                case 3:
                                    var starElement =
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>';
                                    break;
                                case 4:
                                    var starElement =
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star"></span>';
                                    break;
                                case 5:
                                    var starElement =
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>' +
                                        '<span class="fa fa-star" style=" color: orange; "></span>';
                                    break;
                                default:
                                    var starElement =
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>';
                                    break;
                            }
                            var row = '<div class="row justify-content-center mb-3">';
                            row = row +
                                '<div class="col-md-12 col-xl-10">' +
                                '<div class="card shadow-0 border rounded-3">' +
                                '<div class="card-body">' +
                                '<div class="row">' +
                                '<div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">' +
                                '<div class="bg-image hover-zoom ripple rounded ripple-surface">' +
                                '<img src="' + list.photos +
                                '" class="w-100"  style=" width: 225px !important; height: 225px; "/>' +
                                '</div></div>' +
                                '<div class="col-md-6 col-lg-6 col-xl-6">' +
                                '<h5>' + list.name + '</h5>' +
                                '<div class="d-flex flex-row">' +
                                '<div class="mb-1 me-2">' + starElement +
                                '</div>' +
                                '<span>' + list.user_ratings_total + '</span>' +
                                '</div>' +
                                '<div class="mt-1 mb-0 text-muted small">' +
                                typesElement +
                                '</div>';
                            row = row + '<p class="text-truncate mb-4 mb-md-0">' +
                                list.formatted_address + '</p>' +
                                '</div>';
                            row = row +
                                '<div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">' +
                                '<div class="d-flex flex-row align-items-center mb-1">' +
                                '</div>' +
                                '<div class="d-flex flex-column mt-4">' +
                                '<a href ="#" class="btn btn-primary btn-sm" type="button"><i class="fa fa-location-arrow" aria-hidden="true"></i> เยี่ยมชม</a>' +
                                '<button class="btn btn-outline-primary btn-sm mt-2" type="button"> <i class="fa fa-phone" aria-hidden="true"></i> โทรสอบถาม' +
                                '</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                            row = row + "</div>";
                            $('#contentLoader').append(row);
                        });
                        // Display the pagination links
                        $('#pagination').append(response.links);
                        $('#keySearchName').val($("#searchWord").val());
                        // Loader
                        $(".spanner-loader").hide();
                        $(".overlay-loader").hide();
                    },
                    error: function(response) {
                        console.log('ERROR');
                        console.log(response);
                        // Loader
                        $(".spanner-loader").hide();
                        $(".overlay-loader").hide();
                    }
                });
            });
        });
    </script>
@endsection
