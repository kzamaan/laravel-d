<?php

namespace App\Http\Livewire;

use App\Exceptions\PermissionForPropertyIsNotDeclaredInControllerException;
use App\Http\Controllers\PermissionForPropertyValidation;
use App\Services\MenuService\MenuService;
use App\Services\RoleService\RoleService;
use App\Services\PermissionService\PermissionService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleList extends Component
{
    use PermissionForPropertyValidation;

    protected $permission_for = 'role';

    public $openModal = false;
    public $editableMode = false;
    public $name, $description, $permissions = [];

    public $searchKey;
    protected $queryString = ['searchKey' => ['except' => '']];
    public $sortColumnName = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 25;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles',
        'description' => 'nullable|string',
        'permissions' => 'nullable|array',
    ];

    /**
     * @param $value
     * @return void
     */
    public function updatedSelectedPage($value): void
    {
        $this->selectedItem = $value ? $this->roles->pluck('id')->toArray() : [];
    }

    /**
     * @return void
     */
    public function updatedSelectedItem(): void
    {
        $this->selectedPage = false;
    }

    /**
     * @param $columnName
     * @return void
     */
    public function sortBy($columnName): void
    {
        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortColumnName = $columnName;
    }


    public function getRolesProperty()
    {
        return Role::query()
            ->where('name', 'like', '%' . $this->searchKey . '%')
            ->orderBy($this->sortColumnName, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getMenusProperty(MenuService $menuService)
    {
        return $menuService->createdMenuItems();
    }

    public function getPermissionsListProperty(PermissionService $permissionService)
    {
        return $permissionService->permissions();
    }

    public function create()
    {
        $this->openModal = true;
    }

    public function checkAllMenu($slug)
    {
        dd($this->permissions);
    }

    public function store()
    {
        $this->validate();

        // check permission
        $this->hasPermission('create');

        // create a new rule
        $role = Role::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        if (is_array($this->permissions)) {
            // sync permission role
            $role->syncPermissions($this->permissions);
        }

        // reset form
        $this->reset();
        $this->openModal = false;
    }

    /**
     * @return View
     * @throws PermissionForPropertyIsNotDeclaredInControllerException
     */
    public function render(): View
    {
        $this->hasPermission('view');

        return view('livewire.role-list')->layoutData([
            'title' => 'Role List',
        ]);
    }
}
