@extends('admin.dashboard')

<style>
    .multi-stats .stat-item {
        font-size: 0.85em;
    }

    .info-box-wrapper {
        position: relative;
    }

    .tag_style {
        background: #bdbdbd;
        border-radius: 15px;
        padding: 1px 5px;
        bottom: 5px;
        position: absolute;
        right: -8px;
        font-size: 0.8em;
    }
</style>


@section('content')
    @section('title', 'Tableau de bord')

<style>
    .multi-stats .stat-item {
        font-size: 0.85em;
    }

    .info-box-wrapper {
        position: relative;
    }

    .tag_style {
        background: #bdbdbd;
        border-radius: 15px;
        padding: 1px 5px;
        bottom: 5px;
        position: absolute;
        right: -8px;
        font-size: 0.8em;
    }
</style>

@section('content_header')
<div class="container col-lg-10 col-md-7">
<div class="row d-flex">
    <!-- books statistics -->
    <div class="col-lg-4 col-6 d-flex">
        <div class="info-box-wrapper w-100 ">
            <x-adminlte-info-box title="" text="" icon="fas fa-book-open" theme="info" icon-theme="info" class="w-100">
                <x-slot name="description">
                    <div class="multi-stats mt-8">
                        <div class="stat-item">
                            <span><!--<i class="fas fa-book-open text-primary ml-2"></i>-->Nombre total de
                                livres:</span>
                            <strong class="float-right"> {{ $total_books }} </strong>
                        </div>
                        <div class="stat-item">
                            <span class="flex-fill"><!--<i class="fas fa-check-circle text-success"></i>-->Livre
                                disponibles:</span>
                            <strong class="float-right"> {{ $available_books }} </strong>
                        </div>
                        <div class="stat-item">
                            <span><!--<i class="fas fa-exclamation-triangle text-warning"></i>-->Livre réservés:</span>
                            <strong class="float-right"> {{ $non_available_books }} </strong>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-info-box>
        </div>
    </div>

    <!-- Request_info_1 -->
    <div class="col-lg-4 col-6 d-flex">
        <div class="info-box-wrapper w-100 h-100">
            <x-adminlte-info-box title="" text="" icon="fas fa-clipboard-list" theme="warning" icon-theme="warning"
                class="w-100">
                <x-slot name="description">
                    <div class="multi-stats mt-8">
                        <div class="stat-item">
                            <span><!--<i class="fas fa-clock text-warning"></i>-->Demandes acceptées</span>
                            <strong class="float-right"> {{ $request_statistics->approved_requests }} </strong>
                        </div>
                        <div class="stat-item">
                            <span><!--<i class="fas fa-check text-success"></i>-->Demandes refusées</span>
                            <strong class="float-right"> {{ $request_statistics->rejected_requests }} </strong>
                        </div>
                        <div class="stat-item">
                            <span style="color:#ffc107;">.</span>
                            <strong class="float-right"> {{ " "}}</strong>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-info-box>
            <span class="tag_style">30 derniers jours</span>
        </div>
    </div>

    <!-- Accpted_request_status  -->
    <div class="col-lg-4 col-6 d-flex">
        <div class="info-box-wrapper w-100">
            <x-adminlte-info-box title="" text="" icon="fas fa-info-circle" theme="success" icon-theme="success"
                class="w-100">
                <x-slot name="description">
                    <div class="multi-stats mt-8">
                        <div class="stat-item">
                            <span>Livres empruntés</span>
                            <strong class="float-right"> {{ $request_statistics->borrowed_books }}</strong>
                        </div>
                        <div class="stat-item">
                            <span>Livres en retard</span>
                            <strong class="float-right"> {{ $request_statistics->overdue_requests }}</strong>
                        </div>
                        <div class="stat-item">
                            <span>Livres retournés</span>
                            <strong class="float-right"> {{ $request_statistics->returned_books }}</strong>
                        </div>
                    </div>
                </x-slot>
            </x-adminlte-info-box>
            <span class="tag_style">30 derniers jours</span>
        </div>
    </div>
</div>


    <div class="container-fluid" style="margin-top:25px">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-12 col-md-7 col-sm-12 mb-2">
            <h4>Demandes en attente</h4>

            <div class="table-responsive bg-white">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id demande</th>
                            <th>Titre de livre</th>
                            <th>Etudiant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pending_requests_data as $request)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">{{ $request->id }}</span>
                                </td>
                                <td>
                                    <span>
                                        {{ $request->book_title}}
                                    </span>
                                </td>
                                <td>{{ $request->user_name }}</td>
                                <td>{{ $request->date }}</td>
                            </tr>
                        @endforeach

                        <tr></tr>
                    </tbody>

                </table>
            </div>

            <div class="mt-2 mr-1 text-right">
                <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-primary"
                    style="font-weight:600; ;">
                    voir plus<i style='font-size:13px; vertical-align: middle' class='fas ml-1'>&#xf101;</i>
                </a>
            </div>

        </div>
    </div>
</div>
</div>

@stop

@endsection
