<?php
namespace Shubhcredit\Ore\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OreController
{
    private $mdl="\App\Models\\";
    private $ore;
    private $ore_of;
    private $ore_fillable;
    private $mdl_obj;
    private $ore_action;

    public function __construct()
    {
        $this->ore_of = getOreOf();
        $this->mdl .= ucfirst($this->ore_of);
        $this->mdl_obj = new $this->mdl();
        $this->ore = $this->mdl_obj->ore;
        $this->ore_fillable = $this->mdl_obj->getFillable();
        $this->ore_action = getOreAction($this->mdl_obj);
    }

    public function index()
    {
        try {
            if(!$this->ore_action['view']){
                return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
            }

            $q_obj = $this->mdl::orderBy('id', 'desc');
            foreach ($this->ore as $ore_cp) {
                if ($ore_cp['component'] == 'option') {
                    $q_obj->with($ore_cp['name'] . '_is');
                }
            }

            if (isset($_GET['ore-trash'])) {
                $q_obj->onlyTrashed();
            }

            $result = $q_obj->get();
        } catch (\Exception $e) {
            oreExceptionLog($e);
        }
        return response()->json(['message' => "Success", 'data' => $result->toArray(), 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }

    public function create()
    {
        if(!$this->ore_action['create']){
            return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
        }
        $ore = $this->ore;
        $ore_fillable_fields = $this->ore_fillable;
        $dt = compact('ore', 'ore_fillable_fields');

        foreach ($this->ore as $ore_cp) {
            if ($ore_cp['component'] == 'option') {
                $r_mdl = "\App\Models\\" . oreRelationKey($ore_cp['relation'], 0);
                $dt[$ore_cp['name']] = $r_mdl::all()->toArray();
            } else if ($ore_cp['component'] == 'enum') {
                $dt[$ore_cp['name']] = $ore_cp['enum'];
            }
        }

        return response()->json(['status' => true, 'message' => " Data fetched Success", 'data' => $dt, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }

    public function store(Request $request)
    {
        $st=null;
        try {
            if(!$this->ore_action['save']){
                return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
            }
        $validator = Validator::make($request->all(), orevlCreate($this->ore));
        if ($validator->fails()) {
            $response = [
                'status'  => false,
                'message' => $validator->errors()->first(),
                'messages' => $validator->messages(),
            ];
            return response()->json($response, 401);
        }

        $data = $request->except('_token', 'image');

        foreach ($this->ore as $ore_cp) {
            if ($ore_cp['component'] == 'file' && $request[$ore_cp['name']]) {
                $data[$ore_cp['name']] = my_image_file_upload($request[$ore_cp['name']], 'ore/' . $this->ore_of);
            }
        }
        
            $st = $this->mdl::create($data);
            // dd($st);
        } catch (\Exception $e) {
            
            oreExceptionLog($e);
        }

        return response()->json(['status' => true, 'message' => "One Data Created Successfull", 'data' => $st, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }


    public function show(Request $request, $id)
    {
        if(!$this->ore_action['view']){
            return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
        }
        try {
            if (isset($_GET['ore-trash'])) {
                $result = $this->mdl::withTrashed()->find($id);
            } else {
                $result = $this->mdl::where('id', $id)->first();
            }
        } catch (\Exception $e) {
            oreExceptionLog($e);
        }
        return response()->json(['status' => true, 'message' => "Detail fetched successfull", 'data' => $result, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }


    public function edit(Request $request, $id)
    {
        try {
            if(!$this->ore_action['edit']){
                return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
            }
            if (isset($_GET['ore-trash'])) {
                $st = $this->mdl::withTrashed()->find($id)->restore();
            }

            if (isset($_GET['ore-trash-a'])) {
                $st = $this->mdl::onlyTrashed()->restore();
            }

            $result = $this->mdl::where('id', $id)->first();
            $ore = $this->ore;
            $dt = compact('result', 'ore');

            foreach ($this->ore as $ore_cp) {
                if ($ore_cp['component'] == 'option') {
                    $r_mdl = "\App\Models\\" . oreRelationKey($ore_cp['relation'], 0);
                    $dt[$ore_cp['name']] = $r_mdl::all()->toArray();
                } else if ($ore_cp['component'] == 'enum') {
                    $dt[$ore_cp['name']] = $ore_cp['enum'];
                }
            }
        } catch (\Exception $e) {
            oreExceptionLog($e);
        }

        return response()->json(['status' => true, 'message' => 'Edit detail fetched successfull', 'data' => $result, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }


    public function update(Request $request, $id)
    {
        if(!$this->ore_action['update']){
            return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
        }
        $validator = Validator::make($request->all(), orevlUpdate($this->ore, $id));

        if ($validator->fails()) {
            $response = [
                'status'  => false,
                'message' => $validator->errors()->first(),
                'messages' => $validator->messages(),
            ];
            return response()->json($response, 401);
        }

        $data = $request->except('_token', '_method');
        foreach ($this->ore as $ore_cp) {
            if ($ore_cp['component'] == 'file' && $request[$ore_cp['name']]) {
                $ore_record = $this->mdl::where('id', $id)->first();
                $data[$ore_cp['name']] = my_image_file_replace($request[$ore_cp['name']], 'ore/' . $this->ore_of, $ore_record[$ore_cp['name']]);
            }
        }
        try {
            $st = $this->mdl::where('id', $id)->update($data);
            $result = $this->mdl::where('id', $id)->first();
        } catch (\Exception $e) {
            oreExceptionLog($e);
        }

        return response()->json(['status' => true, 'message' => 'Data updated successfull', 'data' => $result, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }

    public function destroy($id)
    {
        try {
            if(!$this->ore_action['delete']){
                return response()->json(['message' => "Failed", 'status' => false, 'ore_of' => $this->ore_of], 200);
            }
            if (isset($_POST['delete'])) {
                $result = $this->mdl::withTrashed()->find($id)->toArray();
                foreach ($this->ore as $ore_cp) {
                    if ($ore_cp['component'] == 'file' && $result[$ore_cp['name']]) {
                        $ore_record = $this->mdl::where('id', $id)->first();
                        $data[$ore_cp['name']] = my_image_file_delete($result[$ore_cp['name']], 'ore/' . $this->ore_of);
                    }
                }
                $st = $this->mdl::withTrashed()->find($id)->forceDelete();
                return response()->json(['status' => true, 'message' => 'Data force deleted successfull', 'data' => $st, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
            } else {
                $st = $this->mdl::destroy($id);
            }
        } catch (\Exception $e) {
            oreExceptionLog($e);
        }

        return response()->json(['status' => true, 'message' => 'Data deleted successfull', 'data' => $st, 'ore_of' => $this->ore_of, 'ore_action' => $this->ore_action], 200);
    }

}