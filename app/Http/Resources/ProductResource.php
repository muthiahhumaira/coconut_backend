<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    //define properti
    public $status;
    public $message;
    public $resource; // Ini sudah diwarisi dari JsonResource, tapi kita bisa definisikan ulang jika perlu

    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @return void
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource); // Pastikan resource dilewatkan ke parent constructor
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * toArray
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        // Mendapatkan data paginasi dari resource (jika resource adalah instance LengthAwarePaginator)
        $paginationData = [];
        if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginationData = [
                'total'        => $this->resource->total(),
                'count'        => $this->resource->count(), // Jumlah item di halaman saat ini
                'per_page'     => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'total_pages'  => $this->resource->lastPage(),
            ];
            // Mengambil hanya 'data' dari paginator untuk array 'data' utama
            $dataContent = $this->resource->items();
        } else {
            // Jika bukan paginator, langsung gunakan resource sebagai data
            $dataContent = $this->resource;
        }


        return [
            'message' => $this->message,
            'data'    => [
                'data' => $dataContent, // Ini akan berisi array kosong jika tidak ada data
                'page' => $paginationData,
            ],
        ];
    }
}