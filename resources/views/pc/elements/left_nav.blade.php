<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="/" class="site_title"><i class="fa fa-paw"></i> <span> {{ config('app.name') }} </span></a>
    </div>
    <!-- /menu profile quick info -->
    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <h3>Dashboard</h3>
            <ul class="nav side-menu">
                <li><a><i class="fa fa-list"></i> Posts Management <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ route('posts.index') }}"> Post List</a></li>
                        <li><a href="{{ route('posts.form') }}"> Post Form</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>