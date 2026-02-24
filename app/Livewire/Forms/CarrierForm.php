<?php

namespace App\Livewire\Forms;

use App\Models\Carrier;
use Livewire\Form;

class CarrierForm extends Form
{
    public $id = null;
    // deze werkt niet, geeft nog altijd fout op dubbele code bij wijzigen, dus met rule zie verder
    //#[Validate('required|unique:Carriers,code,{$this->id}', as: 'Carrier code')]
    public $code = null;
    public $name = null;

    // special validation rule for code for insert and update!
    // mag wel hetzelfde bij update van dezelfde id
    public function rules()
    {
        return [
            'code' => "required|unique:Carriers,code,{$this->id}",
            'name' => "required|unique:Carriers,name,{$this->id}",
        ];
    }

    // $validationAttributes is used to replace the attribute name in the error message
    protected $validationAttributes = [
        'code' => 'Carrier code',
        'name' => 'Carrier name',
    ];

    // Create a new Carrier
    public function create()
    {
        $this->validate();
        Carrier::create([
            'code' => $this->code,
            'name' => $this->name,
        ]);
    }

    // Update a selected Carrier
    public function update()
    {
        $Carrier = Carrier::findOrFail($this->id);
        $this->validate();
        $Carrier->update([
            'code' => $this->code,
            'name' => $this->name,
        ]);
    }

}
