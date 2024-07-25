<!-- /.content-wrapper -->
<footer class="main-footer">
	<strong>Copyright &copy; 2014-2021 <a href="#">{{ $footerName ?? 'CMS Manager'}}</a>.</strong>
	All rights reserved.
	<div class="float-right d-none d-sm-inline-block">
		
	</div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
@if(Auth::check())
    <script>
        var token = "{{ Auth::user()->api_token }}";
        $(function($){
            $.ajaxSetup({
                headers:{
                    'Authorization': 'Bearer {{ Auth::user()->api_token }}',
                    'X-CSFR-TOKEN': '{{csrf_token()}}'
                },
                error: function(xhr, status, error){
                    if( xht.status == 403){
                        alert('Sorry, Session is expired.');
                    }else{
                        alert('An Error occurred: '+status+ ' Error: '+error);
                        console.log('An Error occurred: '+status+ ' Error: '+error);
                    }
                }
            });
        });

    </script>
    @endif
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
	$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{ asset('js/demo.js')}}"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="/js/pages/dashboard.js"></script> -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-fixedcolumns\js\dataTables.fixedColumns.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4\js\dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive\js\dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive\js\responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons\js\dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons\js\buttons.flash.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons\js\buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons\js\buttons.print.min.js')}}"></script>

<script>
	$(function(){
		// let toastOptions = {
		// 	animation:true,
		// 	autohide:true,
		// 	delay:3000,
		// }
		// $('.toast').toast(toastOptions);
		// $('#sucess').toast('show');
		// $('#error').toast('show');
		// $('#info').toast('show');
		// $('#warning').toast('show');
	});
	var tableElem={};

</script>
@yield('script')
