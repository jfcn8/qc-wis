{{-- <h1 class="h3 mb-2 text-gray-800">Article</h1> --}}

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Article</h6>
        @if (in_array('Add', $permissions))
            <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#newArticle">New</button>
        @endif
        @if (in_array('Generate', $permissions))
            <button class="btn btn-sm btn-primary mt-1 float-right" wire:click="export()">Export</button>
        @endif
        
        
    </div>
    <div class="card-body">
        @include('livewire.layouts.loading')

        <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" wire:model="searchKey" class="form-control" placeholder="Article, Classification">
        </div>


        <div>
            @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Classification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if ($articles->count() > 0)
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ $article->article }}</td>
                                <td>{{ $article->classification }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info"  wire:click="viewArticleDetails({{ $article->article_id }})">View</button>
                                    @if (in_array('Edit', $permissions))
                                        <button class="btn btn-sm btn-outline-primary" wire:click="getArticle({{ $article->article_id }})">Edit</button>
                                    @endif
                                    @if (in_array('Delete', $permissions))
                                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteConfirmation({{ $article->article_id }})">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="3">
                            <div class="not-found">
                                <img src="{{ asset('img/no-record-found.gif') }}" alt="">
                            </div>
                        </td>
                    </tr>
                    @endif
                    
                </tbody>
            </table>
            {{ $articles->links() }}
        </div>
             <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="newArticle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveArticle">
                           

                            <div class="form-group">
                                <label for="classification_id">Classification</label>
                                <select class="form-control" wire:model="classification_id">
                                    <option value="">Select Classification</option>
                                    @foreach ($classifications as $classification)
                                        <option value="{{ $classification->classification_id }}">{{ $classification->classification }}</option>
                                    @endforeach
                                </select>
                                @error('classification_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="article">Article</label>
                                <input type="text" wire:model="article" class="form-control" id="article" autocomplete="off" placeholder="Item Article">
                                @error('article')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-sm btn-outline-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="editArticle" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Update Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateArticle">
                            

                            <div class="form-group">
                                <label for="classification_id">Classification</label>
                                <select class="form-control" wire:model="classification_id">
                                    <option value="">Select Classification</option>
                                    @foreach ($classifications as $classification)
                                        <option value="{{ $classification->classification_id }}">{{ $classification->classification }}</option>
                                    @endforeach
                                </select>
                                @error('classification_id')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="article">Article</label>
                                <input type="text" wire:model="article" class="form-control" id="article" autocomplete="off" placeholder="Item Article">
                                @error('article')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deleteArticle" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Delete Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                       <h5>Are you sure you want to delete this record?</h5>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-outline-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="deleteArticle()">Yes! Delete</button>
                    </div>
                </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="viewArticle" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Article</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $article_name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Classification</th>
                                        <td>{{ $classification_name }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>


        
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('close-modal' , event => {
            $('#newArticle').modal('hide');
            $('#editArticle').modal('hide');
            $('#deleteArticle').modal('hide');
            $('#viewArticle').modal('hide');
        });

        window.addEventListener('show-edit-article-modal' , event => {
            $('#editArticle').modal('show');
        });

        window.addEventListener('show-delete-article-modal' , event => {
            $('#deleteArticle').modal('show');
        });

        window.addEventListener('show-article-modal' , event => {
            $('#viewArticle').modal('show');
        });
    </script>
@endpush
  
 