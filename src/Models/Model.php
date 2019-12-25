<?php


namespace App\Models;


class Model
{
    /**
     * @return int|null
     */
    public function getKey()
    {
        return isset($this->id) ? $this->id : null;
    }

    public function toArray(): array
    {
        $response = [];

        foreach ($this as $key => $value) {
            $response[$key] = $value;
        }

        return $response;
    }

    public function toObj(array $data): Model
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }
}