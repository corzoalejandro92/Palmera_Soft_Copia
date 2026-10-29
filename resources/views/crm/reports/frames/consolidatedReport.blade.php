<div role="tabpanel" class="tab-pane" id="consolidated" aria-expanded="false" aria-labelledby="base-consolidated"> 
    <div class="row">
        <div class="col-12">
            <div class="card"  style="box-shadow: 0px 0px 15px #ccc!important; padding:10px;">
                <div class="card-header">
                    <form id="consolidated-form">
                        <div class="form-row align-items-center">
                          <div class="col-auto">
                            <label for="inlineFormInput">Inicio</label>
                            <input name="star_date" type="date" class="form-control mb-2" id="inlineFormInput" placeholder="Inicio" value="{{date('Y-m-01')}}">
                          </div>
                          <div class="col-auto">
                            <label for="inlineFormInputGroup">Fin</label>
                            <div class="input-group mb-2">
                              {{-- <div class="input-group-prepend">
                                <div class="input-group-text">@</div>
                              </div> --}}
                              <input name="end_date" type="date" class="form-control" id="inlineFormInputGroup" placeholder="Fin" value="{{date('Y-m-t')}}">
                            </div>
                          </div>
                          {{-- <div class="col-auto">
                            <div class="form-check mb-2">
                              <input class="form-check-input" type="checkbox" id="autoSizingCheck">
                              <label class="form-check-label" for="autoSizingCheck">
                                Remember me
                              </label>
                            </div>
                          </div> --}}
                          <div class="col-6" style="margin-top:26px;">
                            <div class="row">
                              <div class="col-2">
                                <button data-click="consolidatedReport" type="button" class="btn btn-primary mb-2">Filtrar</button>
                              </div>
                              <div class="col-1">
                              </div>
                              <div class="col-2">
                                @if(Auth::user()->roles->pluck('slug')->contains('root') || Auth::user()->roles->pluck('slug')->contains('admin'))
                                  <button data-click="RegenerateconsolidatedReport" type="button" class="btn btn-info mb-2">Regenerar</button>
                                @endif
                              </div>
                              <div class="col-1">
                              </div>
                              <div class="col-3" id="divDownloadConsolidated">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    <h4 class="card-title">Previsualizacion del informe</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body" id="embed"></div>
                </div>
            </div>
        </div>
    </div>
</div>