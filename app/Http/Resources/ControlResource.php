<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ControlResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                => $this->id,
            "name"              => $this->infestation != null ? $this->infestation->name: 'N/A',
            "area"              => $this->area  != null ?  $this->area->name : 'N/A',
            "product"           => $this->product   != null ?  $this->product->name : 'N/A',
            "dose"              => $this->dose  != null ?  $this->dose->text : 'N/A',
            "application"       => $this->application  != null ?  $this->application->name : 'N/A',
            "relatedProducts"   => $this->product && $this->product->relatedProducts ? ProductResource::collection( $this->getRelatedProductsByControlDosePattern($this->dose->text, $this->product->relatedProducts)) : [],
        ];
    }

    private function getRelatedProductsByControlDosePattern($doseText, $relatedProducts){
        // Extraer la parte del texto entre el último espacio antes del '/' y el '/'
        $pattern = '/\s+(\S+)\//';
        preg_match($pattern, $doseText, $matches);

        
    
        if (empty($matches)) {
            // Si no hay texto que coincida con el patrón, no se filtra nada
            return collect([]);
        }
    
        $unitBeforeSlash = $matches[1];
    
        // Filtrar y retornar productos relacionados donde la unidad coincida con la parte extraída
        $relatedProductsR = $relatedProducts->filter(function($relatedProduct) use ($unitBeforeSlash){
            // Comparar el texto de la unidad insensitivamente a mayúsculas/minúsculas
            $relatedUnitText = strtolower($relatedProduct->unit->name);
            $unitBeforeSlash = strtolower($unitBeforeSlash);
                        return strpos($relatedUnitText, $unitBeforeSlash) !== false;
        });
        return $relatedProductsR;
    }
}
