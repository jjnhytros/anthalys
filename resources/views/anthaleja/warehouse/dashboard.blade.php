@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mt-4">Warehouse Dashboard</h1>
        <div class="row">
            <!-- Droni -->
            <div class="col-md-6">
                <h3>Drones Status</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Drone ID</th>
                            <th>Type</th>
                            <th>Battery Life</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drones as $drone)
                            <tr>
                                <td>{{ $drone->id }}</td>
                                <td>{{ ucfirst($drone->type) }}</td>
                                <td>{{ $drone->battery_life }}%</td>
                                <td>{{ ucfirst($drone->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Robot -->
            <div class="col-md-6">
                <h3>Robots Status</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Robot ID</th>
                            <th>Type</th>
                            <th>Battery Life</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($robots as $robot)
                            <tr>
                                <td>{{ $robot->id }}</td>
                                <td>{{ ucfirst($robot->type) }}</td>
                                <td>{{ $robot->battery_life }}%</td>
                                <td>{{ ucfirst($robot->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Magazzini -->
            <div class="col-md-12">
                <h3>Warehouses Status</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Warehouse ID</th>
                            <th>Location</th>
                            <th>Current Stock</th>
                            <th>Capacity</th>
                            <th>Automation Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td>{{ $warehouse->id }}</td>
                                <td>{{ $warehouse->location }}</td>
                                <td>{{ $warehouse->current_stock }}</td>
                                <td>{{ $warehouse->capacity }}</td>
                                <td>{{ $warehouse->automation_level }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
