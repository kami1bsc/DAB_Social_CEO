@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>View User Requests</h4>
                @if(session()->has('message'))
                    <div class="alert alert-success text-center">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-warning text-center">
                        {{ session()->get('error') }}
                    </div>
                @endif
            </div>            
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                @if($users->count() > 0)
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>                            
                            <th>Gender</th>
                            <th>Image</th>
                            <th>Verify</th>
                            <th>Show</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>                                    
                                    <td>{{ ucfirst($user->gender) }}</td>                                    
                                    <td><img src="{{ asset($user->profile_image) }}" alt="Profile Image" style = "width: 60px; height: 40px;"></td>
                                    <td>
                                        <a href="{{ route('admin.all_users.verify_user', $user->id) }}" class = "btn btn-sm btn-success"><i class = "fa fa-check"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.all_users.show', $user->id) }}" class = "btn btn-sm btn-info"><i class = "fa fa-eye"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.all_users.destroy', $user->id) }}" method = "POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type = "submit" onclick = "return confirm('Are You Sure To Want to Delete?')" name = "submit" class = "btn btn-sm btn-danger"><i class = "fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                @else
                    <h4 style = "text-align:center">No User Request Found!</h4>
                @endif
            </div>
        </div>
    </div>
@endsection