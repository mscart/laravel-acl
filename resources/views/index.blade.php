@extends('admin.app')
@section('page_title') @lang('acl::acl.name') @endsection
@section('pagetitle') @lang('acl::acl.name') @endsection
@section('breadcrumb')
  <span class="breadcrumb-item active">@lang('acl::acl.name')</span>
@endsection


@section('content')
  <div class="card">
  <div class="card-header header-elements-inline">
    <h5 class="card-title">@lang('acl::acl.role_list')</h5>
    <div class="header-elements">
      <div class="list-icons-item">
        @can('acl::role.delete')
        <button class="btn bg-danger " style="display:none;"  id="delete_selected">
          <span><i class="icon-trash"></i> @lang('admin/general.actions.delete_selected')</span>
        </button>
        @endcan
      </div>
      <div class="list-icons list-icons-item" id="buttons"></div>

      </div>
  </div>
<table id="browse_acl_roles" class="table data_table ">
  <thead>
    <tr>
      <th></th>
       <th title="Name">@lang('acl::browse.name')</th>
       <th title="Guard">@lang('acl::browse.guard_name')</th>
       <th title="Actions" class="text-center">@lang('acl::browse.actions')</th>
    </tr>
    <tr>
        <th width="1%">
                <input type="checkbox" class="check_all " id="check_all"/>

        </th>

        <th width="60%" class="searchable "></th>
        <th width="20%" class="searchable "></th>
        <th width="5%" ></th>

    </tr>
  </thead>
</table>
</div>

@include('admin.partials.modal_delete')
@endsection

@section('custom_js')
@include('admin.partials.data_table_vars')
<script src="{{ asset('backend/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>

  <script src="{{ asset('backend/vendor/mscart/acl/assets/js/browse.js') }}"></script>
  <script>
    var report_title = 'Roles report';
    var ajax_url = "{{ route('acl.destroy','delete')}}";
    var getRoles_route = " {{ route('acl.getRoles') }}"
</script>
@endsection
