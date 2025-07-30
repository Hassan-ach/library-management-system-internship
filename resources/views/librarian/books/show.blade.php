@extends('layouts.app')

@section('title', 'Détails du livre: ' . $title)

@section('content_header')
    <h1 class="m-0 text-dark">Détails du livre</h1>
@stop
@section('js')
    @parent
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Informations sur le livre" theme="primary" icon="fas fa-book-reader" >
                <div class="card-body" style="//border: black solid 1px,">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3" style="height: 350px; max-width: 300px;">

                            @if ($image_url)
                                <img src="{{ $image_url }}" class="card-img-top" alt="{{ $title }} Cover" style=" object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex justify-content-center align-items-center bg-light" style="height: 350px; width: 100%;">
                                    <i class="fas fa-book fa-6x text-black-50"></i>
                                </div>
                            @endif

                            <h4 class="mt-3" style="font-weight : 600;" >{{ $title }}</h4>
                        </div>

                        <div class="col-md-8">
                            <dl class="row" style="padding: 10px">
                                <dt class="col-sm-4">ISBN:</dt>
                                <dd class="col-sm-8">
                                    {{ $isbn }}
                                </dd>

                                <dt class="col-sm-4">Auteur(s):</dt>
                                <dd class="col-sm-8">
                                    @if( $authors["old"])
                                        @foreach($authors['old'] as $author)
                                            {{ $author['name'] }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @else
                                        {{ "N/A" }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Éditeur(s):</dt>
                                <dd class="col-sm-8">
                                    @if( $publishers['old'])
                                        @foreach($publishers['old'] as $publisher)
                                            {{ $publisher['name'] }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @else
                                        {{ "N/A" }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Année de publication:</dt>
                                <dd class="col-sm-8">{{ $publication_date ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Nombre de pages:</dt>
                                <dd class="col-sm-8">{{ $number_of_pages ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Catégorie(s):</dt>
                                <dd class="col-sm-8">
                                    @if( $categories['old'])
                                        @foreach($categories['old'] as $category)
                                            <span class="badge badge-info">{{ $category['name'] }}</span>{{ !$loop->last ? ' ' : '' }}
                                        @endforeach
                                    @else
                                        {{ "N/A" }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Etiquettes:</dt>
                                <dd class="col-sm-8">
                                    @if( $tags['old'])
                                        @foreach($tags['old'] as $tag)
                                            <span class="badge badge-secondary">{{ $tag['name'] }}</span>{{ !$loop->last ? ' ' : '' }}
                                        @endforeach
                                    @else
                                        {{ "N/A" }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Nombre total de copies:</dt>
                                <dd class="col-sm-8">{{ $total_copies }}</dd>

                                <dt class="col-sm-4">Nombre de copies disponibles</dt>
                                <dd class="col-sm-8">{{ $available_copies }}</dd>

                                <dt class="col-sm-4" >Description:</dt>
                                <dd class="col-sm-8"></dd>
                                <dd class="col-sm" style='margin: 10px'>
                                    <p style="text-align: justify">{{ $description ?? 'Ce livre ne dispose pas encore de description.' }}</p>
                                </dd>

                            </dl>

                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex  justify-content-between align-items-center " style="margin:15px">
                    <div >
                        <a href="{{ route('librarian.books.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('librarian.books.edit', $book) }}">
                            <button type="submit" class="btn btn-secondary mr-3" >
                                <i class="fas fa-pencil-alt pr-2"></i> Modifier
                            </button>
                        </a>
                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" id="#deleteModal" >
                            <i class="fas fa-trash pr-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
     <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression du livre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce livre :
                    <strong>{{ $title }}</strong> ?
                    <p class="text-danger mt-2">Une fois supprimé, le livre ne pourra plus être restauré.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>

                    <form id="deleteForm" method="POST" action="{{ route('librarian.books.delete', $book) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

