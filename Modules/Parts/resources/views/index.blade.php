@extends('parts::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header">Parts</h1>
        </div>
        <div class="col-md-4">
            <a class="btn btn-primary pull-right" href="">Create part</a>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <th>Part uuid</th>
            <th>Manufacturer Name</th>
        </thead>
        <tbody>
            <?php foreach($parts as $part): ?>
                <tr>
                    <td>{{ $part->manufacturedPartId  }}</td>
                    <td>{{ $part->manufacturerName }}</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
@stop
