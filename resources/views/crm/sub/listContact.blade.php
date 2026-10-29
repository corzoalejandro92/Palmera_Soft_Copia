<table class="table text-inputs-searching table-bordered zero-configuration" style="width:100%!important;" id="list">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Cargo</th>
            <th>Aut.</th>
            <th>Email</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Nombre</th>
            <th>Telefono</th>
            <th>Cargo</th>
            <th>Aut.</th>
            <th>Email</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </tfoot>
    <tbody>
 
        @foreach ($contacts as $contact)
            <th scope="row">{{ $contact->name}} @if($contact->offices->count() == 0) <span class="badge badge-warning">Sin sucursal</span> @endif</th>
                <td>{{$contact->phone}}</td>
                <td>{{$contact->position}}</td>
                <td>@foreach ($contact->types as $type)
                    <span class="badge badge-info" style="margin-bottom: 3px;">{{ $type->name }}</span>
                @endforeach
                </td>
                <td>{{$contact->email}}</td>
                
                <td>
                    <div class="text-center">
                        @can('crms.info.admin')
                            <a href="#" data-click="openEditContact" data-id="<?= $contact->id ?>"><i class="icon-pencil mx-0"></i></a>
                        @endcan
                        @can('crms.info.destroy')
                            <a href="#" data-click="openFormDeleteContact" data-id="<?= $contact->id ?>"><i class="icon-trash mx-1 text-danger"></i></a>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>