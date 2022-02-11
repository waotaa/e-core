## Eva Laravel core

## Environments files
```
.env.test
.env.staging
.env.production
.env.testing (unit tests)
```

## Setup
`php artisan eva-core:setup {--n|no-interaction} {--l|lean}`

## Force data sync with elastic
```angular2html
php artisan elastic:sync-all -f
php artisan elastic:sync-environments
```

## Tests
Make sure you setup your testing .env's before running the tests.
You probably want to use a different database for running the tests.

### Browser tests
We use laravel dusk for browser tests. It uses a .env.dusk.[env] environment file. If your test fails due to a chrome
driver failure, you first should try to update it.

Running the tests:
```
php artisan dusk:chrome-driver
php artisan dusk
```

### Unit tests
For unit testing we use phpunit. In phpunit.xml the .env.testing environment isset as the used environment file. Laravel
doesn't use the .env directly. It loads it in the config. Therefor you need to clear your config before running a test.

Running the tests:
```
php artisan config:clear
php artisan test
```

## Geo Data
Eva uses geo data from townships and labor market regions.\
There are various API's that can be used as the source for this data.\
We use the API data to generate a source file. This source file is the
single point of truth we use to create our database entries.

There are various commands to check if the source file or database is up
to date and other commands to update them if they're not.


### New source and new data set
To generate a new township and region source run

```
php artisan geo:generate-source
```

You can also generate a new source for either townships or regions.
Without the `--update-source` option you create a new source snapshot.
Run it, check the file, and when satisfied add the option to overwrite
the source file.

```
php artisan geo:townships-create-dataset {--update-source}
php artisan geo:regions-create-dataset {--update-source}
```

You can create townships and regions from the source file with the following
commands. These commands are not careful. They will create or update the data
but they will not check if current entries don't exist anymore and the items
associated with them need reallocation.
So not recommended with an existing data set.

```
php artisan geo:townships-create 
php artisan geo:regions-create 
```


### Existing source or existing data set
Once you have your data established you want to monitor for changes.
To do this you can run the following commands. These will only show
the missing items between multiple API's and the data source.

The checks do not check for content changes!

```
php artisan geo:townships-check-source-from-api
php artisan geo:regions-check-source-from-api
``` 

We currently use the Cbs Open Data Api to create the source file.
When checking for changes in the various API results, look for that one.

Create data snapshot and check them in storage under fixed/area
```
php artisan geo:townships-create-dataset
php artisan geo:regions-create-dataset
```

When your happy with the snapshot, run it again with the `update-source`
option.
```
php artisan geo:townships-create-dataset --update-source
php artisan geo:regions-create-dataset --update-source
```

You can also check if the database is up to date with the source data.
The following commands will check for missing items and content
deviations.

```
php artisan geo:townships-check-data-from-source
php artisan geo:regions-check-data-from-source
```

When you decide to update the database with the source data you can run
the update commands. These commands will show you each deviation and
offers you the choice to update the data.

Before you run this you want to have checked all the changes between the
source and the database and want the source in a state in which you can
just answer yes to all the changes.

```
php artisan geo:townships-update-data-from-source
php artisan geo:regions-update-data-from-source
```


## Professionals

The data from the id provider is leading
The data in the professionals table is a copy of the data in the
id-provider. To sync the data in the database with the data from the
id-provider you can run the following command.

```
professionals:sync
```

The commands updates the last_seen_date of all the professionals it
found. Naturally the last_seen_date of professionals missing from the
id-provider will not update. You might want to remove those from
professionals from the database. There are methods in place to do so
