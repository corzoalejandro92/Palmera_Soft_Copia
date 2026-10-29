
<div class="row">
    <div class="card-columns">
        @foreach ($service as $services)
            <div class="card card-service"  data-id="<?= $services->id ?>">
                <div class="card-content">
                    <div class="card-body">
                        <div class="media d-flex">
                            <div class="actionButton">
                                @canAny(['services.show','services.create'])
                                <a href="#" data-click="openForm" data-id="<?= $services->id ?>" ><i class="icon-eye "></i></a>
                                @endcanAny
                                @can('services.destroy')
                                <a href="#" data-click="openFormDelete" data-id="<?= $services->id ?>"><i class="icon-close"></i></a>
                                @endcan
                            </div>
                            <div class="align-self-top">
                            <i class="font-large-1 mr-1 {{ $services->avatar}}"></i>
                            </div>
                            <div class="media-body text-left align-self-bottom">
                            <b><span class="d-block mb-1 font-medium-2" style="margin-right: 46px;">{{ $services->name}}</span></b>
                            <h7 class="text-left" style="color: #949494;"><i>{{ $services->description}}</i></h7>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>