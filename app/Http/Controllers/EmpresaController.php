<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmpresaController extends Controller
{

    public function validator(array $data){ 

        return Validator::make($data, [
             'rut' => 'required',
             'dv' => 'required',
             'razon_social' => 'required',
             'giro' => 'required'                 
         ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();
         
          return response()->json(
              [
                  'status' => 'success',
                  'items' => $empresas->toArray(),
              ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }

        $input = $request->all();
        $rut = $input['rut'];
        $dv = $input['dv'];
        $razon_social = $input['razon_social'];
        $giro = $input['giro'];
        $produccion_id = 1;
        $facturacion_id = 1;
        $tipo_servicio_id = 1;
     

        $empresa = Empresa::create(
         array(
                 'rut'                  => $name,
                 'dv'                   => $lastname,
                 'razon_social'         => $rut,
                 'giro'                 => $telefono,
                 'produccion_id'        => $produccion_id,
                 'facturacion_id'       => $facturacion_id,    
                 'tipo_servicio_id'     => $tipo_servicio_id,
              )
         );
        
         return response()->json(
            [
                'status' => 'success',
                'item' => $empresa->toArray()
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa, $id)
    {
        $empresa = Empresa::find($id);
        return response()->json(
            [
                'status' => 'success',
                'item' => $empresa->toArray()
            ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {

            return response()->json(
                [
                    'status' => 'error',
                    'message' => $validation->errors(),
                ], 300);
           
        }
        $empresa = Empresa::where('id',$id)->first(); 

        if(!is_null($empresa)){
    
            $input = $request->all();
            $rut = $input['rut'];
            $dv = $input['dv'];
            $razon_social = $input['razon_social'];
            $giro = $input['giro'];
            $produccion_id = 1;
            $facturacion_id = 1;
            $tipo_servicio_id = 1;
     

        $empresa = Empresa::where('id', $id)->update(
         array(
                 'rut'                  => $name,
                 'dv'                   => $lastname,
                 'razon_social'         => $rut,
                 'giro'                 => $telefono,
                 'produccion_id'        => $produccion_id,
                 'facturacion_id'       => $facturacion_id,    
                 'tipo_servicio_id'     => $tipo_servicio_id,
              )
         );
            
       
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'La empresa ha sido actualizado!!'
                ], 200);


        }else{

            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'El empresa no fue encontrado',
                ], 300);

           
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
          
            $empresa = Empresa::findOrFail($id);
             
            if(!is_null($empresa)){
                 
                 $empresa->delete();
 
                 return response()->json(
                     [
                         'status' => 'success',
                         'message' => 'La empresa ha sido eliminada!!'
                     ], 200);
             
             }else{
         
                 return response()->json(
                     [
                         'status' => 'error',
                         'message' => 'La empresa no existe!!'
                     ], 300);
             }
 
 
         }catch(ModelNotFoundException $e){
             
             return response()->json(
                 [
                     'status' => 'error',
                     'message' => 'La empresa no existe!!'
                 ], 300);
   
         }
 
    }

    public function borrar(Request $request )
    {
      
        
        $ids = array_column($request->all(), 'id');
        
        try{

            if(count($ids) > 0 ){          
                
                Empresa::destroy($ids);
                
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'Los registros ha sido eliminados!'
                    ], 200);

            }else{
        
                    return response()->json(
                        [
                            'status' => 'error',
                            'message' => 'Error al intentar eliminar los registros!'
                        ], 300);
            }
        

        }catch(ModelNotFoundException $e){
            
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Error al intentar eliminar los registros!'
                ], 300);
  
        }

        
    }
}
