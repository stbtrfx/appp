<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\OrderItem;
class orderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $is_buy='';
             dd ($this->user);
            foreach($this->user as $u){
                // dd($u);
                if(($u->id ==  $request->user_id)){
                    $is_buy = 1;
                }else{
                    $is_buy = 0;
                }
            }
        // $this->user;
        // return parent::toArray($request);
        return [
           'id'=>$this->id,
           'name'=>$this->name,

           'is_buy'=>$this->is_buy,



        ];

    }
}
