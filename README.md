# Eva Laravel core

## Setup

### Installation
To use this package in your own project run:\
`php artisan eva-core:install {--n|no-interaction}`

Make sure you publish the config this package provides with: 
`php artisan vendor:publish`

Besides config this package also offers translations and Open API specs

### Fresh instance
Before setting up a new instance first make sure your .env file is setup. See .env 
variable descriptions below.\
When setting up a new instance of the project run:

`php artisan eva-core:setup {--n|no-interaction}`\
This will freshly migrate the database and sets up some other services like the geo data.
I recommend you create your own setup script which executes this script.

### Updating an existing instance
When updating an existing instance of the project run:\
`php artisan eva-core:update {--n|no-interaction}`
This will run missing migrations.
I recommend you create your own update script which executes this script.

## Environments files
```
.env.test
.env.staging
.env.production
.env.testing (unit tests)
```

### Environment variables
All these variables are found in `.env.example`. Make sure to copy them to your own project

ELASTIC_INDEX_PREFIX
: The prefix used to prefix all elastic indeces

ELASTIC_CLOUD_ID
: The cloud id of the elastic search instance

ELASTIC_USERNAME
: The username of the elastic search instance

ELASTIC_PASSWORD
: The password of the elastic search instance

ELASTIC_PUBLIC_CLOUD_ID
: The cloud id of the **public** elastic search instance - used to store data shared on kibana boards

ELASTIC_PUBLIC_USERNAME
: The username of the **public** elastic search instance - used to store data shared on kibana boards

ELASTIC_PUBLIC_PASSWORD
: The password of the **public** elastic search instance - used to store data shared on kibana boards

FILESYSTEM_STORAGE_PATH_DOWNLOADS
FILESYSTEM_DIRECTORY_PATH_DOWNLOADS
: The directory where all files from the download entities are stored

AWS_ACCESS_KEY_ID
: AWS Access key for S3 filesystem settings

AWS_SECRET_ACCESS_KEY
: AWS Secret access key for S3 filesystem settings

AWS_REGION
: AWS region for S3 filesystem settings - defaults to `eu-central-1`

AWS_URL
: AWS url for S3 filesystem settings

AWS_BUCKET
: AWS bucket name for S3 filesystem settings

AWS_BUCKET_GEO
: AWS bucket name for storing geo data on S3 filesystem settings

AWS_SDK_ACCESS_KEY_ID
: Thw AWS acces key id for AWS cognito service API calls

AWS_SDK_SECRET_ACCESS_KEY
: Thw AWS acces key for AWS cognito service API calls

AWS_SDK_REGION
: defaults to `eu-central-1`



## CLI Commands
This package offers a number of CLI commands. You can find their description in
their classes 


## Geo Data
Eva uses geo data from townships and labor market regions.\
There are various API's that can be used as the source for this data.\
We use the API data to generate a source file. This source file is the
single point of truth we use to create our database entries.

There are various commands to check if the source file or database is up-to-date
and other commands to update them if they're not.

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

#### Check for deviations between API and Source
```
php artisan geo:townships-check-source-from-api
php artisan geo:regions-check-source-from-api
``` 

#### Snapshot creation for manually checking source set
We currently use the Cbs Open Data Api to create the source file.
When checking for changes in the various API results, look for that one.

Create data snapshot and check them in storage under fixed/area
```
php artisan geo:townships-create-dataset
php artisan geo:regions-create-dataset
```

#### Update the Source file with the data from the API
When your happy with the snapshot, run it again with the `update-source`
option.
```
php artisan geo:townships-create-dataset --update-source
php artisan geo:regions-create-dataset --update-source
```

#### Check for deviations between Source file and Database
You can also check if the database is up to date with the source data.
The following commands will check for missing items and content
deviations.

```
php artisan geo:townships-check-data-from-source
php artisan geo:regions-check-data-from-source
```

#### Update the Database with the data from the Source file
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

> **_NOTE:_** Some deployment methods might not allow for interaction. In that
case you can run the commands with the yes-to-all flag. Do make sure to
check the deviations before running the command

```
php artisan geo:townships-update-data-from-source --yes-to-all
php artisan geo:regions-update-data-from-source --yes-to-all
```

# Package contents

- Casts which can be used to cast model properties to a format
- CLI Commands
- Elastic resources for mapping database data to elastic documents
- Enums which provide static options for some model properties
- Events which are mostly used to trigger data to elastic search synchronisation
- Http requests used in the repositories to enforce validation rules
- Http resources which can be used in API reponses
- Http validation rules which are used in the requests to enforce correct user input
- Interfaces
- Jobs, mostly used for elastic synchronisation / management tasks
- Listeners for the events
- Models
- Notification messages
- Observers
- Policies
- Providers
- Repositories which can be used for storing / retrieving model entities
- Services, various business logic services
- Traits

## Professionals
When creating a professional, a user is created in Amazone Cognito.
These are users for the instrument catalog.

The data in the professionals' table is a copy of the users in
the cognito user pool. The data from the id-provider is leading

To sync the data in the database with the data from the id-provider
you can run the following command.

```
professionals:sync {environmentSlug?} {--d|destructive}
```

The commands updates the last_seen_date of all the professionals it
found. Naturally the last_seen_date of professionals missing from the
id-provider will not update. You might want to remove those from
professionals from the database. You can use the destructive option to
do so
