<?php

class Settings
{
    private array $workersDetails;
    private array $placesDetails;

    /**
     * @return array
     */
    public function getWorkersDetails(): array
    {
        return $this->workersDetails;
    }

    /**
     * @param array $workersDetails
     * @return Settings
     */
    public function setWorkersDetails(array $workersDetails): Settings
    {
        $temp = [];
        foreach($workersDetails as $k => $val) {
            $temp[$val['id']] = $val['name'] . ' ' . $val['surname'];
        }

        $this->workersDetails = $temp;
        return $this;
    }

    /**
     * @return array
     */
    public function getPlacesDetails(): array
    {
        return $this->placesDetails;
    }

    /**
     * @param array $placesDetails
     * @return Settings
     */
    public function setPlacesDetails(array $placesDetails): Settings
    {
        $temp = [];
        foreach($placesDetails as $k => $val) {
            $temp[$val['id']] = $val['name'];
        }

        $this->placesDetails = $temp;
        return $this;
    }

    public function getNameOfPlaceById(string $id): string {
        return $this->getPlacesDetails()[$id] ?? '';
    }

    public function getNameOfWorkerById(string $id): string {
        return $this->getWorkersDetails()[$id] ?? '';
    }
}