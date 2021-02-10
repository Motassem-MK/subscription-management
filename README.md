# Application Subscription Management

## Requirements
- Docker & docker-compose
- WSL2 (for Windows)
- Composer
- PHP 7.3+

## Setup
- Run `cp .env.example .env`
- Install dependencies by running `composer install`
- Start Sail to build and run the docker container `./vendor/bin/sail up -d`
- Run `./vendor/bin/sail artisan key:generate`
- For the scope of the assignment, we can run the scheduler using `./vendor/bin/sail artisan schedule:work`

## Testing
The project was built using TDD practice, to run all tests `./vendor/bin/sail artisan test`

## Notes

### Device
- Concerning `uID`, I assumed that we'll use the device's `GAID`/`IDFA` identifiers, which both consist of 32 hex numbers plus 4 dashes (36 letters in total).
- I assumed that a user can have a different language for each application on the same device.
- It's stated in the requirements that a different client-token must be created for each device, which -unless I misunderstood the sentence- is not enough, in order to identify which subscription to be checked, a different token should be created for each application in the device as well. 

### Application

#### app_id
- I assumed that it represents the application's _package name_.
- I assumed that an application's `app_id` is identical in its iOS and Android versions.

to figure out the max_length for it, I had to find the maximum length of the smallest bottleneck:
- Java does not set a limit to package names or class names.
- Java stores class names as `CONSTANT_Utf8_info`, which limits the length to 65535 characters.
- Filesystems of the three major Operating Systems (MacOS, Windows and Linux) has a limit of 255 Bytes on file names.

Thus, the smallest bottleneck has the maximum length of 255 characters.

#### APIs Credentials
both `google_api_credentials` and `apple_api_credentials` are nullable to tolerate that certain apps can only have an Android or iOS versions.

### Why not two simple tables?
- To allow having a different language for each application on the same device.
- To allow keeping a history of subscriptions for reporting.
- To avoid duplications (ex: uID) and null values (ex: subscription expiration date)
- The separation of `Registry` and `Subscription` and `Application` adds flexibility for possible future changes
