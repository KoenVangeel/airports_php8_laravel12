<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CarrierForm;
use App\Models\Carrier;
use App\Traits\NotificationsTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Carriers extends Component
{
    use WithPagination;
    use WithFileUploads;
    use NotificationsTrait;

    // filter and pagination
    public $filter;
    public $noLogo = false;
    public $perPage = 5;

    public $showModal = false;
    public CarrierForm $form;

    public $logo;
    public $showModalUpload = false;

    // paginator terug naar pagina 1 als nieuw aantal per page
    public function updated($property, $value): void
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property (available but often not needed here)

        // Check if the updated property is one of the filters or pagination control
        if (in_array($property, ['perPage', 'filter', 'noLogo']))
        {
            // If yes, reset the paginator back to page 1
            $this->resetPage();
        }
    }

    // reset the form and error bag
    public function resetValues()
    {
        $this->form->reset();
        $this->resetErrorBag();
    }

    public function newCarrier()
    {
        $this->resetValues();
        $this->showModal = true;
    }

    public function createCarrier()
    {
        $this->form->create();
        $this->showModal = false;
        $this->toastSuccess("The carrier <b><i>{$this->form->name}</i></b> has been added");
        $this->resetValues();
    }

    public function editCarrier(Carrier $carrier)
    {
        $this->resetValues();
        $this->form->fill($carrier);
        $this->showModal = true;
    }

    public function uploadLogoCarrier(Carrier $carrier)
    {
        $this->resetValues();
        $this->form->fill($carrier);
        $this->showModalUpload = true;
    }

    public function saveLogo()
    {
        $this->validate([
            'logo' => 'image|max:1024', // 1MB Max
        ]);

        $this->logo->storeAs('logos', $this->form->id . '.png', 'public');

        $this->showModalUpload = false;
        $this->toastSuccess("The logo for carrier <b><i>{$this->form->name}</i></b> has been uploaded.");
    }

    public function updateCarrier()
    {
        $this->form->update();
        $this->showModal = false;
        $this->toastInfo("The carrier <b><i>{$this->form->name}</i></b> has been updated",
            [
                'position' => 'bottom-right'
            ]);
        $this->resetValues();
    }

    public function deleteConfirm(Carrier $carrier)
    {
        $this->confirm(
            "Are you sure you want to delete the carrier <b><i>{$carrier->name}</i></b>?",
            [
                'heading' => "DELETE",
                'confirmText' => 'Delete carrier',
                'class' => 'bg-red-100! dark:bg-red-500!',
                'next' => [
                    'onEvent' => 'delete-carrier',
                    'carrier' => $carrier->id,
                ]
            ]
        );
    }

    // use Livewire\Attributes\On; niet vergeten, anders triggert hij het event niet
    #[On('delete-carrier')]
    public function deleteCarrier(Carrier $carrier)
    {
        $carrier->delete();
        $this->toastInfo("The carrier <b><i>{$carrier->city}</i></b> has been deleted",
            [
                'position' => 'bottom-right'
            ]
        );
    }

    public function render()
    {
        $carriers = Carrier::withCount('flights')
            ->orderBy('name')
            ->searchNameOrCode($this->filter);

        // only if $noLogo is true, filter the query further. else, skip this step
        if($this->noLogo) {
            $carriers = $carriers->logoExists(false);
        }

        $carriers = $carriers->paginate($this->perPage);

        return view('livewire.admin.carriers', compact('carriers'));
    }
}
