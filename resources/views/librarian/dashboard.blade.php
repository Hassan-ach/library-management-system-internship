@extends('layouts.app')

@section('title', 'Tableau de bord')

<style>
    .multi-stats .stat-item {
        font-size: 0.85em;
    }

    .info-box-wrapper {
        position: relative;
    }

    .tag_style {
        background: #ababab;
        border-radius: 15px;
        padding: 1px 5px;
        bottom: 5px;
        position: absolute;
        right: -8px;
        font-size: 0.8em;
    }
</style>

@section('content_header')

<div class="row d-flex">
    <!-- books statistics -->
    <div class="col-lg-4 col-6 d-flex">
        <div class="info-box-wrapper w-100 ">
            <x-adminlte-info-box title="" text="" icon="fas fa-book-open" theme="info"
                icon-theme="info" class="w-100">
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
            <x-adminlte-info-box title="" text="" icon="fas fa-clipboard-list" theme="warning" icon-theme="warning" class="w-100">
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
            <x-adminlte-info-box title="" text="" icon="fas fa-info-circle" theme="success"
                icon-theme="success" class="w-100">
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


@stop