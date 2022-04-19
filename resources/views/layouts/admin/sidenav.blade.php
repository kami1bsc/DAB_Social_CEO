<div id="sidebar-wrapper" style = "margin-top: 11px;">
      <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
        <li class="active">
          <a href="{{ route('admin.dashboard') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-dashboard fa-stack-1x "></i></span> Dashboard</a>
          <!-- <ul class="nav-pills nav-stacked" style="list-style-type:none;">
            <li><a href="#">link1</a></li>
            <li><a href="#">link2</a></li>
          </ul> -->
        </li>
        <li>
          <a href=""><span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i></span> Users &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<i class = "fa fa-chevron-down"></i></a>
          <ul class="nav-pills nav-stacked" style="list-style-type:none;">
            <li><a href="{{ route('admin.all_users.index') }}"><i class = "fa fa-user"></i>&emsp;User Requests</a></li>
            <li><a href="{{ route('admin.all_users.verified_users') }}"><i class = "fa fa-user"></i>&emsp;Verified Users</a></li>
          </ul>
        </li>
        <!-- <li>
          <a href="{{ route('admin.all_users.index') }}"><span class="fa-stack fa-lg pull-left"><i class="fa fa-users fa-stack-1x "></i></span>Users</a>
        </li>         -->
        <!-- <li>
          <a href=""><span class="fa-stack fa-lg pull-left"><i class="fa fa-list fa-stack-1x "></i></span>Categories</a>
        </li>
        <li>
          <a href=""><span class="fa-stack fa-lg pull-left"><i class="fa fa-list fa-stack-1x "></i></span>Boards</a>
        </li> 
        <li>
          <a href=""><span class="fa-stack fa-lg pull-left"><i class="fa fa-pencil fa-stack-1x "></i></span>Posts</a>
        </li>  
        <li>
          <a href="" onclick = "alert('Comming Soon!')" ><span class="fa-stack fa-lg pull-left"><i class="fa fa-money fa-stack-1x "></i></span>Transactions</a>
        </li>    -->
      </ul>
    </div>