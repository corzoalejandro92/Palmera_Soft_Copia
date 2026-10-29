<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\ProductWarehouseConfig;
use Illuminate\Http\Request;

class InventoryLessThanOrEquals implements Rule
{
    public $group      = null;
    public $warehouse_id    = null;
    public $request = null;
    public $maximum = 0;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request, $group)
    {
        $this->group   =  $group;
        $this->request  = $request->input($group);
        $this->warehouse_id = $request->input('warehouse_id');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $key = str_replace($this->group.'.','',$attribute);
        $key = explode('.',$key)[0];

        $pwc = ProductWarehouseConfig::
            where('product_id',$this->request[$key]['product_id'])
            ->where('warehouse_id',$this->warehouse_id)
            ->get()->last();

        if($pwc){
            $this->maximum = $pwc->maximum;
            return  $this->maximum >= $value;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "el campo :attribute supera el límite establecido en la configuración ({$this->maximum})";
    }
}
