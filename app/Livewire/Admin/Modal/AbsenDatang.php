<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Attributes\On;
use Livewire\Component;

class AbsenDatang extends Component
{
    public $qrCode;

    #[On('open-modal')]
    public function openModal($data)
    {
        $this->qrCode = $data['qrCode']; // Menyimpan QR Code URL yang diterima
    }

    public function render()
    {
        return view('livewire.admin.modal.absen-datang');
    }
}
