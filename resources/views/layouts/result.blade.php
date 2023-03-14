
    @if (count($googleData) > 0)
    <div id="contentLoader">
        @foreach ($googleData as $item)
            @php
                // Check Types & Convert Data Type
                $typesElement = '';
                $myStringArray = str_replace('[', '', $item->types);
                $arrayLength = str_replace(']', '', $myStringArray);
                $resultConvertArray = explode(',', $arrayLength);

                for ($i = 0; $i < count($resultConvertArray); $i++) {
                    $dataResult = str_replace('"', '', $resultConvertArray[$i]);
                    $typesElement .= ' <span>' . $dataResult . '</span><span class="text-primary"> • </span>';
                }

                // Check Star
                $checkRatingStar = (int) $item->rating;
                switch ($checkRatingStar) {
                    case 1:
                        $starElement = '<span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>';
                        break;
                    case 2:
                        $starElement = '<span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>';
                        break;
                    case 3:
                        $starElement = '<span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>';
                        break;
                    case 4:
                        $starElement = '<span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star"></span>';
                        break;
                    case 5:
                        $starElement = '<span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>
                                        <span class="fa fa-star" style=" color: orange; "></span>';
                        break;
                    default:
                        $starElement = '<span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>';
                        break;
                }
            @endphp
            <div class="row justify-content-center mb-3">
                <div class="col-md-12 col-xl-10">
                    <div class="card shadow-0 border rounded-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                    <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                        <img src="{{ $item->photos }}" class="w-100"
                                            style=" width: 225px !important; height: 225px; " />
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-6">
                                    <h5>{{ $item->name }}</h5>
                                    <div class="d-flex flex-row">
                                        <div class="mb-1 me-2"> @php echo $starElement; @endphp
                                        </div>
                                        <span> {{ $item->user_ratings_total }} </span>
                                    </div>
                                    <div class="mt-1 mb-0 text-muted small"> @php echo $typesElement; @endphp
                                    </div>
                                    <p class="text-truncate mb-4 mb-md-0">
                                        {{ $item->formatted_address }} </p>
                                </div>

                                <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                    <div class="d-flex flex-row align-items-center mb-1">
                                    </div>
                                    <div class="d-flex flex-column mt-4">
                                        <a href="#" class="btn btn-primary btn-sm" type="button"><i
                                                class="fa fa-location-arrow" aria-hidden="true"></i> เยี่ยมชม</a>
                                        <button class="btn btn-outline-primary btn-sm mt-2" type="button"> <i
                                                class="fa fa-phone" aria-hidden="true"></i> โทรสอบถาม
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row justify-content-center mb-3">
        <div class="d-flex align-items-center justify-content-center" id="pagination">
            {!! $googleData->links() !!}
        </div>
    </div>
    @else
        <div class="row justify-content-center mb-3">
            <div class="col-md-12 col-xl-10 ">
                <h4>Sorry data not found !!</h4>
            </div>
        </div>
    @endif
    <input type="hidden" value="{{ $keySearchName }}" name="keySearchName" id="keySearchName">

