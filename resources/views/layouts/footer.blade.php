<script>
  $(document).ready(function () {
    window.viewer_modal = function ($src = '') {
      start_loader();
      var t = $src.split('.').pop();
      var view = (t === 'mp4')
        ? $("<video src='" + $src + "' controls autoplay></video>")
        : $("<img src='" + $src + "' />");

      $('#viewer_modal .modal-content video, #viewer_modal .modal-content img').remove();
      $('#viewer_modal .modal-content').append(view);
      $('#viewer_modal').modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      });
      end_loader();
    }

    window.uni_modal = function ($title = '', $url = '', $size = '') {
      start_loader();
      $.ajax({
        url: $url,
        error: err => {
          console.log(err);
          alert("An error occurred");
          end_loader();
        },
        success: function (resp) {
          if (resp) {
            $('#uni_modal .modal-title').html($title);
            $('#uni_modal .modal-body').html(resp);

            // Limpia cualquier clase anterior relacionada al tamaño
            $('#uni_modal .modal-dialog').removeClass('modal-sm modal-md modal-lg modal-xl');

            // Aplica nuevo tamaño si corresponde
            if ($size !== '') {
              $('#uni_modal .modal-dialog').addClass('modal-' + $size);
            } else {
              $('#uni_modal .modal-dialog').addClass('modal-md');
            }

            // Muestra el modal
            $('#uni_modal').modal({
              show: true,
              backdrop: 'static',
              keyboard: false,
              focus: true
            });

            end_loader();
          }
        }
      });
    };


    window._conf = function ($msg = '', $func = '', $params = []) {
      $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
      $('#confirm_modal .modal-body').html($msg);
      $('#confirm_modal').modal('show');
    }
  });
</script>

<footer class="main-footer text-sm bg-gradient-navy text-light">
  <strong>Copyright © {{ date('Y') }}.
  </strong> Derechos Reservados.
  <div class="float-right d-none d-sm-inline-block">
    <b>{{ system_info('short_name') }} </b>
    v1.0
  </div>
</footer>

<!-- ./wrapper -->

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

@yield('scripts')