<?php

namespace App\Livewire\Forms;

use App\Models\Airport;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AirportForm extends Form
{
    public $id = null;
    // deze werkt niet, geeft nog altijd fout op dubbele code bij wijzigen, dus met rule zie verder
    //#[Validate('required|unique:airports,code,{$this->id}', as: 'airport code')]
    public $code = null;
    #[Validate('required', as: 'city')]
    public $city = null;
    #[Validate('required|exists:airportstatuses,id', as: 'airport status')]
    public $airportstatus_id = null;

    // special validation rule for code for insert and update!
    public function rules()
    {
        return [
            'code' => "required|unique:airports,code,{$this->id}",
        ];
    }

    // $validationAttributes is used to replace the attribute name in the error message
    protected $validationAttributes = [
        'code' => 'airport code',
    ];

    // Create a new airport
    public function create()
    {
        $this->validate();
        Airport::create([
            'code' => $this->code,
            'city' => $this->city,
            'airportstatus_id' => $this->airportstatus_id,
        ]);
    }

    // Update an selected airport
    public function update()
    {
        $airport = Airport::findOrFail($this->id);
        $this->validate();
        $airport->update([
            'code' => $this->code,
            'city' => $this->city,
            'airportstatus_id' => $this->airportstatus_id,
        ]);
    }

}
