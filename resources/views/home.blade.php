@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">DataTable with minimal features & hover style</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Rendering engine</th>
                      <th>Browser</th>
                      <th>Platform(s)</th>
                      <th>Engine version</th>
                      <th>CSS grade</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Trident</td>
                      <td>Internet
                        Explorer 4.0
                      </td>
                      <td>Win 95+</td>
                      <td> 4</td>
                      <td>X</td>
                    </tr>
                    <tr>
                      <td>Trident</td>
                      <td>Internet
                        Explorer 5.0
                      </td>
                      <td>Win 95+</td>
                      <td>5</td>
                      <td>C</td>
                    </tr>
                    <tr>
                      <td>Trident</td>
                      <td>Internet
                        Explorer 5.5
                      </td>
                      <td>Win 95+</td>
                      <td>5.5</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Trident</td>
                      <td>Internet
                        Explorer 6
                      </td>
                      <td>Win 98+</td>
                      <td>6</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Trident</td>
                      <td>Internet Explorer 7</td>
                      <td>Win XP SP2+</td>
                      <td>7</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Trident</td>
                      <td>AOL browser (AOL desktop)</td>
                      <td>Win XP</td>
                      <td>6</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Gecko</td>
                      <td>Firefox 1.0</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.7</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Gecko</td>
                      <td>Firefox 1.5</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.8</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Gecko</td>
                      <td>Firefox 2.0</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.8</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Gecko</td>
                      <td>Firefox 3.0</td>
                      <td>Win 2k+ / OSX.3+</td>
                      <td>1.9</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Gecko</td>
                      <td>Camino 1.0</td>
                      <td>OSX.2+</td>
                      <td>1.8</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td>Misc</td>
                      <td>PSP browser</td>
                      <td>PSP</td>
                      <td>-</td>
                      <td>C</td>
                    </tr>
                    <tr>
                      <td>Other browsers</td>
                      <td>All others</td>
                      <td>-</td>
                      <td>-</td>
                      <td>U</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                      <th>Rendering engine</th>
                      <th>Browser</th>
                      <th>Platform(s)</th>
                      <th>Engine version</th>
                      <th>CSS grade</th>
                    </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
  
              
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop