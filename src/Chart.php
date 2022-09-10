<?php

declare(strict_types=1);

namespace LaravelLib\ChartPHP;

/**
 * Represents a chart instance.
 */
class Chart
{
    /**
     * Stores the server data of the chart.
     *
     * @var ServerData
     */
    protected ServerData $serverData;

    /**
     * Creates a new instance of a chart.
     *
     * @param ServerData $serverData
     */
    public function __construct(ServerData $serverData)
    {
        $this->serverData = $serverData;
    }

    /**
     * Creates a new instance of a chart.
     *
     * @return Chart
     */
    public static function build(): Chart
    {
        return new Chart(new ServerData);
    }

    /**
     * Sets the chart labels.
     *
     * @param string[] $labels
     * @return Chart
     */
    public function labels(array $labels): Chart
    {
        $this->serverData->chart->labels = $labels;
        return $this;
    }

    /**
     * Adds extra information to the chart.
     *
     * @param array $value
     * @return Chart
     */
    public function extra(array $value): Chart
    {
        $this->serverData->chart->extra = $value;
        return $this;
    }

    /**
     * AdvancedDataset appends a new dataset to the chart or modifies an existing one.
     * If the ID has already been used, the dataset will be replaced with this one.
     *
     * @param string $name
     * @param array $values
     * @param array|null $extra
     * @return Chart
     */
    public function advancedDataset(string $name, array $values, ?array $extra): Chart
    {
        $dataset = $this->getDataset($name);
        if ($dataset) {
            $dataset->name = $name;
            $dataset->values = $values;
            $dataset->extra = $extra;
        } else {
            $this->serverData->datasets[] = new DatasetData($name, $values, $extra);
        }
        return $this;
    }

    /**
     * Dataset adds a new simple dataset to the chart. If more advanced control is
     * needed, consider using `AdvancedDataset` instead.
     *
     * @param string $name
     * @param array $values
     * @return Chart
     */
    public function dataset(string $name, array $values): Chart
    {
        return $this->advancedDataset($name, $values, null);
    }

    /**
     * Returns the string representation JSON encoded.
     *
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toObject());
    }

    /**
     * Transforms it to an object.
     *
     * @return ServerData
     */
    public function toObject(): ServerData
    {
        return $this->serverData;
    }

    /**
     * Gets the dataset with the given name.
     *
     * @param string $name
     * @return ServerData|null
     */
    protected function getDataset(string $name): ?DatasetData
    {
        foreach ($this->serverData->datasets as $dataset) {
            if ($dataset->name == $name) {
                return $dataset;
            }
        }
        return null;
    }
}
