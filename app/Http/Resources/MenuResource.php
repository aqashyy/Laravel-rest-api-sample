<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // if($this->parent_id != '0')
        // {
            // $subMenus =
            $menuItems = [
                'id'                 => $this->id,
                'title'              => $this->title,
                'alias'              => $this->alias,
                'linkaddress'        => $this->linkaddress,
                'target'             => $this->target,
                'viewtype'           => $this->viewtype,
                'type'               => $this->type,
                'parent_id'          => $this->parent_id,
                'menuorderid'        => $this->menuorderid,
            ];
        // }
        return $menuItems;
    }
}
