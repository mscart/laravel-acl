@extends('admin.app')
@section('page_title') @lang('acl::acl.name') @endsection
@section('pagetitle') @lang('acl::add_role.add_role') @endsection
@section('breadcrumb')
  <a href="{{ route('acl.list_roles') }}" class="breadcrumb-item"> @lang('acl::acl.name') </a>
  <span class="breadcrumb-item active">@lang('acl::add_role.add_role')</span>
@endsection
@section('content')
<div class="card">
	<div class="card-header header-elements-inline">
		<h5 class="card-title">@lang('acl::add_role.add_role')</h5>
	</div>
	<div class="card-body">
		<form action="{{ route("acl.store")}}" method="post" id="add_role" class="validate">
		{{ csrf_field() }}
		<div class="form-group">
			<label>@lang('acl::add_role.role_name'):</label>
			<input name="role_name" id="role_name" type="text" class="form-control" placeholder="@lang('acl::add_role.role_name')" required="">
		</div>
		<div class="row">
			@php($nr=0)
			@foreach ($data as $key => $package)
			@php($nr++)
			<div class="col-md-3">
				<div class="form-group">
					<label>@lang($package['name'])</label>
					<select name="permissions[]" class="form-control multiselect" multiple="multiple" data-fouc>
						@foreach ($package['permissions'] as $key => $permision)
						<option value="{{ $permision }}">@lang($permision)</option>
						@endforeach
					</select>
				</div>
			</div>
			@if($nr==4)
		</div>
		<div class="cold-md-3">
			@php($nr=0)
			@endif
			@endforeach
		</div>
		<div class="d-flex justify-content-end align-items-center">
			<button type="reset" class="btn btn-light" id="reset">@lang('admin/general.reset') <i class="icon-reload-alt ml-2"></i></button>
			<button type="submit" class="btn btn-primary ml-3">@lang('admin/general.submit') <i class="icon-paperplane ml-2"></i></button>
		</div>
		</form>
	</div>
</div>
@endsection
@section('custom_js')
<script src="{{ asset('backend/js/plugins/forms/validation/validate.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
  <script src="{{ asset('backend/js/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ asset('backend/vendor/mscart/acl/assets/js/add_role.js') }}"></script>
<script src="{{ asset('backend/js/plugins/forms/validation/localization/messages_'.App::getLocale().'.js') }}" type="text/javascript"></script>
@endsection
