<?php
namespace MsCart\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Activitylog\Traits\LogsActivity;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\Package;

use Datatables;

class AclController extends Controller
{

    use LogsActivity;

    /**
     * Show the roles list
     * @return
     */
    public function index()
    {
        $this->authorize('acl::role.read');
        return view('acl::index');
    }
    /**
     * Get roles from database and prepare de restults for datatable Js
     * @return json [description]
     */
    public function getRoles()
    {
        $role1 = Role::orderBy('id',"desc")->get();

        return Datatables::of($role1)->addColumn('action', function ($data)
        {
            return $this->getActionColumn($data);
        })->make(true);
    }

    /**
     * Add the action column to the datatable
     * @param  [type] $data the data from Datatable model
     * @return string       [description]
     */
    protected function getActionColumn($data): string
        {
            $deleteUrl = route('acl.destroy', $data->id);
            $editUrl = route('acl.edit', $data->id);
            $editDisable = (!auth()->user()
                ->can('acl::role.edit') || $data->id==1) ? "disabled" : "";
            $deleteDisable = (!auth()->user()
                ->can('acl::role.delete') || $data->id==1) ? "disabled" : "";

            return '<div class="list-icons">
          <div class="dropdown">
            <a href="#" class="list-icons-item" data-toggle="dropdown">
              <i class="icon-menu9"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
              <a href="' . $editUrl . '" class="dropdown-item ' . $editDisable . '"><i class="icon-pencil5"></i>' . __('admin/general.actions.edit') . '</a>
              <a href="#" data-url ="' . $deleteUrl . '" class="dropdown-item text-danger delete ' . $deleteDisable . '"><i class="icon-trash-alt "></i>' . __('admin/general.actions.delete') . '</a>
            </div>
          </div>
        </div>';
        }
        /**
         * Delete de role
         * @param  Request $request
         * @return json
         */
        public function destroy(Request $request)
        {
            $this->authorize('acl::role.delete');

            $ids = explode(',', $request->get('ids'));
            $messages = [];
            $deleted_roles = false;
            $ids_error = [];
            $deleted = false;
            foreach ($ids as $id)
            {
                if (empty($role = Role::find($id)) || $id == 1)
                {
                    $messages[] = trans('acl::browse.not_deleted', ["name" => $role->name]);
                    $ids_error[] = $id;
                    continue;
                }

                //  $role = Role::find($id);
                $deleted_roles = $role->delete();

                if (!$deleted_roles)
                {
                    $messages[] = trans('acl::browse.not_deleted', ["name" => $role->name]);
                    $ids_error[] = $id;
                }
            }

            if ($deleted_roles)
            {
                $messages[] = trans('acl::acl.messages.multiple_deleted');
            }

            if ($request->ajax())
            {
                return response()
                    ->json(['success' => true, 'deleted_invoices' => $deleted_roles, 'messages' => $messages, 'ids_error' => (count($ids_error) > 0) ? $ids_error : false,

                ], 200);
            }

        }
        /**
         * Show the add role forms
         * @return view
         */
        public function show()
        {
            $this->authorize('acl::role.create');
            $packages = Package::all();
            $data = [];

            if ($packages)
            {
                foreach ($packages as $key => $package)
                {
                    $opts = json_decode($package->options);

                    $data[] = ['name' => $opts->name, 'permissions' => $opts->permissions, ];
                }
            }
            // $array = [
            //   "name"=>"acl::acl.name",
            //   "permision"=>[
            //     "acl::role.read",
            //     "acl::role.delete",
            //     "acl::role.edit",
            //     "acl::role.create",
            //   ]
            //
            // ];
            //
            // echo json_encode($array);
            return view('acl::add_role', ["data" => $data]);
        }

        /**
         * Store the role and permissions
         * @param  Request $request Illuminate Request
         * @return [type]           [description]
         */
        public function store(Request $request)
        {
            $this->authorize('acl::role.create');
            $role = Role::create(["name" => $request->role_name]);
            $role->syncPermissions($request->permissions);

            $viewData = ['messages' => [['message' => trans('acl::acl.messages.saved', ['name' => $request->role_name]) , 'type' => 'success'], ], ];
            return redirect()
                ->route('acl.list_roles')
                ->with($viewData);

        }

        /**
         * Check role to see if already exist in database
         * @param  Request $request [description]
         * @return json           [description]
         */
        public function checkRoleName(Request $request)
        {
            $role_name = $request->role_name;
            $messages = [];
            $role_exist = false;
            if ($role = Role::where('name', $role_name)->first())
            {
                $role_exist = true;
                $messages[] = trans('acl::acl.messages.reole_exist');
            }
            if ($request->ajax())
            {
                return response()
                    ->json(['success' => true, 'role_exist' => $role_exist, 'messages' => $messages], 200);
            }

        }
        /**
         * Edit the
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function edit($id)
        {
            $this->authorize('acl::role.edit');
            if (empty($role = Role::find($id))) return redirect(route('acl.list_roles'));

            $packages = Package::all();
            $data = [];

            if ($packages)
            {
                foreach ($packages as $key => $package)
                {
                    $opts = json_decode($package->options);

                    $data[] = ['name' => $opts->name, 'permissions' => $opts->permissions, ];
                }
            }

            $role_permissions = $role->permissions()
                ->get();
            if ($role_permissions)
            {
                $role_p =[];
                foreach ($role_permissions as $key => $p)
                {
                    $role_p[] = $p->name;
                }
            }
            return view('acl::edit', ['role' => $role, 'data' => $data, 'role_permissions' => $role_p]);
        }

        public function update(Request $request, $id)
        {

            $this->authorize('acl::role.edit');
            $role = Role::find($id);
            $role->name = $request->role_name;
            $role->saveOrFail();

            $role->syncPermissions($request->permissions);

            $viewData = ['messages' => [['message' => trans('acl::acl.messages.updated', ['name' => $request->role_name]) , 'type' => 'success'], ], ];
            return redirect()
                ->route('acl.list_roles')
                ->with($viewData);

        }

    }
