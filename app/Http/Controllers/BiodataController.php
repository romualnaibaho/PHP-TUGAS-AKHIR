<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Biodata;

class BiodataController extends Controller
{
    public function getData(){

        $data = Biodata::get();

        if(count($data) > 0){
            $res['message'] = "Success!";
            $res['value'] = $data;

            return response($res);
        }else{
            $res['message'] = "Empty!";

            return response($res);
        }
    }

    public function store(Request $request){

        $this->validate($request, [
            'file' => 'required|max:2048'
        ]);

        $file = $request->file('file');
        $nama_file = time()."_".$file->getClientOriginalName();

        $tujuan_upload = 'data_file';

        if($file->move($tujuan_upload, $nama_file)){
            $data = Biodata::create([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'hobi' => $request->hobi,
                'foto' => $request->foto
            ]);

            $res['message'] = "Success!";
            $res['values'] = $data;

            return response($res);
        }
    }

    public function update(Request $request){

        if(!empty($request->file)){
            $this->validate($request, [
                'file' => 'required|max:2048'
            ]);

            $file = $request->file('file');

            $nama_file = time()."_".$file->getClientOriginalName();
            $tujuan_upload = 'data_file';

            $file->move($tujuan_upload, $nama_file);
            $data = Biodata::where('id', $request->id)->get();

            foreach($data as $data){
                @unlink(public_path('data_file/'.$data->foto));
                $ket = Biodata::where('id', $request->id)->update([
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'hobi' => $request->hobi,
                    'foto' => $request->foto
                ]);

                $res['message'] = "Success!";
                $res['values'] = $ket;

                return response($res);            
            }

        }else{

            $data = Biodata::where('id', $request->id)->get();

            foreach($data as $data){
                $ket = Biodata::where('id', $request->id)->update([
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'hobi' => $request->hobi
                ]);

                $res['message'] = "Success!";
                $res['values'] = $ket;

                return response($res);
            }
        }
    }

    public function delete($id){

        $data = Tabel12::where('id', $id)->get();

        foreach($data as $data){

            if(file_exist(public_path('data_file/'.$data->foto))){

                @unlink(public_path('data_file/'.$data->foto));
                Tabel12::where('id', $id)->delete();
                $res['message'] = "Success!";
                
                return response($res);
            }else{
                $res['message'] = "Empty!";

                return response($res);
            }
        }
    }

    public function getDetail($id){

        $data = Biodata::where('id', $id)->get();

        if(count($data) > 0){
            $res['message'] = "Success!";
            $res['value'] = $data;

            return response($res);
        }else{
            $res['message'] = "Empty!";

            return response($res);
        }
    }
}
