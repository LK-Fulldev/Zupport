<header class="masthead">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="text-center text-white">
                    <h1 class="mb-5">Search restaurant from your address.</h1>
                    <form id="formSearchWord">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="row">
                            <div class="col">
                                <input class="form-control form-control-lg" id="searchWord" name="searchWord"
                                    type="text" placeholder="บางชื่อ.." required>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-lg" id="confimreHidden"
                                    type="submit">ค้นหาเลย</button>
                                <button type="button" class="btn btn-primary" id="confimreSearch" style="display:none;">Yes</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
