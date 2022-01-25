<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;

use App\Models\Models\CustomerTypes;
use App\Models\Models\Customer;

use Datatables;

class CustomerController extends Controller
{
    //

    public function index()
    {
        $customertypes = CustomerTypes::where('status', 1)->get();

        return view('welcome', compact('customertypes'));
    }

    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('customer')
            ->join('customer_types', 'customer_types.id', '=', 'customer.customer_types_id')
            ->selectRaw('customer.id, customer.nama as customername, customer_types.nama, customer.alamat, customer.latitude, customer.longitude, customer.status')
            ->orderBy('customer.id', 'ASC');

            return Datatables::of($data)
            ->addColumn('status', function($row){

                $btn = $row->status == '1' ? 'Active' : 'Inactive';

                return $btn;
            })
            ->addColumn('action', function($row){
                $btn = $row->status == '0' ? '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editModal('.$row->id.')">Edit</button>' : '';
                $btn .= $row->status == '1' ? '<button type="button" class="btn btn-danger" onclick="setStatus('.$row->id.', 0)">Set to Inactive</button>' : '<button type="button" class="btn btn-primary" onclick="setStatus('.$row->id.', 1)">Set to Active</button>';

                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama' => ['required'],
            'jenis' => ['required'],
            'alamat' => ['required'],
            'tanggal_lahir' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        $create = Customer::create([
            'nama' => $request->nama,
            'customer_types_id' => $request->jenis,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 1,
        ]);

        return back()->with(['success' => 'Sukses Tambah Data Customer']);
    }

    public function setstatus(Request $request)
    {
        $update = Customer::find($request->id);

        $change_status = $update->status == '1' ? $update->update(['status' => '0']) : $update->update(['status' => '1']);

        return response()->json(['success' => 'Berhasil mengubah status'], 200);
    }

    public function edit(Request $request)
    {
        $find = Customer::find($request->id);

        if(!$find)
        {
            return response()->json(['error' => 'Tidak ditemukan data'], 400);
        }

        return response()->json($find);
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'nama' => ['required'],
            'jenis' => ['required'],
            'alamat' => ['required'],
            'tanggal_lahir' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        $update = Customer::where('id', $request->id)->update([
            'nama' => $request->nama,
            'customer_types_id' => $request->jenis,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with(['success' => 'Sukses Edit Data Customer']);
    }
}
