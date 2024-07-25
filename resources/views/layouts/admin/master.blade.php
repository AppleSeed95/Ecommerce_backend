@include('layouts.admin.head')
<style type="text/css">
    
    .toast-header i{font-size: 16px;margin-right: 5px;}
    .preloader{background-color: #d0cecea3;}

</style>
@yield('cssStyle')
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

      <!-- Preloader -->
      <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="dest/img/AdminLTELogo.png" alt="Loading..." height="60" width="60">
    </div>
    @include('layouts.admin.nav')
    @include('layouts.admin.sidebar')


    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?php if(!empty($pageTitle)){echo $pageTitle;} ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @foreach ($breadcrumb as $link)
                            <?php 
                                if( $link['link'] =='' ){
                                    $cls = 'active';
                                    $url = '#';
                                }else{
                                    $cls = '';
                                    $url = $link['link'];
                                }
                                ?>
                                <li class="breadcrumb-item {{$cls}}"><a href="{{$url}}">{{$link['title']}}</a></li>
                            @endforeach
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
    

    <!-- <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: relative; min-height: 200px;">
        <div class="toast-header">
            <img src="..." class="rounded mr-2" alt="...">
            <strong class="mr-auto">Bootstrap</strong>
            <small class="text-muted">11 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div> -->




@include('layouts.admin.footer')
</body>
</html>