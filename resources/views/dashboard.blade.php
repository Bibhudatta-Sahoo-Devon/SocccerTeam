<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($admin) && $admin)
                <x-button class="ml-4">
                    <a href="{{route('createTeam')}}">Add Team</a>
                </x-button>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            <li style="color: green;">{{ session()->get('message') }}</li>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="color: red;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(!empty($data) && is_array($data))

                        <table id="teams_table" class="display">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Team</th>
                                <th>Logo</th>
                                <th>Players</th>
                                @if(isset($admin) && $admin)
                                    <th>Edit</th>
                                    <th>Delete</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $count => $team)
                                <tr>
                                    <td>{{$count+1}}</td>
                                    <td>{{$team['name']}}</td>
                                    <td><img src="{{asset($team['logo'])}}" alt="{{$team['logo']}}" width="75"
                                             height="100"></td>
                                    <td><a href="{{route('teamPlayers',$team['id'])}}">View Players</a></td>
                                    @if(isset($admin) && $admin)
                                        <td><a href="{{route('editTeam',$team['id'])}}">Edit</a></td>
                                        <td><a class="deleteButton" href="{{route('deleteTeam',$team['id'])}}">Delete</a></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <span>Welcome to Dashboard, Please create a team!</span>
                    @endif
                </div>


            </div>
        </div>
    </div>

    @section('script')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#teams_table').DataTable({});

                $(".deleteButton").click(function(){
                    if (!confirm("Do you want to delete this team?")){
                        return false;
                    }
                });
            });
        </script>
    @stop

</x-app-layout>
