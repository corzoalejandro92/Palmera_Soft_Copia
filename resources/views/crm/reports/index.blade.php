<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <section class="row" id="filteContainer">
            @include('layouts.loading.full')  
    </section>
    <div class="form-group">
        <hr>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div id="tabReport" class="relative" style="min-height: 380px;">
            <ul class="nav nav-tabs">
                @include('crm.reports.tabs_header')
            </ul>
            <div class="tab-content px-1 pt-1">
                @include('crm.reports.tabs_content')
            </div>
        </div>
    </div>
</div>