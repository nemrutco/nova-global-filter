# Nova Global Filter

This package allows you to broadcast any of your existing Laravel Nova filters to Metrics or custom cards.

![screenshot](https://user-images.githubusercontent.com/5906125/80741990-3075f180-8b4d-11ea-8b39-c7bd03d9d7b5.png)

## Installation

You can install the package in to a `Laravel` app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require nemrutco/nova-global-filter
```

## Usage

In this example, we are registering few `Metric Cards` and the `Global Filter` as:

```php
public function cards(Request $request)
{
	return [
		new NewUsers // Value Metric

		new UsersPerDay, // Trend Metric

		new UsersPerPlan, // Partition Metric

		new \Nemrutco\NovaGlobalFilter([
			new UserType, // Select Filter

			new BirthdayFilter, // Date Filter

			new UserGender // Booloean Filter
		]),
	];
}
```

And now on `Metric Card` or any other cards `filters` are available in `$request`:

```php
...
class UsersPerDay extends Trend
{
	public function calculate(NovaRequest $request)
	{
		// Get the model query before applying filters
		$model = User::query();

		// Check if there is any filters in the request
		if($request->has('filters')){
			// Apply each filter to the $model
			foreach(json_decode($request->filters,true) as $filter => $value) {
				$model = (new $filter)->apply($request,$model,$value);
			}
		}

		// Do your thing with the filtered $model
		return $this->countByDays($request, $model);
	}
	...
}

```

if you want to listen to `Global Filter` on any of `Custom Cards`:

```js
...
created() {
    Nova.$on("global-filter-changed", filter => {
      // Do your thing with the changed filter
      console.log(filter);
    });
},
...
```

## Good to know

- Basic functionality of this package is that it listens all the asigned filters. Once a value of a filter is changed, it broadcasts as `Nova.$on('global-filter-changed', [changed filter and value])`. So any card that listens to this event, will recieve the filter and it's value.
- This package overwrites Nova's default `Metric Cards` to allow them to listen `"global-filter-changed"` event. Make sure there are no any conflicts with other pacages.
- This package currently does not support Index view filters to be synchronized. So filters in `Global Filter` will not trigger update at the filters in `Filter Menu` of your Index view.
- If you are willing to support this package, it will be great to get your issues, PRs and thoughts on [Github](https://github.com/nemrutco/).

## Credits

- [Muzaffer Dede](https://github.com/muzafferdede)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
