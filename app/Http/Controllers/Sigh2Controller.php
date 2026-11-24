<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class Sigh2Controller extends Controller {
 private function roles(){return DB::table('sigh2_roles')->where('user_id',auth()->id())->pluck('role')->all();}
 private function module($key){$m=config('sigh2')[$key]??null;abort_unless($m,404);abort_unless(in_array($m['role'],$this->roles()),403);return $m;}
 private function audit($key,$action,$id=null){DB::table('sigh2_audit')->insert(['user_id'=>auth()->id(),'module'=>$key,'action'=>$action,'record_id'=>$id,'created_at'=>now(),'updated_at'=>now()]);}
 public function home(){ $roles=$this->roles();$modules=array_filter(config('sigh2'),fn($m)=>in_array($m['role'],$roles));$counts=[];foreach($modules as $k=>$m)$counts[$k]=DB::table('sigh2_'.$k)->count();return view('sigh2.home',compact('modules','counts','roles')); }
 public function index(Request $r,$key){$m=$this->module($key);$q=DB::table('sigh2_'.$key);if($r->filled('q')&&ctype_digit((string)$r->q))$q->where('id',$r->q);$rows=$q->orderByDesc('id')->paginate(20)->withQueryString();$this->audit($key,'consulta');return view('sigh2.index',compact('key','m','rows'));}
 private function target($type){$s=substr(rtrim($type,'?'),1);return str_starts_with($s,'!')?substr($s,1):'sigh2_'.$s;}
 public function create($key){$m=$this->module($key);$options=[];foreach($m['fields'] as $f=>$type)if(str_starts_with($type,'@')){$table=$this->target($type);$options[$f]=DB::table($table)->select('id')->orderByDesc('id')->limit(200)->pluck('id');}return view('sigh2.form',compact('key','m','options'));}
 private function fail($field,$message){throw ValidationException::withMessages([$field=>$message]);}
 public function store(Request $r,$key){$m=$this->module($key);$rules=[];foreach($m['fields'] as $f=>$type){$rule=[str_ends_with($type,'?')?'nullable':'required'];$type=rtrim($type,'?');if(str_starts_with($type,'@')){$rule[]='integer';$rule[]=Rule::exists($this->target($type),'id');}elseif(str_contains($type,'|'))$rule[]=Rule::in(explode('|',$type));else{$rule=array_merge($rule,match($type){'i'=>['integer','min:0','max:100000000'],'m'=>['numeric','min:0','max:9999999999','decimal:0,2'],'d','h'=>['date'],'t'=>['string','max:20000'],default=>['string','max:255']});if($type==='u')$rule[]=Rule::unique('sigh2_'.$key,$f);} $rules[$f]=$rule;}$data=$r->validate($rules);
 DB::transaction(function()use($key,$data){$d=$data; $get=fn($table,$id)=>DB::table('sigh2_'.$table)->where('id',$id)->lockForUpdate()->first();
 if(isset($d['inicio'],$d['fin'])&&$d['fin']<$d['inicio'])$this->fail('fin','La fecha final debe ser posterior.');
 if($key==='lotes'&&$d['existencia']!=0)$this->fail('existencia','Inicie en cero y registre una entrada.');
 if(in_array($key,['movimientos','dispensaciones','prestaciones','compras'])&&$d['cantidad']<1)$this->fail('cantidad','Debe ser mayor a cero.');
 if(in_array($key,['movimientos','dispensaciones'])){$lot=$get('lotes',$d['lote_id']);$delta=$key==='movimientos'&&$d['tipo']==='Entrada'?$d['cantidad']:-$d['cantidad'];if($lot->existencia+$delta<0)$this->fail('cantidad','Existencia insuficiente.');if($delta<0&&$lot->vencimiento&&$lot->vencimiento<now()->toDateString())$this->fail('lote_id','Lote vencido.');if($key==='dispensaciones'){ $o=$get('ordenes',$d['orden_id']);if($o->tipo!=='Medicamento'||$o->estado==='Cancelada')$this->fail('orden_id','Orden no apta.');}DB::table('sigh2_lotes')->where('id',$lot->id)->increment('existencia',$delta);}
 if($key==='estancias'){$bed=$get('camas',$d['cama_id']);if($bed->estado!=='Disponible'||DB::table('sigh2_estancias')->where('cama_id',$bed->id)->whereNull('egreso')->exists())$this->fail('cama_id','Cama no disponible.');if(!empty($d['egreso'])&&$d['egreso']<$d['ingreso'])$this->fail('egreso','Fecha inválida.');}
 if($key==='prestaciones'){$o=$get('ordenes',$d['orden_id']);$a=$get('admisiones',$d['admision_id']);$t=$get('tarifas',$d['tarifa_id']);$c=$get('contratos',$t->contrato_id);if($o->estado!=='Realizada'||$o->admision_id!=$a->id||$t->servicio_id!=$a->servicio_id||$c->pagador_id!=$a->pagador_id||$d['fecha']<$t->inicio||$d['fecha']>$t->fin||$d['fecha']<$c->inicio||$d['fecha']>$c->fin)$this->fail('tarifa_id','Orden, contrato o tarifa no corresponde.');if(DB::table('sigh2_prestaciones')->where('orden_id',$o->id)->exists())$this->fail('orden_id','Ya registrada.');$d['total']=round($t->valor*$d['cantidad'],2);}
 if($key==='facturas'){$p=$get('prestaciones',$d['prestacion_id']);if($d['copago']>$p->total||DB::table('sigh2_facturas')->where('prestacion_id',$p->id)->exists())$this->fail('prestacion_id','Prestación facturada o copago inválido.');$d['total']=$p->total-$d['copago'];}
 if($key==='pagos'){$f=$get('facturas',$d['factura_id']);$saldo=$f->total-DB::table('sigh2_pagos')->where('factura_id',$f->id)->sum('valor');if($d['valor']<=0||$d['valor']>$saldo)$this->fail('valor','Pago fuera del saldo.');}
 if($key==='notas'&&!empty($d['nota_previa_id'])&&$get('notas',$d['nota_previa_id'])->admision_id!=$d['admision_id'])$this->fail('nota_previa_id','Adenda de otra atención.');
 $id=DB::table('sigh2_'.$key)->insertGetId($d+['autor_id'=>auth()->id(),'created_at'=>now(),'updated_at'=>now()]);$this->audit($key,'crear',$id);
 });return redirect()->route('sigh2.index',$key)->with('exito','Registro guardado.');}
 public function auditLog(){abort_unless(in_array('admin',$this->roles()),403);$rows=DB::table('sigh2_audit')->orderByDesc('id')->paginate(30);return view('sigh2.audit',compact('rows'));}
}
