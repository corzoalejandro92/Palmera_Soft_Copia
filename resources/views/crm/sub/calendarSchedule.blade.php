<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
 ?>

<div class="card">

    <div class="card-content collapse show">
        <div class="card-header">
            <div class="heading-elements visible" style="z-index:1000;margin-top: 0px;">
                <ul class="list-inline mb-0">
                    <li>
                        <a data-action="expand">
                            <i class="ft-maximize" style="font-size: 30px;"></i>
                        </a>
                    </li>
                </ul>

        </div>
        <div class="card-body" style="margin-top: -30px;">
            <div class="card-text">
                <div class="row">

                    <div class="col-md-12">
                        <a name="tabSchedule"></a>
                        <div id='agenda'></div>
                    </div>
                    <div class="col-md-12">
                        &nbsp;
                    </div>
                    <div class="col-md-12" align="center">
                        <fieldset style="    border: 1px solid #f2f2f2;
                        border-radius: 10px;">
                            <legend style="width: auto;margin-left: 14px;">Estados de los servicios</legend>
                            @foreach (\App\CrmScheduleStatus::get() as $status)
                                <span style="height: 13px;width: 13px;display: inline-flex;border-radius: 50%;background-color: {{$status->color}}"></span> <span class="mr-3">{{$status->name}}</span>
                            @endforeach
                            <br><br>
                        </fieldset>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>