
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label class="col-3">Formato Predefinido</label>
            <select class="form-control" name="format" multiple>
                <option>Seleccione una opción</option>
                @if($quote->format)
                    <option value="{{$quote->format->id}}" selected>{{$quote->format->name}}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="col-12">
        <textarea data-keyup="hiden-error" id="ckeditor"  class="ckeditor" name="body">
                                        
            @if($quote->format_html)
                {{$quote->format_html}}
            @else
                <div class="cke-page">
                        <div class="cke-content" style="font-size:14px;font-family:Arial,Helvetica,sans-serif">
                        <p style="font-size:14px;font-family:Arial,Helvetica,sans-serif">Redacte texto aquí.</p>
                    </div>
                </div>
            @endif
                
        </textarea>
    </div>
</div>