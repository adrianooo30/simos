<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Supplier;

use Illuminate\Http\Request;
use App\Http\Requests\Supplier\AddSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;

// custom classes dependencies
use App\Dependency\SupplierDependency;

// for plugin
use Yajra\Datatables\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $supplierDependency;

    public function  __construct(SupplierDependency $supplierDependency)
    {
        $this->supplierDependency = $supplierDependency;
    }

    public function index()
    {
        return DataTables::of( $this->supplierDependency->getSuppliers() )
                // add columns
                ->addColumn('contact', 'includes.datatables.contact')
                ->addColumn('action', function( $supplier ){

                    $actionHTML = '';

                    if(auth()->user()->can('view_supplier_history')){
                        $actionHTML .= '<a href="#" class="btn btn-primary  waves-effect waves-light mx-1">
                                            <i class="ti-bookmark-alt"></i>
                                        </a>';
                    }

                    if(auth()->user()->can('edit_supplier')){
                        $actionHTML .= '<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-supplier-modal" onclick="userDetails('.$supplier['id'].')">
                                            <i class="ti-pencil-alt"></i>
                                        </button>';
                    }

                    return $actionHTML;

                })
                // edit columns
                ->editColumn('profile_img', function( $product ){
                    return '<div align="center">
                                <img src="'.$product['profile_img'].'" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('supplier_name', 'includes.datatables.name')
                ->editColumn('address', 'includes.datatables.address')
                // raw columns
                ->rawColumns(['profile_img', 'supplier_name', 'contact', 'address', 'action'])
                //convert to json
                ->toJson();
    }

    public function store(AddSupplierRequest $request)
    {
        $supplier = Supplier::create($request->except(['profile_img_hidden']));

        $this->__storeImage($supplier);

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added a new supplier.',
            'supplier' => Supplier::orderBy('supplier_name')->get(),
        ]);
    }

    public function show(Supplier $supplier)
    {
        return $this->supplierDependency->getSupplierDetails($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->except(['profile_img_hidden']));

        $this->__storeImage($supplier);

        return response()->json([
            'title' => 'Successfully Updated.',
            'text'  => 'Any changes you made have been saved.',
        ]);
    }

    public function __storeImage($supplier)
    {
        if( request()->hasFile('profile_img') ){
            $supplier->update([
                'profile_img' => '/storage/'.request()->file('profile_img')->store('uploads', 'public'),
            ]);
        }
        else{
            $supplier->update([
                'profile_img' => request()->input('profile_img_hidden'),
            ]);
        }
    }

}
