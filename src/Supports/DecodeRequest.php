<?php

namespace Nsingularity\GeneralModul\Foundation\Supports;

class DecodeRequest
{
    public $filter = [];
    public $sort   = [];
    public $search = "";

    /**\
     * DecodeRequest constructor
     */
    public function __construct()
    {
        $this->filter = request()->input("filter");
        $this->sort   = request()->input("sort");
        $this->search = request()->input("search");

        if ($this->filter && !is_array($this->filter)) {
            $this->filter = json_decode($this->filter, 1);
        }

        if ($this->sort && !is_array($this->sort)) {
            $this->sort = json_decode($this->sort, 1);
        }
    }
}
