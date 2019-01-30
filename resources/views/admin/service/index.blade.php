@extends('admin.layouts.app_admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Список услуг
                @can('service-create')
                <a href="{{route('admin.service.create')}}" class="btn btn-success">
                    <i class="fa fa-plus"></i>
                </a>
                @endcan
            </h1>
        </div>
        @component('admin.components.breadcrumb')
            @slot('parent') Главная @endslot
            @slot('active') Услуги @endslot
        @endcomponent
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span>{{ $message }}</span>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="service" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="service_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc width-40" tabindex="0" aria-controls="service" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID" >ID</th>
                                <th class="sorting" tabindex="0" aria-controls="service" rowspan="1" colspan="1" aria-label="Площадка" >Площадка</th>
                                <th class="sorting" tabindex="0" aria-controls="service" rowspan="1" colspan="1" aria-label="Тип услуги" >Тип услуги</th>
                                <th class="sorting" tabindex="0" aria-controls="service" rowspan="1" colspan="1" aria-label="Имя" >Название</th>
                                <th class="sorting" tabindex="0" aria-controls="service" rowspan="1" colspan="1" aria-label="Цена" >Цена</th>
                                <th class="width-100"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($services as $service)
                                <tr role="row" class="@if($loop->iteration  % 2 == 0) even @else odd @endif">
                                    <td class="sorting_1">{{$service->id}}</td>
                                    <td>{{$service->businessCenter->name}}</td>
                                    <td>{{$service->serviceType->name}}</td>
                                    <td>{{$service->name}}</td>
                                    <td>{{$service->price}}</td>
                                    <td>
                                        <form onsubmit="if(confirm('Удалить?')) { return true } else { return false }"
                                              action="{{route('admin.service.destroy', $service)}}" method="post">
                                            <input type="hidden" name="_method" value="DELETE">
                                            {{ csrf_field() }}
                                            @can('service-list')
                                            <a class="btn btn-info" href="{{route('admin.service.show', $service)}}"><i class="fa fa-eye"></i></a>
                                            @endcan
                                            @can('service-edit')
                                            <a class="btn btn-warning" href="{{route('admin.service.edit', $service)}}"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('service-delete')
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
