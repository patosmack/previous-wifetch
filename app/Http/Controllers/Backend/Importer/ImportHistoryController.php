<?php

namespace App\Http\Controllers\Backend\Importer;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Importer\ImportHistory;
use App\Models\User\MerchantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($merchant_id)
    {
        $merchant = MerchantInfo::findorFail($merchant_id);
        $merchantImports = ImportHistory::where('merchant_info_id', '=', $merchant_id)->orderBy('created_at', 'DESC')->get();
        return view('backend.importer.importer', compact('merchantImports', 'merchant_id', 'merchant'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $merchant_id = $request->get('merchant_id');
        $description = $request->get('description');
        $merchant = MerchantInfo::find($merchant_id);
        if(!$description){
            return redirect()->back()->withErrors(['error' => 'The description is required, please try again'])->withInput();
        }
        if($merchant){
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $uploadBasepath =  rtrim('import_histories', '/\\');
                $attachment_file_name =  $user->id . '_' . Helper::slug($attachment->getClientOriginalName()) . '_' .  Helper::stripString(Str::random(10)) . '.' .  $attachment->getClientOriginalExtension();
                $attachment->move(storage_path($uploadBasepath), $attachment_file_name);
                $import = new ImportHistory();
                $import->description = $description;
                $import->user_id = $user->id . '_' . Helper::slug($attachment->getClientOriginalName()) . '_' .  Helper::stripString(Str::random(10)) ;
                $import->merchant_info_id = $merchant_id;
                $import->udid = $attachment_file_name;
                $import->status = 'pending';
                $import->status_message = 'Pending Import';
                $import->file_name = $uploadBasepath . '/' .$attachment_file_name;
                if($import->save()){
                    return redirect()->back()->with(['success' => 'The import file was created successfully'])->withInput();
                }
            }
        }
        return redirect()->back()->withErrors(['error' => 'There was a problem uploading your file, please try again'])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $udid = $request->get('udid', null);
        $import = ImportHistory::where('udid', '=', $udid)->where('user_id', '=', $user->id)->first();
        if(!$import){
            return response_json('El archivo de importación no fue encontrado, intente nuevamente', 401);
        }
        $import_file = $import->file_name;
        if($import && $import->delete()){
            if($import_file and File::exists(storage_path($import_file))){
                File::delete(storage_path($import_file));
            }
            return response_json('El archivo de importación fue eliminado correctamente', 200, []);
        }
        return response_json('Ocurrió un problema al eliminar el archivo de importación, intente nuevamente', 401);
    }
}
