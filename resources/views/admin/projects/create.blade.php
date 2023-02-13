@extends('layouts.app')

<h1 class="text-center text-info py-5">questo é il create</h1>

@section('content')
<div class="container">
  <h1>New Project</h1>
  
  <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
    @csrf()

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{$error}}</li>       
          @endforeach
        </ul>
      </div>
      @endif

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" value="{{old('name')}}" >
    </div>

    @foreach ($technologies as $technology)
      <div class="form-check form-check-inline @error('technologies') is-invalid @enderror">
        {{-- Il name dell'input ha come suffisso le quadre [] che indicheranno al server,
              di creare un array con i vari tag che stiamo inviando --}}
        <input class="form-check-input @error('technologies') is-invalid @enderror" type="checkbox"
          id="technologyCheckbox_{{ $loop->index }}" value="{{ $technology->id }}" name="technologies[]"
          {{ in_array( $technology->id, old('technologies', [])) ? 'checked' : '' }}
          >
        <label class="form-check-label" for="technologyCheckbox_{{ $loop->index }}">{{ $technology->name }}</label>
      </div>
    @endforeach

    <div class="mb-3">
      <label class="form-label">type</label>
      <select class="form-select" name="type_id">
        @foreach ($types as $type)

          <option value="{{$type->id}}">{{$type->name}}</option>

        @endforeach
        

      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" cols="30" rows="5" class="form-control">{{old('description')}}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Cover image</label>
      <input type="file" class="form-control" name="cover_img">
    </div>
    

    <div class="mb-3">
      <label class="form-label">Github link</label>
      <input type="text" class="form-control" name="github_link" value="{{old('github_link')}}" >
    </div>

    
    

    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Annulla</a>
    <button class="btn btn-primary">Save</button>
  </form>

</div>

@endsection