<?php


namespace Tsung\NovaUserManagement\Nova\Filters;


use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Active extends Filter
{
    public $defaultFilter = '';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('is_active', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Active' => 1,
            'Not Active' => 0
        ];
    }

    public function default()
    {
        return $this->defaultFilter;
    }

    public function setFilter($filter)
    {
        $this->defaultFilter = $filter;

        return $this;
    }
}
