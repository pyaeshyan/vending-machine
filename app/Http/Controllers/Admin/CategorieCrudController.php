<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategorieRequest;
use App\Models\Categorie;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategorieCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategorieCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Categorie::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/categorie');
        CRUD::setEntityNameStrings('categorie', 'categories');
        CRUD::denyAccess(['create', 'update', 'delete', 'show']);

        if (backpack_user()->can('create_category')) {
            CRUD::allowAccess('create');
        }

        if (backpack_user()->can('edit_category')) {
            CRUD::allowAccess('update');
        }


        if (backpack_user()->can('delete_category')) {
            CRUD::allowAccess('delete');
        }

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategorieRequest::class);

        CRUD::addField([ // Select
            'label' => 'Category Name',
            'type' => 'text',
            'name' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],

        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function store(CategorieRequest $request) {
        
        try {
            $category = Categorie::create([
                    'title' => $request->title
            ]);

            if($category) {
                \Alert::add('success', 'Category created successfully!')->flash();
                return redirect()->route('categorie.index');
            } else {
                \Alert::add('danger', 'Failed to create Category!')->flash();
                return redirect()->route('categorie.index');
            }

        } catch (\Throwable $th) {
                \Alert::add('danger', 'Failed to create Category!')->flash();
                return redirect()->route('categorie.index');
        }

    }

    protected function update(CategorieRequest $request) {
        try {
            
            $category = Categorie::find($request->id);
            if($category) {
                $category->title = $request->title;
                $category->save();

                \Alert::add('success', 'Category updated successfully!')->flash();
                return redirect()->route('categorie.index');
            } else {
                \Alert::add('success', 'Failed to update Category!')->flash();
                return redirect()->route('categorie.index');
            }

        } catch (\Throwable $th) {
            \Alert::add('danger', 'Failed to update Category!')->flash();
                return redirect()->route('categorie.index');
        }
    }
}
