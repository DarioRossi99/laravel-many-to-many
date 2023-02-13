@extends('layouts.app')

@section('content')

<div class="container">
<h1 class="my-3">Lists Project</h1>

<a class="btn btn-warning my-3" href="{{route('admin.projects.create')}}">Crea un nuovo progetto</a>
  <table class="table">
    <thead>
      <tr>
        <th>Id</th>
        
        <th>Cover Img</th>
        <th>Name</th>
        <th>Technologies</th>
        <th>Project Type</th>
        <th>Description</th>
        <th>Github link</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($projects as $project)
        <tr>
          <td>{{ $project->id }}</td>
          <td><img src="{{ asset('/storage/' . $project->cover_img)  }}" alt="" style="width: 80px; height: 50px; object-fit:cover; object-position: top  "></td>
          <td>{{ $project->name }}</td>
          {{-- $post->tags = Una collection (array) di istanze del model Tag.
            Tutti i tag associati al post corrente --}}
            <td>
          @foreach ($project->technologies as $technology)
            {{ $technology->name . ' ' }}
          @endforeach
          </td>
          <td>{{ $project->type ? $project->type->name : ' ' }}</td>
          <td>{{ $project->description }}</td>
          <td>{{ $project->github_link }}</td>
          
          <td>
            <div class="d-flex gap-2 ">
              <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-primary">E</a>
              <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-info">S</a>
              <form action="{{route('admin.projects.destroy', $project->id)}}" method="POST">
                @csrf()
                @method('delete')
                <button class="btn btn-danger">D</button>
              </form>
            </div>
            
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>




</div>

@endsection