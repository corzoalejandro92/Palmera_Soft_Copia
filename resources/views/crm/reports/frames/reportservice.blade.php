

@section('viewjs3', 'crm/reports/reportservice')

<div role="tabpanel" class="tab-pane active" id="reportservice" aria-expanded="true" aria-labelledby="base-reportservice"> 
  <br><br>
    <div class="row">
      
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="dataTables_length" id="tblList_length">
                    <label style="display: inline-flex;">Mostrar 
                        <select style="position: relative;top: -10px;margin-left: 5px;margin-right: 5px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> 
                        registros
                    </label>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9" align="right">
                    <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                        <label style="display: inline-flex;">Desde:  
                            <input style="position: relative;top: -10px;margin-left: 5px;width: 152px;" type="date" id="startdate" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="">
                            <button class="btn btn-primary" style="position: relative;top: -10px; margin-right: 5px;" onclick="$('#startdate').val('').trigger('change');">X</button>
                        </label>
                        <label style="display: inline-flex;">Hasta:  
                          <input style="position: relative;top: -10px;margin-left: 5px;width: 152px;" type="date" id="enddate" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="">
                          <button class="btn btn-primary" style="position: relative;top: -10px; margin-right: 5px;" onclick="$('#enddate').val('').trigger('change');">X</button>
                      </label>
                      <label style="display: inline-flex;">Buscar:  
                        <input style="position: relative;top: -10px;margin-left: 5px;width: auto;" type="search" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="{{$term ?? ''}}">
                    </label>
                    </div>
            </div>
        <div class="col-12">
          <div  id="reportserviceDivTable">
           
          </div>
        </div>
    </div>
</div>




