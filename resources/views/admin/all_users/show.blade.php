@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>View User Details</h4>
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
                <table class="table table-sm table-hover">
                    <tr>
                        <td style = "width: 500px;">ID</td>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Birth Date</td>
                        <td>{{ $user->birthdate }}</td>
                    </tr>
                    <tr>
                        <td>Age</td>
                        <td>{{ $user->age }}</td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            @if(empty($user->gender))
                                No Gender Found
                            @else
                                {{ ucfirst($user->gender) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Profile Image</td>
                        <td>
                            @if(!empty($user->profile_image))
                                <img src="{{ asset($user->profile_image) }}" alt="Profile Image" style = "width: 80px; height: 60px;">
                            @else
                                No Image Found
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>
                            @if(empty($user->city))
                                No City Found
                            @else
                                {{ $user->city }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Zodiac</td>
                        <td>
                            @if(!empty($user->zodiac))
                                {{ $user->zodiac }}
                            @else
                                No Zodiac Found
                            @endif                 
                        </td>
                    </tr>
                    <tr>
                        <td>Sexual Preference</td>
                        <td>
                            @if(empty($user->sexual_preference))
                                No Sexual Preference Found
                            @else
                                {{ $user->sexual_preference }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Profile Completion</td>
                        <td>{{ $user->profile_completion }}</td>
                    </tr>
                    <tr>
                        <td>Is Active User</td>
                        <td>{{ ucfirst($user->is_active_user) }}</td>
                    </tr>
                    <tr>
                        <td>How Tall User is</td>
                        <td>{{ $user->how_tall_are_you }}</td>
                    </tr>
                    <tr>
                        <td>Profile Verification Document</td>
                        <td>
                            @if(empty($user->profile_verification_document))
                                No Profile Verification Document Found
                            @else
                                {{ $user->profile_verification_document }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Is Profile Verified</td>
                        <td>{{ ucfirst($user->is_profile_verified) }}</td>
                    </tr>
                    <tr>
                        <td>Job Title</td>
                        <td>
                            @if(empty($user->job_title))
                                No Job Found
                            @else
                                {{ $user->job_title }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>
                            @if(empty($user->company_name))
                                No Company Found
                            @else
                                {{ $user->company_name }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>University or School</td>
                        <td>
                            @if(empty($user->university_or_school))
                                No University or School Found
                            @else
                                {{ $user->university_or_school }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Self Description</td>
                        <td>
                            @if(empty($user->self_description))
                                No Self Description Found
                            @else
                                {{ $user->self_description }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Silhouetee</td>
                        <td>
                            @if(empty($user->silhouetee))
                                No Silhouetee Found
                            @else
                                {{ $user->silhouetee }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Like to have Child</td>
                        <td>
                            @if(empty($user->like_to_have_child))
                                No Data Found
                            @else
                                {{ $user->like_to_have_child }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Smoke</td>
                        <td>
                            @if(empty($user->do_you_smoke))
                                No Data Found
                            @else
                                {{ $user->do_you_smoke }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Drink</td>
                        <td>
                            @if(empty($user->do_you_drink))
                                No Data Found
                            @else
                                {{ $user->do_you_drink }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Ethnicity</td>
                        <td>
                            @if(empty($user->ethnicity))
                                No Ethnicity Found
                            @else
                                {{ $user->ethnicity }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Why User using app</td>
                        <td>
                            @if(empty($user->why_you_use_app))
                                No Data Found
                            @else
                                {{ $user->why_you_use_app }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tag 1</td>
                        <td>
                            @if(empty($user->tag_1))
                                No Tag Found
                            @else
                                {{ $user->tag_1 }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tag 2</td>
                        <td>
                            @if(empty($user->tag_2))
                                No Tag Found
                            @else
                                {{ $user->tag_2 }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tag 3</td>
                        <td>
                            @if(empty($user->tag_3))
                                No Tag Found
                            @else
                                {{ $user->tag_3 }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection